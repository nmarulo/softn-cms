<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Softn CMS | Dashboard</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href="{{ asset('css/normalize.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/all.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/daterangepicker.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/icheck/blue.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/adminlte/AdminLTE.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/adminlte/skin-blue.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="application/javascript">
            var globalURL = '{{ url('/') }}';
        </script>
    </head>
    <body class="hold-transition skin-blue sidebar-mini fixed">
        <div class="wrapper">
            {{ include('includes.master-header') }}
            {{ include('includes.master-navbar') }}
            <div class="content-wrapper">
                <section class="content-header">
                    #block(header)
                    <ol class="breadcrumb">
                        <li>
                            <a id="link-breadcrumb-dashboard" href="{{ url('/dashboard') }}">
                                <i class="fa fa-tachometer-alt"></i>
                                Inicio
                            </a>
                        </li>
                        #block(breadcrumb)
                    </ol>
                </section>
                <section class="content">
                    #block(content)
                </section>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>SoftN CMS</b> - Version 0.5
                </div>
                <div>
                    Diseño base <strong>adminlte</strong>.
                </div>
            </footer>
        </div>
        {{ include('includes.messages') }}
        <script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/icheck.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/select2/select2.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/adminlte.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/common.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/data-list.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/modal-dialog.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/messages.js') }}" type="text/javascript"></script>
        #block(scripts)
    </body>
</html>
