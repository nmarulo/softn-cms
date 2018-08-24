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
    <main class="container-fluid">
        #block(content)
    </main>
    <footer></footer>
    {{ include('includes.messages') }}
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/common.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/messages.js') }}" type="text/javascript"></script>
    #block(scripts)
</body>
</html>
