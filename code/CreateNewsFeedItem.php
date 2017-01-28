<?php

require_once("../dao/DaoFactory.php");
require_once("Validator.php");
require_once("Util.php");
require_once("SessionHelper.php");

$title = Util::parsePost("title");
$message = Util::parsePost("message");
$community_oid = Util::parsePost("community_oid");
$user_oid = Util::parsePost("user_oid");


$valid = Validator::isValidField($title)
    && Validator::smallerThan($message, Validator::$email_length);

if ($valid) {
    $item = new NewsFeedItem(null, null, $title, $message, 
        date("Y-m-d H:i:s + 7 days"), $community_oid ,$user_oid);
    DaoFactory::createNewsFeedDao()->save($item);

    echo '{"result": "", "success": true}';
} else {
    echo '{"result": "", "success": false"}';
}