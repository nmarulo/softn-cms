<?php
use SoftnCMS\controllers\ViewController;

ViewController::registerScript('jquery-3.2.1');
ViewController::registerScript('common');
ViewController::registerScriptRoute('app/vendor/twbs/bootstrap/dist/js/bootstrap');
ViewController::registerStyle('normalize');
ViewController::registerStyleRoute('app/vendor/twbs/bootstrap/dist/css/bootstrap');
ViewController::registerStyleRoute('app/vendor/fortawesome/font-awesome/css/font-awesome.min');
ViewController::registerStyle('style');
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo __('Proceso de instalación'); ?></title>
        <?php ViewController::includeStyles(); ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container-fluid">
            <?php ViewController::content(); ?>
        </div>
        <?php
        ViewController::singleViewByDirectory('messages');
        ViewController::includeScripts();
        ?>
    </body>
</html>
