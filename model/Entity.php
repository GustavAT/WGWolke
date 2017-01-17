<?php
require_one("../core/Util.php");

abstract class Entity {

    protected $object_id;
    protected $date_created;

    function __construct() {
        $object_id = Util::newGuid();
        $date_created = date_create();
    }
}