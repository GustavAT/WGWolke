<?php
require_once("../code/PrintHelper.php");
require_once("../code/Resources.php");
require_once("../code/FormGenerator.php");
require_once("../code/SessionHelper.php");
require_once("../code/Util.php");
require_once("../dao/DaoFactory.php");

SessionHelper::doActivity();
$user_oid = SessionHelper::getCurrentUserOid();
if ($user_oid === null) {
    SessionHelper::logOut();
    Util::redirect("index.php?error=0");
}

$user = DaoFactory::createUserDao()->getById($user_oid);
if ($user === null) {
    SessionHelper::logOut();
    Util::redirect("index.php?error=1");
}

$community = DaoFactory::createCommunityDao()->getByUserOid($user_oid);
if ($community === null) {
    SessionHelper::logOut();
    Util::redirect("index.php?error=2");
}

if (!$user->isOwner()) {
    Util::redirect("Dashboard.php");
}

$users = DaoFactory::createUserDao()->getByCommunity($community->getObjectId());
$length = count($users);
$firsthalf = array_slice($users, 0, $length / 2);
$secondhalf = array_slice($users, $length / 2);

?>

<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <title> <?php Resources::$title_member; ?> </title>
    <script type="text/javascript">
        window.phpVars = [];        
        <?php
            echo "phpVars.userOid = '" . $user_oid . "';";
            echo "phpVars.communityOid = '" . $community->getObjectId() . "';";
            echo "phpVars.unknownError = '" . Resources::$unknown_error . "';";
        ?>
    </script>
</head>

<body>
    
    <div class="wrapper">
        <?php PrintHelper::printNavBar(Resources::$title_member, $user, $community); ?>
        <div id="page-wrapper">
            <br />
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-green">
                        <a data-toggle="modal" data-target="#new-user-dialog">
                            <div class="panel-body">
                                <span class="pull-left"><?php echo Resources::$button_create_user; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                    <?php
                        foreach ($firsthalf as $community_user) {
                            $community_user->createView("#edit-user-dialog");
                        }                    
                    ?>
                </div>
                <div class="col-lg-6">
                    <?php
                        foreach ($secondhalf as $community_user) {
                            $community_user->createView("#edit-user-dialog");
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-user-dialog" tabindex="-1" role="dialog" aria-labelledby="editUserTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editUserTitle"><?php echo Resources::$button_edit; ?></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-left" data-dismiss="modal"><?php echo Resources::$button_cancel; ?></button>
                    <button type="button" class="btn btn-black" id="button-toggle-locked"><?php echo Resources::$button_toggle_locked; ?></button>
                    <button type="button" class="btn btn-warning" id="button-assign-ownership"><?php echo Resources::$button_assign_ownership; ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new-user-dialog" tabindex="-1" role="dialog" aria-labelledby="newUserTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="newUserTitle"><?php echo Resources::$button_create_user; ?></h4>
                </div>
                <div class="modal-body">
                    <?php 
                        FormGenerator::createTextField("firstname", "First Name");
                        FormGenerator::createTextField("lastname", "Last Name");
                        FormGenerator::createEmailField("email", "E-Mail");
                        FormGenerator::createPasswordField("password", "Password");
                        FormGenerator::createPasswordField("password-confirm", "Confirm");
                    ?>
                    <div class="alert alert-danger" id="alert-incorrect-user-data">
                        <?php echo Resources::$invalid_entries; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo Resources::$button_cancel; ?></button>
                    <button type="button" class="btn btn-primary" id="button-create"><?php echo Resources::$button_create; ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <?php PrintHelper::createBlackBoardDialog(); ?>
    <?php PrintHelper::includeJS(); ?>

    <script type="text/javascript">

        $(document).ready(function() {
            $("#alert-incorrect-user-data").hide();
            $("#password-confirm").popover({content: "Repeat your password", trigger: "focus", placement: "bottom"});
            $("#password").popover({content: "Passwords must be at least 8 characters long", trigger: "focus", placement: "bottom"});

            $("#firstname").focusout(function() {validateField("firstname")});
            $("#lastname").focusout(function() {validateField("lastname")});
            $("#email").focusout(function() {validateEmailField("email")});
            $("#password").focusout(function() {validatePasswordField("password")});
            $("#password-confirm").focusout(function() {validatePasswordEquals("password", "password-confirm")});

            $("#button-create").click(validateUserForm);
            $("#button-toggle-locked").click(toggleLockedUser);
            $("#button-assign-ownership").click(assignOwnership);
        });

        function toggleLockedUser() {            
            $.ajax({
                url: "../code/ToggleLocked.php",
                type: "POST",
                data: {
                    user_oid: globalUserOid
                },
                dataType: "json"
            }).done(function(data) {
                window.location.replace("Member.php");
            }).fail(function(jqXhr, status, error) {
                window.location.replace("Member.php");
            });
        }

        function assignOwnership() {
            $.ajax({
                url: "../code/AssignOwnership.php",
                type: "POST",
                data: {
                    target_oid: globalUserOid,
                    current_oid: "<?php echo $user_oid; ?>"
                },
                dataType: "json"
            }).done(function(data) {
                window.location.replace("Dashboard.php");
            }).fail(function(jqXhr, status, error) {
                window.location.replace("Dashboard.php");
            });
        }

        function validateUserForm() {
            var firstName = $("#firstname");
            var lastName = $("#lastname");
            var email = $("#email");
            var password = $("#password");
            var passwordConfirm = $("#password-confirm");

            validateField("firstname");
            validateField("lastname");
            validateEmailField("email");
            validatePasswordField("password");
            validatePasswordEquals("password-confirm");

            var isValid = validateField("firstname")
                && validateField("lastname")
                && validateEmailField("email")
                && validatePasswordField("password")
                && validatePasswordEquals("password", "password-confirm");
            
            if (isValid) {
                $("#alert-incorrect-user-data").hide();
                $.ajax({
                    url: "../code/CreateUser.php",
                    type: "POST",
                    data: {
                        community_oid: "<?php echo $community->getObjectId();?>",
                        first_name: firstName.val(),
                        last_name: lastName.val(),
                        email: email.val(),
                        password: password.val(),
                        password_confirm: passwordConfirm.val()
                    },
                    dataType: "json"
                }).done(function(data) {
                    var errorDiv = $("#alert-incorrect-user-data");
                    var message;
                    if (!data) {
                        message = "<?php echo Resources::$unknown_error; ?>";
                    } else if (!data.success) {
                        message = data.result;
                    } else {
                        window.location.replace("Member.php");
                        return;
                    }
                    errorDiv.text(message).show();      
                }).fail(function(jqXhr, status, error) {
                    $("#alert-incorrect-user-data").text("<?php echo Resources::$unknown_error; ?>").show();
                });
            } else {
                $("#alert-incorrect-data").show();
            }
        }

    </script>
</body>
</html>