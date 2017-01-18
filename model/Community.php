<?php
require_once("Entity.php");
require_once("../dao/DaoFactory.php");

class Community extends Entity {
    private $name;
    private $description;

    public function __construct($_object_id, $_date_created, $_name, $_description) {
        parent::__construct($_object_id, $_date_created);
        $this->name = $_name;
        $this->description = $_description;
    }

    public function fromRecord($record) {
        return new Community(
            $record["oid"],
            $record["date_created"],
            $record["name"],
            $record["description"]
        );
    }

    public function addModules($modules) {
        DaoFactory::createCommunityDao()->addModules($this->object_id, $modules);
    }

    // getter
    public function getName() {return $this->name;}
    public function getDescription() {return $this->description;}

    // setter
    public function setName($_value) {$this->name = $_value;}
    public function setDescription($_value) {$this->description = $_value;}

    public function toString() {
        return $this->name . ", " . $this->description;
    }
}