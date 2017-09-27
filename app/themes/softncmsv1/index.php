<?php

use SoftnCMS\classes\constants\OptionConstants;
use SoftnCMS\controllers\ViewController;
use SoftnCMS\models\managers\OptionsManager;

ViewController::registerStyleRoute('app/resources/css/normalize');
ViewController::registerStyleRoute('app/vendor/twbs/bootstrap/dist/css/bootstrap');
//TODO: crear ruta para el tema.
ViewController::registerStyleRoute('app/themes/softncmsv1/resources/css/style');
ViewController::registerScriptRoute('app/resources/js/jquery-3.2.1');
ViewController::registerScriptRoute('app/vendor/twbs/bootstrap/dist/js/bootstrap');
$optionsManager = new OptionsManager();
$siteTitle      = $optionsManager->searchByName(OptionConstants::SITE_TITLE)
                                 ->getOptionValue();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $siteTitle; ?></title>
        <!--        <link rel="icon" href="img/logo_32x32.png" sizes="32x32">-->
        <?php ViewController::includeStyles(); ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container">
            <div id="container">
                <?php ViewController::header(); ?>
                <div class="row clearfix">
                    <div class="col-sm-8">
                        <?php ViewController::content(); ?>
                    </div>
                    <div class="col-sm-4">
                        <?php ViewController::sidebar(); ?>
                    </div>
                </div>
                <?php ViewController::footer(); ?>
            </div>
        </div>
        <?php ViewController::includeScripts(); ?>
    </body>
</html>
