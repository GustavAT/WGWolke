<?php
require_once("Entity.php");

class DishItem extends Entity {
    private $name;
    private $community_oid;

    public function __construct($_object_id, $_date_created, $_name, $_community_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->name = $_name;
        $this->community_oid = $_community_oid;
    }

    public static function fromRecord($record) {
        return new DishItem(
            $record["oid"],
            $record["date_created"],
            $record["name"],
            $record["community_oid"]
        );
    }

    // getter
    public function getName() { return $this->name; }
    public function getCommunityOid() {return $this->community_oid;}

    // setter
    public function setName($_value) {$this->name = $_value;}
    public function setCommunityOid($_value) {$this->community_oid = $_value;}

    public function toString() {
        return $this->name;
    }
}