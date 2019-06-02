<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Softn CMS | Login</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link href="{{ asset('css/normalize.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/all.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/adminlte/AdminLTE.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/icheck/blue.css') }}" rel="stylesheet" type="text/css"/>
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
    <body class="hold-transition login-page">
        #block(content)
        {{ include('includes.messages') }}
        <script src="{{ asset('js/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/icheck.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/adminlte.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/common.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/messages.js') }}" type="text/javascript"></script>
        #block(scripts)
        <script>
            $(function () {
                //iCheck for checkbox and radio inputs
                $('input[type="checkbox"], input[type="radio"]').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue'
                })
            });
        </script>
    </body>
</html>
