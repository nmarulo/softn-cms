<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php $data['template']::getTitle(); ?></title>
        <link href="<?php $data['template']::getUrl(); ?>app/views/css/normalize.css" rel="stylesheet" type="text/css"/>
        <link href="<?php $data['template']::getUrl(); ?>app/vendor/twbs/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="<?php $data['template']::getUrl(); ?>app/vendor/fortawesome/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="<?php $data['template']::getUrl(); ?>app/views/css/style.css" rel="stylesheet" type="text/css"/>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="container-fluid"><!-- .container-fluid -->
            <div id="logo-SoftN">
                <br/>
                <img class="center-block" src="<?php echo $data['siteUrl']; ?>app/views/img/softn.png" alt="CMS - SoftN"/>
            </div>
            <div class="row clearfix"><!-- .row.clearfix -->
                <div id="install" class="panel panel-default center-block clearfix">
                    <div class="panel-body">
                        <h2>Proceso de instalación</h2>
                        <hr/>
                        <form class="form-horizontal" method="post">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Dirección web:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="installUrl" value="<?php echo $data['installUrl'] ?>" placeholder="http://localhost/">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Base de datos:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="installDb" value="<?php echo $data['installDb'] ?>" placeholder="softn_cms">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Usuario BD:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="installUser" value="<?php echo $data['installUser'] ?>" placeholder="root">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Contraseña BD:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="installPass" value="<?php echo $data['installPass'] ?>" placeholder="root">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Host:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="installHost" value="<?php echo $data['installHost'] ?>" placeholder="localhost">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Prefijo:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="installPrefix" value="<?php echo $data['installPrefix'] ?>" placeholder="sn_">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Charset:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="installCharset" value="<?php echo $data['installCharset'] ?>" placeholder="utf8">
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit" name="step" value="2">Siguiente</button>
                        </form>
                    </div>
                </div>
            </div><!-- .row.clearfix -->
        </div><!-- .container-fluid -->
        <script src="<?php $data['template']::getUrl(); ?>app/views/js/jquery-1.12.0.js" type="text/javascript"></script>
        <script src="<?php $data['template']::getUrl(); ?>app/vendor/twbs/bootstrap/dist/js/bootstrap.js" type="text/javascript"></script>
        <script src="<?php $data['template']::getUrl(); ?>app/vendor/tinymce/tinymce/tinymce.js" type="text/javascript"></script>
        <script src="<?php $data['template']::getUrl(); ?>app/views/js/script.js" type="text/javascript"></script>
    </body>
</html>
