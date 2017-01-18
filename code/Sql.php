<?php

class Sql {
    private $sql_string;

public $test;
    public function __construct() {
        $this->sql_string = "";
    }

    public function select($attributes) {
        $this->sql_string = "SELECT ";
        if (is_array($attributes)) {
            $this->sql_string .= join(",", $attributes);
        } else {
            $this->sql_string .= $attributes;
        }
        $this->sql_string .= " ";
    }

    public function from($tablename) {
        $this->sql_string .= "FROM " . $tablename . " ";
    }

    public function join($tablename) {
        $this->sql_string .= "JOIN " . $tablename . " ";
    }

    public function on($condition) {
        $this->sql_string .= "ON " . $condition . " ";
    }

    public function where($condition) {
        $this->sql_string .= "WHERE " . $condition . " ";
    }

    public function orderBy($condition) {
        $this->sql_string .= "ORDER BY " . $condition . " ";
    }

    public function getSql() {
        return $this->sql_string;
    }
}