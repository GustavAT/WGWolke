<?php
require_once("AbstractDao.php");
require_once("../code/Sql.php");
require_once("../code/Datenbarsch.php");
require_once("../model/NewsFeedItem.php");

class NewsFeedDao extends AbstractDao{
    
    private static $instance = null;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __construct() {}

    // CRUD Operations

    public function getByCommunity($community_oid) {
        $sql = NewsFeedDao::getBaseSql();
        $sql->where("nf.community_oid = ?");
        
        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
        $news_feed_items = [];

        if (mysqli_num_rows($records) > 0) {
            while ($row = $records->fetch_assoc()) {
                array_push($news_feed_items, NewsFeedItem::fromRecord($row));
            }
        }

        return $news_feed_items;
    }

    public function getById($oid) {
        $sql = NewsFeedDao::getBaseSql();
        $sql->where("nf.oid = ?");

        $records = Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
        $news_feed_item = null;

        if (mysqli_num_rows($records) > 0) {
            $news_feed_item = NewsFeedItem::fromRecord(mysqli_fetch_assoc($records));
        }

        return $news_feed_item;
    }

    public function save($news_feed_item) {        
        $sql = new Sql();
        if ($this->getById($news_feed_item->getObjectId()) == null) {
            $sql->insertInto("news_feed_item", ["oid", "date_created", "title", "message", "expiration_date", "community_oid", "user_oid"]);
            Datenbarsch::getInstance()->fishQuery($sql, "sssssss",
                $news_feed_item->getObjectId(), $news_feed_item->getDateCreated(), $news_feed_item->getTitle(), $news_feed_item->getMessage(),
                $news_feed_item->getExpirationDate(), $news_feed_item->getCommunityOid(), $news_feed_item->getUserOid());            
        } else {
            $sql->update("news_feed_item");
            $sql->set(["title", "message", "expiration_date", "community_oid", "user_oid"]);
            $sql->where("oid = ?");
            Datenbarsch::getInstance()->fishQuery($sql, "ssssss",
                $news_feed_item->getTitle(), $news_feed_item->getMessage(), $news_feed_item->getExpirationDate(),
                $news_feed_item->getCommunityOid(), $news_feed_item->getUserOid(), $news_feed_item->getObjectId());
        }
    }

    public function delete($oid) {
        $oid = is_a($oid, "NewsFeedItem") ? $oid->getObjectId() : $oid;
        $sql = new Sql();
        $sql->delete();
        $sql->from("news_feed_item");
        $sql->where("oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $oid);
    }

    public function deleteByCommunityOid($community_oid) {
        $sql = new Sql();
        $sql->delete();
        $sql->from("news_feed_item");
        $sql->where("community_oid = ?");
        Datenbarsch::getInstance()->fishQuery($sql, "s", $community_oid);
    }

    private static function getBaseSql() {
        $sql = new Sql();
        $sql->select("nf.*");
        $sql->from("news_feed_item nf");
        return $sql;
    }
}