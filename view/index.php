<?php
require_once("../dao/DaoFactory.php");
require_once("../model/DishTag.php");

$userDao = DaoFactory::createUserDao();
$commDao = DaoFactory::createCommunityDao();
$moduleDao = DaoFactory::createModuleDao();
$newsFeedDao = DaoFactory::createNewsFeedDao();
$toDoDao = DaoFactory::createToDoItemDao();
$financeDao = DaoFactory::createFinanceDao();
$dishTagDao = DaoFactory::createDishTagDao();
$dishItemDao = DaoFactory::createDishItemDao();

$wg = $commDao->getById("FAD6D8D1-6609-4509-8840-5ECC1C9F4B2B");
$users = $userDao->getByCommunity($wg->getObjectId());
$user_oids = [];
foreach ($users as $value) {
    echo $value->toString() . "<br />";
    array_push($user_oids, $value->getObjectId());
}

$tags = $dishTagDao->getByCommunity($wg->getObjectId());
$tag_ids = [];
foreach ($tags as $tag) {
    array_push($tag_ids, $tag->getObjectId());
}

$essen = $dishItemDao->getByCommunity($wg->getObjectId());
$wiener = $essen[1];
$bache = $user_oids[1];
$wann = Util::now();

$dishItemDao->linkDish($wiener->getObjectId(), $wann, $bache);










