<?php

class Module extends Entity {
    private $name;
    private $price;

    public function __construct($_object_id, $_date_created, $_name, $_price) {
        parent::__construct($_object_id, $_date_created);
        $this->name = $_name;
        $this->price = $_price;
    }

    public static function fromRecord($record) {
        return new Module(
            $record["oid"],
            $record["date_created"],
            $record["name"],
            $record["price"]
        );
    }

    // getter
    public function getName() {return $this->name;}
    public function getPrice() {return $this->price;}

    // setter
    public function setName($_value) {$this->name = $_value;}
    public function setPrice($_value) {$this->price = $_value;}

    public function toString() {
        return $this->name . ", € " . $this->price;
    }
}