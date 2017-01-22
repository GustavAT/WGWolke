<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/DishTag.php");

class DishTagDao extends AbstractDao{
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getByDishItem($dish_item_oid) {
        $sql = DishTagDao::getBaseSql();
        $sql->join("dish_tag_item dti");
        $sql->on("dt.oid = dti.dish_tag_oid");
        $sql->join("dish_item di");
        $sql->on("di.oid = dti.dish_item_oid");
        $sql->where("di.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $finance_item_oid);
        $dish_tags = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($dish_tags, DishTag::fromRecord($row));
            }
        }

        return $dish_tags;

    }

    public function getByCommunity($community_oid) {
        $sql = DishTagDao::getBaseSql();
        $sql->where("dt.community_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $dish_tags = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($dish_tags, DishTag::fromRecord($row));
            }
        }

        return $dish_tags;
    }

    public function getById($oid) {
        $sql = DishTagDao::getBaseSql();
        $sql->where("dt.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $dish_tag = null;

        if (mysqli_num_rows($records) > 0) {
            $dish_tag = DishTag::fromRecord(mysqli_fetch_assoc($records));
        }

        return $dish_tag;
    }

    public function save($dish_tag) {        
        $sql = new Sql();
        if ($this->getById($dish_tag->getObjectId()) == null) {
            $sql->insertInto("dish_tag", ["oid", "date_created", "name", "color", "community_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "sssss",
                $dish_tag->getObjectId(), $dish_tag->getDateCreated(), $dish_tag->getName(), $dish_tag->getColor(), $dish_tag->getCommunityOid());            
        } else {
            $sql->update("dish_tag");
            $sql->set(["name", "color", "community_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "ssss",
                $dish_tag->getName(), $dish_tag->getColor(), $dish_tag->getCommunityOid(), $dish_tag->getObjectId());
        }
    }

    public function removeLink($dish_tag_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_tag_item");
        $sql->where("dish_tag_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $dish_tag_oid);        
    }

    public function link($dish_tag_oid, $dish_itme_oids) {
        $sql = new Sql();
        $sql->insertInto("dish_tag_item", ["oid", "dish_tag_oid", "dish_item_oid"]);
        foreach ($dish_item_oids as $dish_item_oid) {            
            Datenbarsch::getInstance()->fishQuery($sql, "sss", Util::newGuid(), $dish_tag_oid, $dish_item_oid);
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "DishTag") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_tag");
        $sql->where("oid = ?");
        $this->removeLink($oid);
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByCommunityOid($community_oid) {
        // todo remove from dish_tag_item
        $sql = new Sql();
        $sql->delete();
        $sql->from("dish_tag");
        $sql->where("community_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("dt.*");
        $sql->from("dish_tag dt");
        return $sql;
    }
}