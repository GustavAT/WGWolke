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

$todo_list_oid = Util::parseGet("list");
if ($todo_list_oid === null) {    
    Util::redirect("ToDoList.php?error=1");
}

// list stuff
$list_dao = DaoFactory::createToDoListDao();
$target_list = $list_dao->getById($todo_list_oid);

if ($target_list === null || $target_list->getCommunityOid() != $community->getObjectId()) {
    // list not found or list not in this community
    Util::redirect("ToDoList.php?error=2");
}

$is_list_owner = $target_list->getCreatorOid() == $user_oid;
$member_oids = $list_dao->getMemberOids($target_list->getObjectId());
$is_member = in_array($user_oid, $member_oids);

if (!$is_list_owner && !$is_member) {
    // user is neighter owner nor member of target list
    Util::redirect("ToDoList.php?error=3");
}

// items stuff

$entry_dao = DaoFactory::createTodoEntryDao();
$items = $entry_dao->getByTodoListOid($target_list->getObjectId());

// members and list owner
$user_dao = DaoFactory::createUserDao();

$list_owner = $user_dao->getById($target_list->getCreatorOid());
$member = [];
foreach ($member_oids as $value) {
    $tmp = $user_dao->getById($value);
    if ($tmp != null) {
        array_push($member,  $tmp);
    }
}

$community_users = $user_dao->getByCommunity($community->getObjectId());
$other_users = [];
foreach ($community_users as $value) {
    if ($value->getObjectId() != $user_oid && !$value->isLocked()) {
        $other_users[$value->getObjectId()] = $value->getFirstName() . " " . $value->getLastName();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <?php PrintHelper::includeDataTablesCSS(); ?>
    <title> <?php echo Resources::$title_todo_details; ?> </title>
    <script type="text/javascript">
        window.phpVars = [];        
        <?php
            echo "phpVars.userOid = '" . $user_oid . "';";
            echo "phpVars.communityOid = '" . $community->getObjectId() . "';";
            echo "phpVars.unknownError = '" . Resources::$unknown_error . "';";
            echo "phpVars.listOid = '" . $target_list->getObjectId() . "';";
            echo "phpVars.listName = '" . $target_list->getListName() . "';";
        ?>
    </script>
</head>

<body>
    
    <div class="wrapper">
        <?php PrintHelper::printNavBar(Resources::$title_todo_details, $user, $community); ?>
        <div id="page-wrapper">
            <br />
            <p class="lead">
                <?php echo htmlspecialchars($target_list->getListName());?> 
                <?php if ($is_list_owner) { ?>
                    <span class="pull-right">
                        <input type="button" data-toggle="modal" data-target="#edit-list-dialog" class="btn btn-primary" value="<?php echo Resources::$button_edit; ?>" />
                        <input type="button" data-toggle="modal" data-target="#delete-list-dialog" class="btn btn-danger" value="<?php echo Resources::$button_delete; ?>" />                        
                    </span>
                <?php } ?>
            </p>
            <hr />
            <div id="todo-entry-buttons">
                <input type="button" class="btn btn-primary" id="button-entry-create" data-toggle="modal" data-target="#create-entry-dialog" value="<?php echo Resources::$button_create; ?>" />
                <input type="button" class="btn btn-success" id="button-entry-done" value="<?php echo Resources::$button_done; ?>" />
            </div>
            <div class="col-md-12">
                <table id="table" class="table  table-hover" cellspacing="0" width="100%">
                    <thead>
                        <th> <?php echo Resources::$text_name; ?> </th>
                        <th> <?php echo Resources::$text_date_created; ?> </ht>
                        <th> oid </th>
                    </thead>
                    <tbody>
                        <?php
                            foreach($items as $item) {
                                echo "<tr><td>";
                                echo htmlspecialchars($item->getDescription());
                                echo "</td><td>";
                                echo $item->getDateCreated();
                                echo "</td><td>";
                                echo $item->getObjectId();
                                echo "</td></tr>";
                            }
                        ?>                        
                    </tbody>
                </table>
                <br />
                <p class="pull-right">
                    <?php
                        echo "<span class=\"text-muted\">" . Resources::$text_member . ": </span>";
                        PrintHelper::printTag($list_owner->getFirstName() . " " . $list_owner->getLastName(), "warning");
                        foreach ($member as $value) {
                            PrintHelper::printTag($value->getFirstName() . " " . $value->getLastName(), $value->isLocked() ? "default" : "primary");
                        }
                    ?>
                </p>
            </div>

        </div>
    </div>

    <div class="modal fade" id="delete-list-dialog" tabindex="-1" role="dialog" aria-labelledby="deleteListDialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="deleteListDialog"><?php echo Resources::$are_you_sure; ?></h4>
                </div>
                <div class="modal-body">
                    <p> <?php echo Resources::$text_list_deletion; ?> </p>                    
                    <div class="alert alert-danger" id="alert-incorrect-list-data">
                        <?php echo Resources::$invalid_entries; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline btn-danger" data-dismiss="modal"><?php echo Resources::$button_cancel; ?></button>
                    <button type="button" class="btn btn-danger" id="button-delete-list"><?php echo Resources::$button_delete; ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-list-dialog" tabindex="-1" role="dialog" aria-labelledby="editListDialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editListDialog"><?php echo Resources::$button_edit; ?></h4>
                </div>
                <div class="modal-body">
                    <?php 
                        FormGenerator::createTextField("name", "Name", true);
                        FormGenerator::createUserList("users", $other_users, $member_oids, Resources::$title_member);
                    ?>
                    <div class="alert alert-danger" id="alert-incorrect-list-data2">
                        <?php echo Resources::$invalid_entries; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo Resources::$button_cancel; ?></button>
                    <button type="button" class="btn btn-primary" id="button-edit-list"><?php echo Resources::$button_save; ?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="create-entry-dialog" tabindex="-1" role="dialog" aria-labelledby="createEntryDialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="createEntryDialog"><?php echo Resources::$button_create_item; ?></h4>
                </div>
                <div class="modal-body">
                    <?php 
                        FormGenerator::createTextField("title", "Title", true);                        
                    ?>
                    <div class="alert alert-danger" id="alert-incorrect-entry-data">
                        <?php echo Resources::$invalid_entries; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo Resources::$button_cancel; ?></button>
                    <button type="button" class="btn btn-primary" id="button-save-entry"><?php echo Resources::$button_create; ?></button>
                </div>
            </div>
        </div>
    </div>

    <?php PrintHelper::createBlackBoardDialog(); ?>
    <?php PrintHelper::includeJS(); ?>
    <?php PrintHelper::includeDataTablesJS(); ?>

    <script type="text/javascript">
        var dataTable = null;

        $(document).ready(function() {
            initNavBar();

            $("#alert-incorrect-list-data").hide();
            $("#alert-incorrect-list-data2").hide();
            $("#alert-incorrect-entry-data").hide();

            $("#button-delete-list").click(deleteList);
            $("#button-edit-list").click(validateListForm);

            $("#name").val(window.phpVars.listName);
            $("#name").popover({content: "Enter list name", trigger: "focus", placement: "bottom"});
            $("#name").focusout(function() {validateField("name")});

            $("#button-entry-done").addClass("disabled");
            $("#button-entry-done").click(doneSelectedItems);

            $("#title").focusout(function() {validateField("title")});
            $("#button-save-entry").click(validateEntryForm);

            dataTable = $("#table").DataTable({
                ordering: true,
                paging: false,
                select: true,
                columnDefs: [{
                    targets: [2],
                    visible: false,
                    searchable: false
                }],
                language: {
                    zeroRecords: "<?php echo Resources::$zero_records; ?>"
                }
            });

            $("#table tbody").on("click", "tr", rowClickHandler);

            var firstRow = $("#table_wrapper .row .col-sm-6")[0];
            if (firstRow) {
                $("#todo-entry-buttons").detach().appendTo($(firstRow));
            }

        });

        function doneSelectedItems() {
            var rows = dataTable.rows(".selected").data();
            var ids = rows.map(function(record) {
                return record[2];
            });
            if (ids.length > 0) {
                $.ajax({
                    url: "../handler/ToDoHandler.php",
                    type: "POST",
                    data: {
                        ids: ids.join(";"),
                        mode: 1
                    },
                    dataType: "json"
                }).done(function(data) {
                    if (data && data.success) {
                        dataTable.rows(".selected").remove().draw(false);
                        $("#button-entry-done").addClass("disabled");
                    }     
                });
            }
        }

        function validateEntryForm() {
            var title = $("#title");
            var isValid = validateField("title");

            if (isValid) {
                $("#alert-incorrect-entry-data").hide();
                $.ajax({
                    url: "../handler/ToDoHandler.php",
                    type: "POST",
                    data: {
                        user_oid: window.phpVars.userOid,
                        description: title.val(),
                        list_oid: window.phpVars.listOid,
                        mode: 2
                    },
                    dataType: "json"
                }).done(function(data) {
                    var errorDiv = $("#alert-incorrect-entry-data");
                    var message;
                    if (!data) {
                        message = "<?php echo Resources::$unknown_error; ?>";
                    } else if (!data.success) {
                        message = data.result;
                    } else {
                        dataTable.row.add([
                            data.result.title,
                            data.result.dateCreated,
                            data.result.oid
                        ]).draw(false);
                        $("#title").val("");
                        $("#create-entry-dialog").modal("hide");
                        return;
                    }
                    errorDiv.text(message).show();      
                }).fail(function(jqXhr, status, error) {
                    $("#alert-incorrect-entry-data").text("<?php echo Resources::$unknown_error; ?>").show();
                });
            }
        }

        function rowClickHandler() {
            $(this).toggleClass("selected");                
            var selected = $("#table .selected");
            if (selected.length > 0) {
                $("#button-entry-done").removeClass("disabled");
            } else {
                $("#button-entry-done").addClass("disabled");
            }
        }

        function deleteList() {
            $.ajax({
                url: "../handler/ListHandler.php",
                type: "POST",
                data: {
                    mode: 2,
                    user_oid: window.phpVars.userOid,
                    community_oid: window.phpVars.communityOid,
                    list_oid: window.phpVars.listOid
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
                    window.location.replace("ToDoList.php?success=1");
                    return;
                }
                errorDiv.text(message).show();
            }).fail(function(jqXhr, status, error) {
                $("#alert-incorrect-list-data").text(window.phpVars.unknownError).show();
            });
        }

        function validateListForm() {
            var name = $("#name");
            var isValid = validateField("name");

            var memberEl = $("#users-form-group")
                .find("input[type=checkbox]:checked");
            var memberIds = [];
            for (var i = memberEl.length; i-- > 0;) {
                memberIds.push(memberEl[i].id);
            }

            if (isValid) {
                $("#alert-incorrect-list-data2").hide();
                $.ajax({
                    url: "../handler/ListHandler.php",
                    type: "POST",
                    data: {
                        mode: 1,
                        user_oid: window.phpVars.userOid,
                        community_oid: window.phpVars.communityOid,
                        list_oid: window.phpVars.listOid,
                        name: name.val(),
                        member_oids: memberIds.join(","),
                    },
                    dataType: "json",                    
                }).done(function(data) {
                    var errorDiv = $("#alert-incorrect-list-data2");
                    var message;
                    if (!data) {
                        message = window.phpVars.unknownError;
                    } else if (!data.success) {
                        message = data.result;
                    } else {
                        window.location.replace("ToDoListDetails.php?list=" + data.result);
                        return;
                    }
                    errorDiv.text(message).show();
                }).fail(function(jqXhr, status, error) {
                    $("#alert-incorrect-list-data2").text(window.phpVars.unknownError).show();
                });
            }
        }      
    </script>
</body>
</html>