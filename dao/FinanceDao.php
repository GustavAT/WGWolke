<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/FinanceItem.php");

class FinanceDao extends AbstractDao {
    
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
        $sql = FinanceDao::getBaseSql();
        $sql->where("fi.community_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $finance_items = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($finance_items, FinanceItem::fromRecord($row));
            }
        }

        return $finance_items;
    }

    public function getById($oid) {
        $sql = FinanceDao::getBaseSql();
        $sql->where("fi.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $finance_item = null;

        if (mysqli_num_rows($records) > 0) {
            $finance_item = FinanceItem::fromRecord(mysqli_fetch_assoc($records));
        }

        return $finance_item;
    }

    public function removeLink($finance_item_oid, $user_oids = null) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("finance_item_user");
        if (isset($user_oids)) {
            foreach ($user_oids as $user_oid) {
                $sql->where("finance_item_oid = ? and user_oid = ?");
                Datenbarsch::getInstance()->fishQuery($sql, "ss", $finance_item_oid, $user_oid);
            }
        } else {
            $sql->where("finance_item_oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "s", $finance_item_oid);
        }
        
    }

    public function link($finance_item_oid, $user_oids) {
        $sql = new Sql();
        $sql->insertInto("finance_item_user", ["oid", "finance_item_oid", "user_oid"]);
        foreach ($user_oids as $user_oid) {            
            Datenbarsch::getInstance()->fishQuery($sql, "sss", Util::newGuid(), $finance_item_oid, $user_oid);
        }
    }

    public function save($finance_item) {
        $sql = new Sql();
        if ($this->getById($finance_item->getObjectId()) == null) {
            $sql->insertInto("finance_item", ["oid", "date_created", "name", "date_accrued", "date_completed", "amount", "completed", "edited", "user_oid", "community_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "sssssdiiss",
                $finance_item->getObjectId(), $finance_item->getDateCreated(), $finance_item->getName(), $finance_item->getDateAccrued(), $finance_item->getDateCompleted(), $finance_item->getAmount(),
                $finance_item->isCompleted(), $finance_item->getEdited(), $finance_item->getUserOid(), $finance_item->getCommunityOid());            
        } else {
            $sql->update("finance_item");
            $sql->set(["name", "date_accrued", "date_completed", "amount", "completed", "edited", "user_oid", "community_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "sssdiisss",
                $finance_item->getName(), $finance_item->getDateAccrued(), $finance_item->getDateCompleted(), $finance_item->getAmount(),
                $finance_item->isCompleted(), $finance_item->getEdited(), $finance_item->getUserOid(), $finance_item->getCommunityOid(), $finance_item->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "FinanceItem") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("finance_item");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByCommunityOid($community_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("fi");
        $sql->where("community_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("fi.*");
        $sql->from("finance_item fi");
        return $sql;
    }
}