<?php
require_once("../code/PrintHelper.php");
require_once("../code/SessionHelper.php");
require_once("../code/Util.php");

SessionHelper::doActivity();
$user_oid = SessionHelper::getCurrentUserOid();
if ($user_oid === null) {
    Util::redirect("index.php");
}



?>

<!DOCTYPE html>
<html>
<head>
    <?php PrintHelper::includeCSS(); ?>
    <title> Dashboard </title>
</head>

<body>
    
    <div class="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <img class="navbar-brand" src="../images/wg_wolke.png" >
                <a class="navbar-brand" href="Dashboard.php">Dashboard</a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings </a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="login.html"><i class="fa fa-sign-out fa-fw"></i> Logout </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav in" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input class="form-control" placeholder="Google it!" type="text">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </li>
                        <li>
                            <p class="text-muted lead" style="text-align: center; margin: 0 !important;"> Newsfeed </p>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h3> Modules </h3>
                </div>
            </div>
        </div>

    </div>

    <?php PrintHelper::includeJS(); ?>

    <script type="text/javascript">

        $(document).ready(function() {
            
        });

    </script>
</body>
</html>