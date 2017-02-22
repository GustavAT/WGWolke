<?php

class Sql {
    private $sql_string;

public $test;
    public function __construct() {
        $this->sql_string = "";
    }

    public function insertInto($tablename, $attributes) {
        $this->sql_string = "INSERT INTO " . $tablename . " "
            . "("
            . implode(",", $attributes)
            . ")";
        $this->sql_string .= " VALUES (";

        $count = count($attributes);
        $i = 0;
        foreach ($attributes as $value) {
            $this->sql_string .= "?";
            if ($i < $count - 1) {
                $this->sql_string .= ",";
            }
            $i++;
        }

        $this->sql_string .= ")";
    }

    public function update($tablename) {
        $this->sql_string = "UPDATE " . $tablename . " ";
    }

    public function set($attributes) {
        $this->sql_string .= "SET ";
        $count = count($attributes);
        $i = 0;
        foreach ($attributes as $value) {
            $this->sql_string .= ($value . " = ?");
            if ($i < $count - 1) {
                $this->sql_string .= ",";
            }
            $i++;
        }        
        $this->sql_string .= " ";
    }

    public function delete($alias = null) {
        $this->sql_string = "DELETE ";
        if ($alias != null) {
            $this->sql_string .= $alias . " ";
        }
    }

    public function select($attributes) {
        $this->sql_string = "SELECT ";
        if (is_array($attributes)) {
            $this->sql_string .= implode(",", $attributes);
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