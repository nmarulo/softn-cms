<?php

/**
 * Controlador del menu del panel de administración.
 */
/**
 * Lista de opciones del panel de adminitración.
 * @global array $GLOBALS['admin_menu']
 * @name $admin_menu 
 */
$GLOBALS['admin_menu'] = [
    ['title' => '<span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span> Información',
        'href' => 'index.php',
        'sub' => false,
        'minRol' => getRol('user'),
    ],
    ['title' => '<span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Entradas',
        'href' => 'posts.php',
        'sub' => [
            ['title' => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nueva entrada',
                'href' => 'post-new.php',
                'sub' => false,
                'minRol' => getRol('editor'),
            ],
            ['title' => '<span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> Categorías',
                'href' => 'categories.php',
                'sub' => false,
                'minRol' => getRol('author'),
            ],
            ['title' => '<span class="glyphicon glyphicon-tags" aria-hidden="true"></span> Etiquetas',
                'href' => 'terms.php',
                'sub' => false,
                'minRol' => getRol('author'),
            ],
        ],
        'minRol' => getRol('editor'),
    ],
    ['title' => '<span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Paginas',
        'href' => 'pages.php',
        'sub' => [
            ['title' => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nueva pagina',
                'href' => 'page-new.php',
                'sub' => false,
                'minRol' => getRol('author'),
            ],
        ],
        'minRol' => getRol('author'),
    ],
    ['title' => '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Barra lateral',
        'href' => 'sidebars.php',
        'sub' => false,
        'minRol' => getRol('author'),
    ],
    ['title' => '<span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Menus',
        'href' => 'menus.php',
        'sub' => false,
        'minRol' => getRol('author'),
    ],
    ['title' => '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Comentarios',
        'href' => 'comments.php',
        'sub' => false,
        'minRol' => getRol('editor'),
    ],
    ['title' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Usuarios',
        'href' => 'users.php',
        'sub' => [
            ['title' => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nuevo usuario',
                'href' => 'user-new.php',
                'sub' => false,
                'minRol' => getRol('admin'),
            ],
        ],
        'minRol' => getRol('admin'),
    ],
    ['title' => '<span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Configuración',
        'href' => 'options.php',
        'sub' => false,
        'minRol' => getRol('admin'),
    ],
];

/**
 * Metodo que muestra una lista con las opciones del panel de administración.
 * @global array $admin_menu Lista de opciones.
 */
function showMenu() {
    global $admin_menu;

    $user_rol = SN_Users::getSession()->getUser_rol();

    $str = '<ul class="sn-menu">';
    foreach ($admin_menu as $menu) {
        if ($user_rol >= $menu['minRol']) {
            if (is_array($menu['sub'])) {
                $id = explode('.', $menu['href'])[0];
                $str .= "<li><a data-toggle='collapse' href='#$id'>$menu[title] <span class='pull-right glyphicon glyphicon-chevron-down'></span></a>";
                $str .= "<ul id='$id' class='sn-submenu collapse'>";
                $str .= "<li><a href='$menu[href]'>$menu[title]</a></li>";
                foreach ($menu['sub'] as $sub) {
                    if ($user_rol >= $sub['minRol']) {
                        $str .= "<li><a href='$sub[href]'>$sub[title]</a></li>";
                    }
                }
                $str .= '</ul></li>';
            } else {
                $str .= "<li><a href='$menu[href]'>$menu[title]</a></li>";
            }
        }
    }    
    $str .= '</ul>';

    echo $str;
}

require ABSPATH . ADM_CONT . 'admin-menu.php';
