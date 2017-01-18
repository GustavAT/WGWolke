<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/Module.php");

class ModuleDao extends AbstractDao {
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getAll() {
        $sql = ModuleDao::getBaseSql();
        $records = Datenbarsch::getInstance()->fishQuery($sql);
        $modules = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($modules, Module::fromRecord($row));
            }
        }

        return $modules;
    }

    public function getById($oid) {
        $sql = ModuleDao::getBaseSql();
        $sql->where("m.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $module = null;

        if (mysqli_num_rows($records) > 0) {
            $module = Module::fromRecord(mysqli_fetch_assoc($records));
        }

        return $module;
    }

    public function save($module) {
        $sql = new Sql();
        if ($this->getById($module->getObjectId()) == null) {
            $sql->insertInto("module", ["oid", "date_created", "name", "price"]);
            Datenbarsch::getInstance()->fishQuery($sql, "sssd",
                $module->getObjectId(), $module->getDateCreated(),
                $module->getName(), $module->getPrice());            
        } else {
            $sql->update("module");
            $sql->set(["name", "price"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "sds",
                $module->getName(), $module->getPrice(), $module->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "Module") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("module");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("m.*");
        $sql->from("module m");
        return $sql;
    }
}