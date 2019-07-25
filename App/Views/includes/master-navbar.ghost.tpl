<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Principal</li>
            <li>
                <a id="link-dashboard" href="{{ url('/dashboard') }}">
                    <i class="fa fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a id="link-dashboard-users" href="{{ url('/dashboard/users') }}">
                    <i class="fa fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li>
                <a id="link-dashboard-settings" href="{{ url('/dashboard/settings') }}">
                    <i class="fa fa-cogs"></i>
                    <span>Configuración</span>
                </a>
            </li>
            <li class="header">Administración</li>
            <li class="treeview">
                <a id="link-dashboard-settings-href" href="#">
                    <i class="fa fa-cogs"></i>
                    <span>Configuración</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a id="link-dashboard-setting-gravatar" href="{{ url('/dashboard/settings/gravatar') }}">
                            <i class="fa fa-cog"></i>
                            <span>GrAvatar</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a id="link-dashboard-users-href" href="#">
                    <i class="fa fa-users-cog"></i>
                    <span>Usuarios</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="active">
                        <a id="link-dashboard-users-profiles" href="{{ url('/dashboard/users/profiles') }}">
                            <i class="fa fa-user-circle"></i>
                            <span>Perfiles</span>
                        </a>
                    </li>
                    <li>
                        <a id="link-dashboard-users-permissions" href="{{ url('/dashboard/users/permissions') }}">
                            <i class="fa fa-key"></i>
                            <span>Permisos</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>
