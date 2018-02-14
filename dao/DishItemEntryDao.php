<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/DishItemEntry.php");

class DishItemEntryDao extends AbstractDao {
    
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
        $sql = DishItemEntryDao::getBaseSql();
        $sql->where("di.community_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $dish_item_entries = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($dish_item_entries, DishItemEntry::fromRecord($row));
            }
        }

        return $dish_item_entries;
    }

    public function getById($oid) {
        $sql = DishItemEntryDao::getBaseSql();
        $sql->where("di.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $dish_item_entry = null;

        if (mysqli_num_rows($records) > 0) {
            $dish_item_entry = DishItemEntry::fromRecord(mysqli_fetch_assoc($records));
        }

        return $dish_item_entry;
    }

    public function save($dish_item_entry) {        
        $sql = new Sql();
        if ($this->getById($dish_item_entry->getObjectId()) == null) {
            $sql->insertInto("dish_item_entry", ["oid", "date_created", "dish_date", "dish_item_oid", "community_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "sssss",
                $dish_item_entry->getObjectId(), $dish_item_entry->getDateCreated(), $dish_item_entry->getName(), $dish_item_entry->getCommunityOid());            
        } else {
            $sql->update("dish_item_entry");
            $sql->set(["dish_date", "community_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "sss",
                $dish_item_entry->getName(), $dish_item_entry->getCommunityOid(), $dish_item_entry->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "DishItemEntry") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_item_entry");
        $sql->where("oid = ?");
        $this->removeTagLink($oid);
        $this->removeDishLink($oid);
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByCommunityOid($community_oid) {
        // todo remove from dish_tag_item and dish_item_entry
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_item_entry");
        $sql->where("community_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("die.*");
        $sql->from("dish_item_entry die");
        return $sql;
    }

    public function getTodayDish($community_oid){
        $sql = DishItemEntryDao::getBaseSql();
        $sql->where("die.dish_date = ? and die.community_oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "ss", date("Y-m-d"), $community_oid);
        $dish_item_entry = null;

        if (mysqli_num_rows($records) > 0) {
            $dish_item_entry = DishItemEntry::fromRecord(mysqli_fetch_assoc($records));
        }

        return $dish_item_entry;
    }
}