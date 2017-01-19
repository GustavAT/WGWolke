<?php
require_once("Entity.php");

class NewsFeedItem extends Entity {
    private $title;
    private $message;
    private $expiration_date;
    private $community_oid;
    private $user_oid;

    public function __construct($_object_id, $_date_created, $_title, $_message,
            $_expiration_date, $_community_oid, $_user_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->title = $_title;
        $this->message = $_message;
        $this->expiration_date = $_expiration_date;
        $this->community_oid = $_community_oid;
        $this->user_oid = $_user_oid;
    }

    public static function fromRecord($record) {
        return new User(
            $record["oid"],
            $record["date_created"],
            $record["title"],
            $record["message"],
            $record["expiration_date"],
            $record["community_oid"],
            $record["user_oid"]
        );
    }

    // getter
    public function getTitle() { return $this->title; }
    public function getMessage() {return $this->message;}
    public function getExpirationDate() {return $this->expiration_date;}
    public function getCommunityOid() {return $this->community_oid;}
    public function getUserOid() {return $this->user_oid;}

    // setter
    public function setTitle($_value) {$this->title = $_value;}
    public function setMessage($_value) {$this->message = $_value;}
    public function setExpirationDate($_value) {$this->expiration_date = $_value;}
    public function setCommunityOid($_value) {$this->community_oid = $_value;}
    public function setUserOid($_value) {$this->user_oid = $_value;}

    public function toString() {
        return $this->title . " " . $this->expiration_date;
    }
}