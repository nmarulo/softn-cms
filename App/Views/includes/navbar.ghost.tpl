<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-fixed-top" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <button id="btn-navbar-menu-toggle" class="navbar-brand navbar-button" type="button">
                <span class="glyphicon glyphicon-menu-hamburger"></span>
            </button>
            <a class="navbar-brand" href="#">SoftN CMS</a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-fixed-top">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
                <li><a href="#">Link</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-sidebar">
                <li>
                    <a href="{{ url('/dashboard/users') }}">
                        <span class="glyphicon glyphicon-triangle-right"></span>
                        Usuarios
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-triangle-right"></span>
                        Dropdown <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="#">
                                <span class="glyphicon glyphicon-triangle-right"></span>
                                Action
                            </a></li>
                        <li><a href="#">
                                <span class="glyphicon glyphicon-triangle-right"></span>
                                Another action
                            </a></li>
                        <li><a href="#">
                                <span class="glyphicon glyphicon-triangle-right"></span>
                                Something else here
                            </a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">
                        <span class="glyphicon glyphicon-triangle-right"></span>
                        Action 3
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="{{ url('/logout') }}">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
