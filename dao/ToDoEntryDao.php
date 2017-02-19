<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/ToDoEntry.php");

class ToDoEntryDao extends AbstractDao {
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getByTodoListOid($todo_list_oid) {
        $sql = ToDoEntryDao::getBaseSql();
        $sql->where("te.todo_list_oid = ? and te.is_finished = 0");
        $sql->orderBy("te.date_created desc");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $todo_list_oid);
        $todo_entries = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($todo_entries, ToDoEntry::fromRecord($row));
            }
        }

        return $todo_entries;
    }

    public function getByCreator($user_oid) {
        $sql = ToDoItemDao::getBaseSql();
        $sql->where("te.user_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $user_oid);
        $todo_entries = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($todo_entries, ToDoEntry::fromRecord($row));
            }
        }

        return $todo_entries;
    }

    public function getById($oid) {
        $sql = ToDoEntryDao::getBaseSql();
        $sql->where("te.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $todo_entry = null;

        if (mysqli_num_rows($records) > 0) {
            $todo_entry = ToDoEntry::fromRecord(mysqli_fetch_assoc($records));
        }

        return $todo_entry;
    }

    public function save($todo_entry) {        
        $sql = new Sql();
        if ($this->getById($todo_entry->getObjectId()) == null) {
            $sql->insertInto("todo_entry", ["oid", "date_created", "todo_list_oid", "description", "is_finished", "user_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "ssssis",
                $todo_entry->getObjectId(), $todo_entry->getDateCreated(), $todo_entry->getTodoListOid(),
                $todo_entry->getDescription(), $todo_entry->isFinished(), $todo_entry->getUserOid());            
        } else {
            $sql->update("todo_entry");
            $sql->set(["todo_list_oid", "description", "is_finished", "user_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "ssiss",
                $todo_entry->getTodoListOid(), $todo_entry->getDescription(), $todo_entry->isFinished(),
                $todo_entry->getUserOid(), $todo_entry->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "ToDoEntry") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("todo_entry");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByToDoListOid($todo_list_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("todo_entry");
        $sql->where("todo_list_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("te.*");
        $sql->from("todo_entry te");
        return $sql;
    }
}