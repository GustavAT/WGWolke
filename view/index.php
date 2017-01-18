<?php
require_once("../dao/DaoFactory.php");

$userDao = DaoFactory::createUserDao();
$commDao = DaoFactory::createCommunityDao();
$moduleDao = DaoFactory::createModuleDao();

$wg = new Community(null, null, "WG", "Bache's WG");
$wg->addModules($moduleDao->getAll());
$commDao->save($wg);

$bache = new User(null, null, "christof.bachmann@gmail.com", md5("1234"), "Christof", "Bachmann", false, null, $wg->getObjectId(), true);
$andi = new User(null, null, "andreas-tscheinig@gmx.at", md5("1234"), "Andreas", "Tscheinig", false, null, $wg->getObjectId(), false);

$userDao->save($bache);
$userDao->save($andi);


// $users = $userDao->getByCommunity(Util::newEmptyGuid());
// $user = null;
// foreach ($users as $value) {
//     echo $value->toString() . "<br />";
//     $user = $value;
// }