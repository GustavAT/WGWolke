<?php
require_once("../dao/DaoFactory.php");

$userDao = DaoFactory::createUserDao();
$record = mysqli_fetch_assoc($userDao->getAll());
$user = User::fromRecord($record);
echo $user->toString();
