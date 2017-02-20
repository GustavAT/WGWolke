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

$list_dao = DaoFactory::createTodoListDao();
$my_lists = $list_dao->getByCreatorOid($user_oid);
$other_lists = $list_dao->getByMemberOid($user_oid);

$all_lists = array_merge($my_lists, $other_lists);

$length = count($all_lists);
$firsthalf = array_slice($all_lists, 0, $length / 2);
$secondhalf = array_slice($all_lists, $length / 2);

$member_list = DaoFactory::createUserDao()->getByCommunity($community->getObjectId());
$other_users = [];

foreach ($member_list as $value) {
    if ($value->getObjectId() != $user_oid && !$value->isLocked()) {
        $other_users[$value->getObjectId()] = $value->getFirstName() . " " . $value->getLastName();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <title> <?php echo Resources::$title_todo_overview; ?> </title>
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
        <?php PrintHelper::printNavBar(Resources::$title_todo_overview, $user, $community); ?>
        <div id="page-wrapper">
            <br />
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-green">
                        <a data-toggle="modal" data-target="#create-list-dialog">
                            <div class="panel-body">
                                <span class="pull-left"><?php echo Resources::$button_create_list; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                    <?php
                        foreach ($firsthalf as $todo_list) {
                            $todo_list->createView($user_oid);
                        }                    
                    ?>
                </div>
                <div class="col-lg-6">
                    <?php
                        foreach ($secondhalf as $todo_list) {                            
                            $todo_list->createView($user_oid);
                        }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="create-list-dialog" tabindex="-1" role="dialog" aria-labelledby="createListDialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="newTodoDialog"><?php echo Resources::$title_new_list; ?></h4>
                </div>
                <div class="modal-body">
                    <?php 
                        FormGenerator::createTextField("name", "Name", true);
                        FormGenerator::createSelectList("users", "Member", $other_users, false, true);
                    ?>
                    <div class="alert alert-danger" id="alert-incorrect-list-data">
                        <?php echo Resources::$invalid_entries; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo Resources::$button_cancel; ?></button>
                    <button type="button" class="btn btn-primary" id="button-create-list"><?php echo Resources::$button_create; ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php PrintHelper::createBlackBoardDialog(); ?>
    <?php PrintHelper::includeJS(); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            initNavBar();

            $("#alert-incorrect-list-data").hide();
            $("#name").popover({content: "Enter list name", trigger: "focus", placement: "bottom"});
            $("#name").focusout(function() {validateField("name")});            
            $("#button-create-list").click(validateListForm);
        });

        function validateListForm() {
            var name = $("#name");
            var member = $("#users");
            var isValid = validateField("name");

            if (isValid) {
                $("#alert-incorrect-list-data").hide();
                $.ajax({
                    url: "../handler/ListHandler.php",
                    type: "POST",
                    data: {
                        mode: 1,
                        user_oid: window.phpVars.userOid,
                        community_oid: window.phpVars.communityOid,
                        name: name.val(),
                        member_oids: member.val().join(",")
                    },
                    dataType: "json",                    
                }).done(function(data) {
                    var errorDiv = $("#alert-incorrect-list-data");
                    var message;
                    if (!data) {
                        message = window.phpVars.unknownError;
                    } else if (!data.success) {
                        message = data.result;
                    } else {
                        window.location.replace("ToDoEntry.php?id=" + data.result);
                        return;
                    }
                    errorDiv.text(message).show();
                }).fail(function(jqXhr, status, error) {
                    $("#alert-incorrect-list-data").text(window.phpVars.unknownError).show();
                });
            }
        }      
    </script>
</body>
</html>