<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/ToDoList.php");

// ToDo: test
class ToDoListDao extends AbstractDao {
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getMemberOids($todo_list_oid) {
        $sql = new Sql();
        $sql->select("tlu.user_oid");
        $sql->from("todo_list_user tlu");
        $sql->where("tlu.todo_list_oid = ?");

        $records = Datenbarsch::getIntance()->fishQuery($sql, "s", $todo_list_oid);
        $user_oids = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($tuser_oids, $row["user_oid"]);
            }
        }

        return $user_oids;
    }

    public function addMember($todo_list_oid, $member_oid) {
        $sql = new Sql();
        $sql->insertInto("todo_list_user", ["oid", "date_created", "todo_list_oid", "user_oid"]);
        Datenbarsch::getInstance()->fishQuery($sql, "ssss",
            Utilty::newGuid(), Utiliy::now(), $todo_list_oid, $member_oid);
    }

    public function removeMember($todo_list_oid, $member_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("todo_list_user tlu");
        $sql->where("tlu.todo_list_oid = ? and tlu.user_oid = ?");

        Datenbarsch::getInstance()->fishQuery($sql, "ss", $todo_list_oid, $member_oid);
    }

    public function getByMemberOid($member_oid) {
        $sql = ToDoListDao::getBaseSql();
        $sql->join("todo_list_user tlu");
        $sql->on("tl.oid = tlu.todo_list_oid");
        $sql->where("tlu.user_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $member_oid);
        $todo_items = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($todo_lists, ToDoList::fromRecord($row));
            }
        }

        return $todo_lists;
    }

    public function getByCreatorOid($creator_oid) {
        $sql = ToDoListDao::getBaseSql();
        $sql->where("tl.creator_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $user_oid);
        $todo_lists = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($todo_lists, ToDoList::fromRecord($row));
            }
        }

        return $todo_Lists;
    }

    public function getById($oid) {
        $sql = ToDoListDao::getBaseSql();
        $sql->where("tl.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $todo_list = null;

        if (mysqli_num_rows($records) > 0) {
            $todo_list = ToDoList::fromRecord(mysqli_fetch_assoc($records));
        }

        return $todo_list;
    }

    public function save($todo_list) {        
        $sql = new Sql();
        if ($this->getById($todo_list->getObjectId()) == null) {
            $sql->insertInto("todo_list", ["oid", "date_created", "community_oid", "list_name", "creator_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "sssss",
                $todo_list->getObjectId(), $todo_list->getDateCreated(), $todo_list->getCommunityOid(),
                $todo_list->getListName(), $todo_list->getCreatorOid());            
        } else {
            $sql->update("todo_list");
            $sql->set(["communiy_oid", "list_name", "creator_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "ssss",
                $todo_list->getCommunityOid(), $todo_list->getListName(),
                $todo_list->getCreatorOid(), $todo_list->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "ToDoList") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("todo_list");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    // TODO
    // public function deleteByCommunityOid($community_oid) {
    //     $sql = new Sql();
    //     $sql->delete();
    //     $sql->from("todo_item");
    //     $sql->where("community_oid = ?");
    //     Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    // }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("tl.*");
        $sql->from("todo_list tl");
        return $sql;
    }
}