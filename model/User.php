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

    public function __construct($_object_id, $_date_created, $_email, $_password, $_first_name, $_last_name, $_is_locked, $_reg_hash, $_community_id) {
        parent::__construct($_object_id, $_date_created);
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

    public function getEmail() { return $this->email; }
    public function getPassword() {return $this->password;}
    public function getFirstName() {return $this->first_name;}
    public function getLastName() {return $this->last_name;}
    public function isLocked() {return $this->is_locked;}
    public function getRegHash() {return $this->reg_hash;}
    public function getCommunityOid() {return $this->community_oid;}

    public function setEmail($_value) {$this->email = $_value;}
    public function setPassword($_value) {$this->password = $_value;}
    public function setFirstName($_value) {$this->first_name = $_value;}
    public function setLastName($_value) {$this->last_name = $_value;}
    public function setLocked($_value) {$this->is_locked = $_value;}
    public function setRegHash($_value) {$this->reg_hash = $_value;}
    public function setCommunityOid($_value) {$this->community_oid = $_value;}

    public function toString() {
        return $this->first_name . " " . $this->last_name;
    }
}