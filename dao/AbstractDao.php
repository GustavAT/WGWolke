<?php
/*
* Abstract Dao class that supports CRUD operations
*/
abstract class AbstractDao {

    /**
    * Get all entities of this type
    */
    abstract public function getAll();

    /*
    * Get a single entity by given object id
    */
    abstract public function getById($oid);

    /*
    * Insert a new entity or update existing object
    */
    abstract public function save($entity);

    /*
    * Remove an entity
    */
    abstract public function delete($entity);
}