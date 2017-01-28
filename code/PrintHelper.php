<?php

class PrintHelper {

    public static function includeCSS() { ?>
        <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="../vendor/sb-admin/css/sb-admin-2.css" rel="stylesheet">
        <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="../css/wg_wolke.css" rel="stylesheet" type="text/css">
    <?php }

    public static function includeJS() { ?>
        <script src="../vendor/jquery/jquery.min.js"></script>
        <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="../vendor/metisMenu/metisMenu.min.js"></script>
        <script src="../vendor/sb-admin/js/sb-admin-2.js"></script>
    <?php }

    public static function printModule($module) {
        $title = $module->getName();

        // calculate value
        $value = "Value";
        $subtext = "View Details";
        $icon = "";
        $module_color = "";

        switch($module->getType()) {
            case 1:
                $icon = "icon-member";
                $module_color = "primary";
                break;
            case 2:
                $icon = "icon-finances";
                $module_color = "green";
                break;
            case 3:
                $icon = "icon-menuplan";
                $module_color = "red";
                break;
            case 4:
                $icon = "icon-shopping";
                $module_color = "yellow";
                break;
        }

        ?>

        <div class="col-md-6">
            <div class="panel panel-<?php echo $module_color; ?>">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <div class="<?php echo $icon; ?> scaling-normal">
                            </div>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge"> <?php echo $value; ?> </div>
                            <div> <?php echo $title; ?> </div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left"><?php echo $subtext; ?></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

    <?php }

    public static function printNewsFeedItem($item) { ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center"> <?php echo $item->getTitle(); ?> </h4>
            </div>
            <div class="panel-body">                
                <p> <?php echo $item->getMessage(); ?> </p>
                <h6 class="text-right text-muted"> <?php echo $item->getDateCreated(); ?> </h6>
            </div>
        </div>

    <?php }
}