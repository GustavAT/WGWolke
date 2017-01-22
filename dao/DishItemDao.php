<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/DishItem.php");

class DishItemDao extends AbstractDao {
    
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
        $sql = DishItemDao::getBaseSql();
        $sql->where("di.community_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $dish_items = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($dish_items, DishItem::fromRecord($row));
            }
        }

        return $dish_items;
    }

    public function getById($oid) {
        $sql = DishItemDao::getBaseSql();
        $sql->where("di.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $dish_item = null;

        if (mysqli_num_rows($records) > 0) {
            $dish_item = DishItem::fromRecord(mysqli_fetch_assoc($records));
        }

        return $dish_item;
    }

    public function save($dish_item) {        
        $sql = new Sql();
        if ($this->getById($dish_item->getObjectId()) == null) {
            $sql->insertInto("dish_item", ["oid", "date_created", "name", "community_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "ssss",
                $dish_item->getObjectId(), $dish_item->getDateCreated(), $dish_item->getName(), $dish_item->getCommunityOid());            
        } else {
            $sql->update("dish_item");
            $sql->set(["name", "community_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "sss",
                $dish_item->getName(), $dish_item->getCommunityOid(), $dish_item->getObjectId());
        }
    }

    public function removeTagLink($dish_item_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_tag_item");
        $sql->where("dish_item_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $dish_item_oid);        
    }

    public function linkTag($dish_item_oid, $dish_tag_oids) {
        $sql = new Sql();
        $sql->insertInto("dish_tag_item", ["oid", "dish_tag_oid", "dish_item_oid"]);
        foreach ($dish_tag_oids as $dish_tag_oid) {
            Datenbarsch::getInstance()->fishQuery($sql, "sss", Util::newGuid(), $dish_tag_oid, $dish_item_oid);
        }
    }

    public function removeDishLink($dish_item_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_item_entry");
        $sql->where("dish_item_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $dish_item_oid);
    }

    public function linkDish($dish_item_oid, $dish_date, $user_oid) {
        $sql = new Sql();
        $sql->insertInto("dish_item_entry", ["oid", "date_created", "dish_date", "dish_item_oid", "user_oid"]);
        Datenbarsch::getInstance()->fishQuery($sql, "sssss", Util::newGuid(), Util::now(), $dish_date, $dish_item_oid, $user_oid);
    }

    public function delete($oid) {
        $oid = is_a($oid, "DishItem") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_item");
        $sql->where("oid = ?");
        $this->removeTagLink($oid);
        $this->removeDishLink($oid);
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByCommunityOid($community_oid) {
        // todo remove from dish_tag_item and dish_item_entry
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_item");
        $sql->where("community_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("di.*");
        $sql->from("dish_item di");
        return $sql;
    }
}