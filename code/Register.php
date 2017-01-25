<?php

require_once("../dao/DaoFactory.php");

$community_name = isset($_POST["community_name"]) ? $_POST["community_name"] : "";
$community_description = isset($_POST["community_description"]) ? $_POST["community_description"] : "";

$comm_name_length = strlen($community_name);
$comm_desc_length = strlen($community_description);

if ($comm_name_length > 0 && $comm_name_length <= 50
    && $comm_desc_length <= 500) {
        // valid community
        $commDao = DaoFactory::createCommunityDao();
        $community = new Community(null, null, $community_name, $community_description);
        $commDao->save($community);
        echo "true";
}