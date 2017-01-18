<?php
require_once("../dao/DaoFactory.php");

$userDao = DaoFactory::createUserDao();
$commDao = DaoFactory::createCommunityDao();
$moduleDao = DaoFactory::createModuleDao();

// $wg = new Community(null, null, "WG", "Bache's WG");
// $wg->addModules($moduleDao->getAll());
// $commDao->save($wg);

// $bache = new User(null, null, "christof.bachmann@gmail.com", md5("1234"), "Christof", "Bachmann", false, null, $wg->getObjectId(), true);
// $andi = new User(null, null, "andreas-tscheinig@gmx.at", md5("1234"), "Andreas", "Tscheinig", false, null, $wg->getObjectId(), false);
// $pete = new User(null, null, "peter.waysocher@gmail.com", md5("1234"), "Peter", "Waysocher", false, null, $wg->getObjectId(), false);

// $userDao->save($bache);
// $userDao->save($andi);
// $userDao->save($pete);

$wg = $commDao->getById("FAD6D8D1-6609-4509-8840-5ECC1C9F4B2B");
$users = $userDao->getByCommunity($wg->getObjectId());
foreach ($users as $value) {
    echo $value->toString() . "<br />";
}