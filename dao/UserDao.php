<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/User.php");

class UserDao extends AbstractDao{
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getByFinanceItem($finance_item_oid) {
        $sql = UserDao::getBaseSql();
        $sql->join("finance_item_user fiu");
        $sql->on("u.oid = fiu.user_oid");
        $sql->join("finance_item fi");
        $sql->on("fi.oid = fiu.finance_item_oid");
        $sql->where("fi.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $finance_item_oid);
        $users = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($users, User::fromRecord($row));
            }
        }

        return $users;

    }

    public function getByCommunity($community_oid) {
        $sql = UserDao::getBaseSql();
        $sql->where("u.community_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $users = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($users, User::fromRecord($row));
            }
        }

        return $users;
    }

    public function getById($oid) {
        $sql = UserDao::getBaseSql();
        $sql->where("u.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $user = null;

        if (mysqli_num_rows($records) > 0) {
            $user = User::fromRecord(mysqli_fetch_assoc($records));
        }

        return $user;
    }

    public function save($user) {        
        $sql = new Sql();
        if ($this->getById($user->getObjectId()) == null) {
            $sql->insertInto("user", ["oid", "date_created", "email", "password", "first_name", "last_name", "is_locked", "reg_hash", "community_oid", "is_owner"]);
            Datenbarsch::getInstance()->fishQuery($sql, "ssssssissi",
                $user->getObjectId(), $user->getDateCreated(), $user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName(),
                $user->isLocked(), $user->getRegHash(), $user->getCommunityOid(), $user->isOwner());            
        } else {
            $sql->update("user");
            $sql->set(["email", "password", "first_name", "last_name", "is_locked", "reg_hash", "community_oid", "is_owner"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "ssssissis",
                $user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName(),
                $user->isLocked(), $user->getRegHash(), $user->getCommunityOid(), $user->isOwner(), $user->getObjectId());
        }
    }

    public function checkLogin($email, $password) {
        $sql = new Sql();
        $sql->select("u.oid");
        $sql->from("user u");
        $sql->where("u.email = ? and u.password = ?");
        $result = Datenbarsch::getInstance()->fishQuery($sql, "ss", $email, $password);
        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result)["oid"];
        }
        return null;
    }

    public function delete($oid) {
        $oid = is_a($oid, "User") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("user");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByCommunityOid($community_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("user");
        $sql->where("community_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("u.*");
        $sql->from("user u");
        return $sql;
    }
}