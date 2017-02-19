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

?>

<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <?php PrintHelper::includeDataTablesCSS(); ?>
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
                        <a data-toggle="modal" data-target="#new-todo-list-dialog">
                            <div class="panel-body">
                                <span class="pull-left"><?php echo Resources::$button_create_todo_list; ?></span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                    <?php
                        foreach ($firsthalf as $todo_list) {
                            PrintHelper::printListTile($todo_list, $user_oid);
                        }                    
                    ?>
                </div>
                <div class="col-lg-6">
                    <?php
                        foreach ($secondhalf as $todo_list) {
                            PrintHelper::printListTile($todo_list, $user_oid);
                        }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <!--<div class="modal fade" id="create-todo-dialog" tabindex="-1" role="dialog" aria-labelledby="newTodoDialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="newTodoDialog"><?php echo Resources::$button_create_todo; ?></h4>
                </div>
                <div class="modal-body">
                    <?php 
                        FormGenerator::createTextField("title", "Title", true);                        
                    ?>
                    <div class="alert alert-danger" id="alert-incorrect-todo-data">
                        <?php echo Resources::$invalid_entries; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo Resources::$button_cancel; ?></button>
                    <button type="button" class="btn btn-primary" id="button-save-todo"><?php echo Resources::$button_create; ?></button>
                </div>
            </div>
        </div>
    </div>-->

    <?php PrintHelper::createBlackBoardDialog(); ?>
    <?php PrintHelper::includeJS(); ?>
    <?php PrintHelper::includeDataTablesJS(); ?>

    <script type="text/javascript">
        // var dataTable = null;
        // $("#alert-incorrect-todo-data").hide();
        // $("#title").focusout(function() {validateField("title")});
        // $("#button-save-todo").click(validateTodoForm);

        // $("#button-done").addClass("disabled");
        // $("#button-done").click(doneSelectedItems);
        
        // $(document).ready(function() {
        //     dataTable = $("#table").DataTable({
        //         ordering: true,
        //         paging: false,
        //         select: true,
        //         columnDefs: [
        //             {
        //                 targets: [2],
        //                 visible: false,
        //                 searchable: false
        //             }
        //         ],
        //         language: {
        //             zeroRecords: '<div class="nothing-center"></div>'
        //         }             
        //     });

        //     $("#table tbody").on("click", "tr", function() {
        //         $(this).toggleClass("selected");
                
        //         var selected = $("#table .selected");
        //         if (selected.length > 0) {
        //             $("#button-done").removeClass("disabled");
        //         } else {
        //             $("#button-done").addClass("disabled");
        //         }                
        //     })

        // });

        // function doneSelectedItems() {
        //     var rows = dataTable.rows(".selected").data();
        //     var ids = rows.map(function(record) {
        //         return record[2];
        //     });
        //     if (ids.length > 0) {
        //         $.ajax({
        //             url: "../handler/FinishTodo.php",
        //             type: "POST",
        //             data: {
        //                 ids: ids.join(";"),
        //             },
        //             dataType: "json"
        //         }).done(function(data) {
        //             if (data && data.success) {
        //                 dataTable.rows(".selected").remove().draw(false);
        //             }     
        //         });
        //     }
        // }

        // function validateTodoForm() {
        //     var title = $("#title");
        //     var isValid = validateField("title");

        //     if (isValid) {
        //         $("#alert-incorrect-todo-data").hide();
        //         $.ajax({
        //             url: "../handler/CreateTodo.php",
        //             type: "POST",
        //             data: {
        //                 user_oid: "<?php echo $user->getObjectId();?>",
        //                 community_oid: "<?php echo $community->getObjectId();?>",
        //                 title: title.val()
        //             },
        //             dataType: "json"
        //         }).done(function(data) {
        //             var errorDiv = $("#alert-incorrect-todo-data");
        //             var message;
        //             if (!data) {
        //                 message = "<?php echo Resources::$unknown_error; ?>";
        //             } else if (!data.success) {
        //                 message = data.result;
        //             } else {
        //                 dataTable.row.add([
        //                     data.result.title,
        //                     data.result.dateCreated,
        //                     data.result.oid
        //                 ]).draw(false);
        //                 $("#title").val("");
        //                 $("#create-todo-dialog").modal("hide");
        //                 return;
        //             }
        //             errorDiv.text(message).show();      
        //         }).fail(function(jqXhr, status, error) {
        //             $("#alert-incorrect-todo-data").text("<?php echo Resources::$unknown_error; ?>").show();
        //         });
        //     }
        // }       

    </script>
</body>
</html>