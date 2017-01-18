<?php
require_once("../code/Util.php");

abstract class Entity {

    protected $object_id;
    protected $date_created;

    function __construct($oid, $_date_created) {
        $this->object_id = isset($oid) ? $oid : Util::newGuid();
        $this->date_created = isset($_date_created) ? $_date_created : date("Y-m-d H:i:s");
    }
}