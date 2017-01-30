<?php
require_once("../code/PrintHelper.php");
require_once("../code/Resources.php");
require_once("../code/FormGenerator.php");
require_once("../code/SessionHelper.php");
require_once("../code/Util.php");
require_once("../dao/DaoFactory.php");

SessionHelper::doActivity();
$user_oid = SessionHelper::getCurrentUserOid();
if ($user_oid === null) {
    SessionHelper::logOut();
    Util::redirect("index.php?error=0");
}

$user = DaoFactory::createUserDao()->getById($user_oid);
if ($user === null) {
    SessionHelper::logOut();
    Util::redirect("index.php?error=1");
}

$community = DaoFactory::createCommunityDao()->getByUserOid($user_oid);
if ($community === null) {
    SessionHelper::logOut();
    Util::redirect("index.php?error=2");
}

$modules = DaoFactory::createCommunityDao()->getModules($community->getObjectId());
?>

<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <title> <?php Resources::$title_dashboard; ?> </title>
    <script type="text/javascript">
        window.phpVars = [];        
        <?php
            echo "phpVars.userOid = '" . $user_oid . "';";
            echo "phpVars.communityOid = '" . $community->getObjectId() . "';";
            echo "phpVars.unknownError = '" . Resources::$unknown_error . "';";
        ?>
    </script>
</head>

<body>
    
    <div class="wrapper">
        <?php PrintHelper::printNavBar(Resources::$title_dashboard, $user, $community); ?>
        <div id="page-wrapper">
            <br />
            <div class="row">
                    <?php
                        foreach($modules as $module) {
                            if ($module->getType() === 1 && !$user->isOwner()) continue;
                            PrintHelper::printModule($module);
                        }
                    ?>    
                </div>            
            </div>
        </div>
    </div>
    
    <?php PrintHelper::createBlackBoardDialog(); ?>
    <?php PrintHelper::includeJS(); ?>

    <script type="text/javascript">

        $(document).ready(function() {
            initNavBar();
        });

    </script>
</body>
</html>