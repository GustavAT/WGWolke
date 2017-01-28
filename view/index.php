<?php
require_once("../code/PrintHelper.php");
require_once("../code/FormGenerator.php");
require_once("../code/SessionHelper.php");
require_once("../code/Util.php");
require_once("../code/Resources.php");

$logout = Util::parseGet("logout");
if ($logout) {
    SessionHelper::logOut();
}
SessionHelper::doActivity();

$user_oid = SessionHelper::getCurrentUserOid();
if ($user_oid !== null) {
    Util::redirect("Dashboard.php");
}


$login_valid = true;
$email = Util::parsePost("login-email");
$password = Util::parsePost("login-password");

$login = Util::parseGet("login");
if ($login) {
    $login_valid = SessionHelper::logIn($email, $password);
    if ($login_valid) {
        Util::redirect("Dashboard.php");
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <title> WG Wolke </title>
</head>

<body>
    
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
                <img src="../images/wg_wolke.png" class="img-responsive text-center" alt="WG Wolke Logo">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <form role="form" method="POST" action="index.php?login=1"> <!-- onsubmit="return checkLoginForm()"-->
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Login</h3>
                        </div>
                        <div class="panel-body panel-height">
                            <div>
                                <p class="lead text-muted"> <?php echo Resources::$welcome_message; ?> </p>
                            </div>
                                <fieldset>
                                    <?php FormGenerator::createEmailField("login-email", "E-Mail", true); ?>
                                    <?php FormGenerator::createPasswordField("login-password", "Password"); ?>

                                    <h6 class="text-right text-muted text-success"><a class="link-no-decoration">Forgot password?</a></h6>                                
                                </fieldset>
                                <div id="alert-login-incorrect" class="alert alert-danger">
                                    <?php echo Resources::$login_not_successful; ?>
                                </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-lg btn-success btn-block" id="button-login">Login</button>
                        </div>
                    </div>                
                </form>
            </div>

            <div class="col-md-6">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Register</h3>
                    </div>
                    <div class="panel-body panel-height text-muted">
                        <p class="lead"> Don't have an account yet? </p>
                        <p> Get access to</p>
                        <ul >
                            <li> Finances </li>
                            <li> Menuplan </li>
                            <li> Shopping List </li>
                            <li> Blackboard </li>
                            <li> many more convenient features </li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#register-dialog">Register</button>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="register-dialog" tabindex="-1" role="dialog" aria-labelledby="registerTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="registerTitle">Register</h4>
                </div>
                <div class="modal-body">
                    <p class="lead text-muted"> Community </p>
                    <?php
                        FormGenerator::createTextField("community-name", "Name", true);
                        FormGenerator::createTextarea("community-description", 3, "Description");
                    ?>
                    <p class="lead text-muted"> About you </p>
                    <?php 
                        FormGenerator::createTextField("firstname", "First Name");
                        FormGenerator::createTextField("lastname", "Last Name");
                        FormGenerator::createEmailField("email", "E-Mail");
                        FormGenerator::createPasswordField("password", "Password");
                        FormGenerator::createPasswordField("password-confirm", "Confirm");
                    ?>
                    <div class="alert alert-danger" id="alert-incorrect-data">
                        <?php echo Resources::$invalid_entries; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="button-register">Register</button>
                </div>
            </div>
        </div>
    </div>

    <?php PrintHelper::includeJS(); ?>

    <script type="text/javascript">

        $(document).ready(function() {
            $("#alert-incorrect-data").hide();
            $("#community-name").popover({content: "Community name must not be empty", trigger: "focus", placement:"bottom"});
            $("#password-confirm").popover({content: "Repeat your password", trigger: "focus", placement: "bottom"});
            $("#password").popover({content: "Passwords must be at least 8 characters long", trigger: "focus", placement: "bottom"});
            $("#button-register").click(validateRegisterForm);

            $("#community-name").focusout(validateCommunityName);
            $("#firstname").focusout(validateFirstName);
            $("#lastname").focusout(validateLastName);
            $("#email").focusout(validateEmail);
            $("#password").focusout(validatePassword);
            $("#password-confirm").focusout(validatePasswordConfirm);

            $("#login-email").val("<?php echo Util::isEmpty($email) ? "" : $email; ?>");

            $("#alert-login-incorrect").hide();
            <?php if (!$login_valid) { ?>
                $("#login-email-form-group").addClass("has-error");
                $("#login-password-form-group").addClass("has-error");
                $("#alert-login-incorrect").show();
            <?php } ?>
        });

        function validateCommunityName() {
            var communityName = $("#community-name");
            if (communityName.val().length === 0) {
                $("#community-name-form-group").addClass("has-error");
            } else {
                $("#community-name-form-group").removeClass("has-error");
            }
            return communityName.val().length !== 0;
        }

        function validateFirstName() {
            var firstName = $("#firstname");
            if (!isValidName(firstName.val())) {
                $("#firstname-form-group").addClass("has-error");
            } else {
                $("#firstname-form-group").removeClass("has-error");
            }
            return isValidName(firstName.val());
        }

        function validateLastName() {
            var lastName = $("#lastname");
            if (!isValidName(lastName.val())) {
                $("#lastname-form-group").addClass("has-error");
            } else {
                $("#lastname-form-group").removeClass("has-error");
            }
            return isValidName(lastName.val());
        }

        function validateEmail() {
            var email = $("#email");
            if (!isValidEmailAddress(email.val())) {
                $("#email-form-group").addClass("has-error");
            } else {
                $("#email-form-group").removeClass("has-error");
            }
            return isValidEmailAddress(email.val());
        }

        function validatePassword(){
            var password = $("#password");
            if (password.val().length < 8) {
                $("#password-form-group").addClass("has-error");
            } else {
                $("#password-form-group").removeClass("has-error");
            }
            return password.val().length >= 8;
        }

        function validatePasswordConfirm() {
            var passwordConfirm = $("#password-confirm");
            var password = $("#password");
            if (password.val() !== passwordConfirm.val() || passwordConfirm.val().length === 0) {
                $("#password-confirm-form-group").addClass("has-error");
            } else {
                $("#password-confirm-form-group").removeClass("has-error");
            }
            return password.val() === passwordConfirm.val();
        }

        function validateRegisterForm() {
            var communityName = $("#community-name");
            var communityDescription = $("#community-description");
            var firstName = $("#firstname");
            var lastName = $("#lastname");
            var email = $("#email");
            var password = $("#password");
            var passwordConfirm = $("#password-confirm");

            validateCommunityName();
            validateFirstName();
            validateLastName();
            validateEmail();
            validatePassword();
            validatePasswordConfirm();

            var isValid = validateCommunityName()
                && validateFirstName()
                && validateLastName()
                && validateEmail()
                && validatePassword()
                && validatePasswordConfirm();
            
            if (isValid) {
                $("#alert-incorrect-data").hide();
                $.ajax({
                    url: "../code/Registration.php",
                    type: "POST",
                    data: {
                        community_name: communityName.val(),
                        community_description: communityDescription.val(),
                        user_first_name: firstName.val(),
                        user_last_name: lastName.val(),
                        user_email: email.val(),
                        user_password: password.val(),
                        user_password_confirm: passwordConfirm.val()
                    },
                    dataType: "json"
                }).done(function(data) {
                    var errorDiv = $("#alert-incorrect-data");
                    var message;
                    if (!data) {
                        message = "<?php echo Resources::$unknown_error; ?>";
                    } else if (!data.success) {
                        message = data.result;
                    } else {
                        window.location.replace("Dashboard.php");
                        return;
                    }
                    errorDiv.text(message).show();      
                }).fail(function(jqXhr, status, error) {
                    console.log("error");
                    $("#alert-incorrect-data").text("<?php echo Resources::$unknown_error; ?>").show();
                });
            } else {
                $("#alert-incorrect-data").show();
            }
        }

        function isValidEmailAddress(emailAddress) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(emailAddress);
        };

        function isValidName(name) {
            var pattern = /^[^±!@£$%^&*_+§¡€#¢§¶•ªº«\\/<>?:;|=.,]{1,20}$/;
            return pattern.test(name);
        };

    </script>
</body>
</html>
