<?php
/**
 * Fichero de configuración: Menu lateral del panel de administración.
 */
use SoftnCMS\models\admin\template\Template;

return [
    [
        'title' => '<i class="fa fa-tachometer" aria-hidden="true"></i> Información',
        'id'    => 'admin',
        'href'  => Template::getUrlAdmin('', FALSE),
        'sub'   => FALSE,
    ],
    [
        'title' => '<span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Entradas',
        'id'    => 'post',
        'href'  => Template::getUrlPost('', FALSE),
        'sub'   => [
            [
                'title' => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nueva entrada',
                'href'  => Template::getUrlPostInsert('', FALSE),
                'sub'   => FALSE,
            ],
            [
                'title' => '<span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span> Categorías',
                'href'  => Template::getUrlCategory('', FALSE),
                'sub'   => FALSE,
            ],
            [
                'title' => '<span class="glyphicon glyphicon-tags" aria-hidden="true"></span> Etiquetas',
                'href'  => Template::getUrlTerm('', FALSE),
                'sub'   => FALSE,
            ],
        ],
    ],
    //    ['title' => '<span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Paginas',
    //        'id' => 'page',
    //        'href' => 'admin/page',
    //        'sub' => [
    //            ['title' => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nueva pagina',
    //                'href' => 'admin/page/insert',
    //                'sub' => false,
    //            ],
    //        ],
    //    ],
    //    ['title' => '<span class="glyphicon glyphicon-home" aria-hidden="true"></span> Barra lateral',
    //        'id' => 'sidebar',
    //        'href' => 'admin/sidebar',
    //        'sub' => false,
    //    ],
    //    ['title' => '<span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span> Menus',
    //        'id' => 'menu',
    //        'href' => 'admin/menu',
    //        'sub' => false,
    //    ],
    [
        'title' => '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Comentarios',
        'id'    => 'comment',
        'href'  => Template::getUrlComment('', FALSE),
        'sub'   => FALSE,
    ],
    [
        'title' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Usuarios',
        'id'    => 'user',
        'href'  => Template::getUrlUser('', FALSE),
        'sub'   => [
            [
                'title' => '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Nuevo usuario',
                'href'  => Template::getUrlUserInsert('', FALSE),
                'sub'   => FALSE,
            ],
        ],
    ],
    [
        'title' => '<span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Configuración',
        'id'    => 'option',
        'href'  => Template::getUrlOption('', FALSE),
        'sub'   => FALSE,
    ],
];
