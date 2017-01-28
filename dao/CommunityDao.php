<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/Community.php");
require_once("../model/Module.php");

class CommunityDao extends AbstractDao{
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    /*
    * IMPORTANT: only call once if a community is created
    */
    public function addModules($community_oid, $modules) {
        $sql = new Sql();
        $sql->insertInto("module_community", ["oid", "module_oid", "community_oid"]);
        foreach ($modules as $value) {
            Datenbarsch::getInstance()->fishQuery($sql, "sss",
                Util::newGuid(), $value->getObjectId(), $community_oid);
        }
    }

    public function getModules($community_oid) {
        $sql = new Sql();
        $sql->select(["m.oid", "m.date_created", "m.name", "m.type", "m.price"]);
        $sql->from("module_community mc");
        $sql->join("module m");
        $sql->on("mc.module_oid = m.oid");
        $sql->where("mc.community_oid = ?");
        $sql->orderBy("m.type");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $modules = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($modules, Module::fromRecord($row));
            }
        }

        return $modules;
    }

    public function getUserCount($oid) {
        $sql = new Sql();
        $sql->select("count(1) as cnt");
        $sql->from("user u");
        $sql->where("u.community_oid = ?");

        $record = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $count = -1;

        if (mysqli_num_rows($record) > 0) {
            $count = mysqli_fetch_assoc($record)["cnt"];
        }

        return $count;
    }

    public function deleteModules($community_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("module_community");
        $sql->where("community_oid = ?");

        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    public function getByUserOid($user_oid) {
        $sql = new Sql();
        $sql->select("u.community_oid");
        $sql->from("user u");
        $sql->where("u.oid = ?");

        $result = Datenbarsch::getInstance()->fishQuery($sql, "s", $user_oid);
        $community = null;

        if (mysqli_num_rows($result) > 0) {
            $id = mysqli_fetch_assoc($result)["community_oid"];
            if ($id) {
                $community = $this->getById($id);
            }
        }

        return $community;
    }

    public function getById($oid) {
        $sql = CommunityDao::getBaseSql();
        $sql->where("c.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $community = null;

        if (mysqli_num_rows($records) > 0) {
            $community = Community::fromRecord(mysqli_fetch_assoc($records));
        }

        return $community;
    }

    public function save($community) {        
        $sql = new Sql();
        if ($this->getById($community->getObjectId()) == null) {
            $sql->insertInto("community", ["oid", "date_created", "name", "description"]);
            Datenbarsch::getInstance()->fishQuery($sql, "ssss",
                $community->getObjectId(), $community->getDateCreated(), $community->getName(),
                $community->getDescription());
        } else {
            $sql->update("community");
            $sql->set(["name", "description"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "sss",
                $community->getName(), $community->getDescription(), $community->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "Community") ? $oid->getObjectId() : $oid;
        $this->deleteModules($oid);
        DaoFactory::createUserDao()->deleteByCommunityOid($oid);
        $sql = new Sql();
        $sql->delete();
        $sql->from("community");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("c.*");
        $sql->from("community c");
        return $sql;
    }
}