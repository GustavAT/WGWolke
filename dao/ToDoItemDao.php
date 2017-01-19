<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/ToDoItem.php");

// ToDo: test
class ToDoItemDao extends AbstractDao {
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getByCommunity($community_oid) {
        $sql = ToDoItemDao::getBaseSql();
        $sql->where("td.community_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $todo_items = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($todo_items, ToDoItem::fromRecord($row));
            }
        }

        return $todo_items;
    }

    public function getByUser($user_oid) {
        $sql = ToDoItemDao::getBaseSql();
        $sql->where("td.user_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $user_oid);
        $todo_items = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($todo_items, ToDoItem::fromRecord($row));
            }
        }

        return $todo_items;
    }

    public function getById($oid) {
        $sql = ToDoItemDao::getBaseSql();
        $sql->where("td.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $todo_item = null;

        if (mysqli_num_rows($records) > 0) {
            $todo_item = ToDoItem::fromRecord(mysqli_fetch_assoc($records));
        }

        return $todo_item;
    }

    public function save($todo_item) {        
        $sql = new Sql();
        if ($this->getById($todo_item->getObjectId()) == null) {
            $sql->insertInto("todo_item", ["oid", "date_created", "description", "is_finished", "community_oid", "user_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "sssiss",
                $todo_item->getObjectId(), $todo_item->getDateCreated(), $todo_item->getDescription(),
                $todo_item->isFinished(), $todo_item->getCommunityOid(), $todo_item->getUserOid());            
        } else {
            $sql->update("todo_item");
            $sql->set(["description", "is_finished", "community_oid", "user_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "sisss",
                $todo_item->getDescription(), $todo_item->isFinished(), $todo_item->getCommunityOid(),
                $todo_item->getUserOid(), $todo_item->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "ToDoItem") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("todo_item");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByCommunityOid($community_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("todo_item");
        $sql->where("community_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("td.*");
        $sql->from("todo_item td");
        return $sql;
    }
}