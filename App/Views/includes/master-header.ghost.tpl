<header class="main-header">
    <a href="#" class="logo">
        <span class="logo-mini">SN</span>
        <span class="logo-lg"><b>SoftN</b> CMS</span>
    </a>
    <nav class="navbar navbar-static-top">
        <a id="link-navbar-sidebar-toggle" href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fa fa-bars"></i>
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a id="link-navbar-user-menu" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('images/default-50x50.gif') }}" class="user-image" alt="User Image">
                        <span class="hidden-xs">
                            {{\App\Facades\SessionFacade::getUser()->userLogin}}
                            <i class="fa fa-caret-down"></i>
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ asset('images/default-50x50.gif') }}" class="img-circle" alt="User Image">
                            <p>
                                {{\App\Facades\SessionFacade::getUser()->userName}}
                                <small>{{\App\Facades\SessionFacade::getUser()->userRegistered}}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a id="link-navbar-profile" href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a id="link-navbar-logout" href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
