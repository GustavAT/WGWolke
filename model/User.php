<?php
require_once("Entity.php");

class User extends Entity {
    private $email;
    private $password;
    private $first_name;
    private $last_name;
    private $is_locked;
    private $reg_hash;
    private $community_oid;

    public function __construct($object_id, $date_created, $_email, $_password, $_first_name, $_last_name, $_is_locked, $_reg_hash, $_community_id) {
        parent::__construct($object_id, $date_created);
        $this->email = $_email;
        $this->password = $_password;
        $this->first_name = $_first_name;
        $this->last_name = $_last_name;
        $this->is_locked = $_is_locked;
        $this->reg_hash = $_reg_hash;
        $this->community_oid = $_community_id;
    }

    public static function fromRecord($record) {
        return new User(
            $record["oid"],
            $record["date_created"],
            $record["email"],
            $record["password"],
            $record["first_name"],
            $record["last_name"],
            $record["is_locked"],
            $record["reg_hash"],
            $record["community_oid"]
        );
    }

    public function toString() {
        return $this->first_name . " " . $this->last_name;
    }
}