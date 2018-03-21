<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SoftN - CMS</title>
    <link href="{{ asset('css/normalize.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css"/>
    <script type="application/javascript">
        //var globalURL = '{{$globalUrl}}';
    </script>
</head>
<body>
    <header>
        {{ include('includes.navbar') }}
    </header>
    <main id="main-container" class="main-container">
        <div class="container-fluid">
            #block(content)
        </div>
    </main>
    <footer class="main-container container-fluid">
        <div class="clearfix">
            <p class="pull-left">SoftN CMS</p>
            <p class="pull-right">v0.5</p>
        </div>
    </footer>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/common.js') }}" type="text/javascript"></script>
    #block(scripts)
</body>
</html>
