<?php
require_once("Entity.php");

class DishTag extends Entity {
    private $name;
    private $color;
    private $community_oid;

    public function __construct($_object_id, $_date_created, $_name, $_color, $_community_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->name = $_name;
        $this->color = $_color;
        $this->community_oid = $_community_oid;
    }

    public static function fromRecord($record) {
        return new DishTag(
            $record["oid"],
            $record["date_created"],
            $record["name"],
            $record["color"],
            $record["community_oid"]
        );
    }

    // getter
    public function getName() { return $this->name; }
    public function getColor() {return $this->color;}
    public function getCommunityOid() {return $this->community_oid;}

    // setter
    public function setName($_value) {$this->name = $_value;}
    public function setColor($_value) {$this->color = $_value;}
    public function setCommunityOid($_value) {$this->community_oid = $_value;}

    public function toString() {
        return $this->name . " <div style='height: 15px; width: 15px !important; background-color:" . $this->color . ";'></div>";
    }
}