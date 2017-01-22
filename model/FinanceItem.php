<?php
require_once("Entity.php");

class FinanceItem extends Entity {
    private $name;
    private $date_accrued;
    private $date_completed;
    private $amount;
    private $is_completed;
    private $edited;
    private $user_oid;
    private $community_oid;

    public function __construct($_object_id, $_date_created, $_name, $_date_accrued,
            $_date_completed, $_amount, $_is_completed, $_edited, $_user_oid, $_community_oid) {
        parent::__construct($_object_id, $_date_created);
        $this->name = $_name;
        $this->date_accrued = $_date_accrued;
        $this->date_completed = $_date_completed;
        $this->amount = $_amount;
        $this->is_completed = $_is_completed;
        $this->edited = $_edited;
        $this->user_oid = $_user_oid;
        $this->community_oid = $_community_oid;
    }

    public static function fromRecord($record) {
        return new FinanceItem(
            $record["oid"],
            $record["date_created"],
            $record["name"],
            $record["date_accrued"],
            $record["date_completed"],
            $record["amount"],
            $record["completed"],
            $record["edited"],
            $record["user_oid"],
            $record["community_oid"]
        );
    }

    // getter
    public function getName() { return $this->name; }
    public function getDateAccrued() {return $this->date_accrued;}
    public function getDateCompleted() {return $this->date_completed;}
    public function getAmount() {return $this->amount;}
    public function isCompleted() {return $this->is_completed;}
    public function getEdited() {return $this->edited;}
    public function getUserOid() {return $this->user_oid;}
    public function getCommunityOid() {return $this->community_oid;}

    // setter
    public function setName($_value) {$this->name = $_value;}
    public function setDateAccrued($_value) {$this->date_accrued = $_value;}
    public function setDateCompleted($_value) {$this->date_completed = $_value;}
    public function setAmount($_value) {$this->amount = $_value;}
    public function setCompleted($_value) {$this->is_completed = $_value;}
    public function setEdited($_value) {$this->edited = $_value;}
    public function setUserOid($_value) {$this->user_oid = $_value;}
    public function setCommunityOid($_value) {$this->community_oid = $_value;}

    public function toString() {
        return $this->date_accrued . " " . $this->name . " " . $this->amount;
    }
}