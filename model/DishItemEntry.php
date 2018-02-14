<?php
require_once("Entity.php");
require_once("../code/Util.php");

class DishItemEntry extends Entity {
    private $dish_date;
    private $dish_item_oid;
    private $community_oid;

    public function __construct($_object_id, $_date_created, $_dish_date, $_dish_item_oid, $_community_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->dish_date = $_dish_date;
        $this->dish_item_oid = $_dish_item_oid;
        $this->community_oid = $_community_oid;
    }

    public static function fromRecord($record) {
        return new DishItemEntry(
            $record["oid"],
            $record["date_created"],
            $record["dish_date"],
            $record["dish_item_oid"],
            $record["community_oid"]
        );
    }

    // getter
    public function getDishDate(){return $this->dish_date;}
    public function getDishItemOid() {return $this->dish_item_oid;}
    public function getCommunityOid() {return $this->community_oid;}

    // setter
    public function setDishDate($_value){return $this->dish_date = $_value;}
    public function setDishItemOid($_value){return $this->dish_item_oid = $_value;}
    public function setCommunityOid($_value) {$this->community_oid = $_value;}

    public function toString() {
        return $this->dish_date;
    }
}