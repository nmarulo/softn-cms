<?php

/**
 * Controlador de la pagina de inicio del panel de administración.
 */
require 'sn-admin.php';

$dataTable['index']['post'] = '<p>No hay publicaciones</p>';
$dataTable['index']['page'] = '<p>No hay paginas</p>';
$dataTable['index']['comment'] = '<p>No hay comentarios</p>';
$dataTable['post']['count'] = count($dataTable['post']['dataList']);
$dataTable['page']['count'] = count($dataTable['page']['dataList']);
$dataTable['comment']['count'] = count($dataTable['comment']['dataList']);
$dataTable['category']['count'] = count($dataTable['category']['dataList']);
$dataTable['user']['count'] = count($dataTable['user']['dataList']);

$leng = 0;
$lengTitle = 20;
/*
 * Si no hay post se muestra un mensaje, de lo contrario, se muestra
 * un lista con las ultimas publicaciones y comentarios.
 */
if ($dataTable['post']['count']) {
    $leng = $dataTable['post']['count'];
    $forEnd = $leng > 5 ? 5 : $leng;

    $dataTable['index']['post'] = '<ul class="list-group">';
    for ($i = 0; $i < $forEnd; ++$i) {
        $title = $dataTable['post']['dataList'][$i]['post_title'];

        if (isset($title{$lengTitle})) {
            $title = substr($title, 0, $lengTitle) . ' [...]';
        }

        $str = '<li class="list-group-item clearfix">';
        $str .= '<span class="pull-left">';
        $str .= $dataTable['post']['dataList'][$i]['post_date'];
        $str .= "</span><a href='" . siteUrl() . "?post=" . $dataTable['post']['dataList'][$i]['ID'] . "'>$title</a></li>";
        $dataTable['index']['post'] .= $str;
    }
    $dataTable['index']['post'] .= '</ul>';

    $leng = $dataTable['comment']['count'];
    $forEnd = $leng > 5 ? 5 : $leng;

    $dataTable['index']['comment'] = '<ul class="list-group">';
    for ($i = 0; $i < $forEnd; ++$i) {
        $post = SN_Posts::get_instance($dataTable['comment']['dataList'][$i]['post_ID']);
        $title = $post->getPost_title();

        if (isset($title{$lengTitle})) {
            $title = substr($title, 0, $lengTitle) . ' [...]';
        }

        $str = '<li class="list-group-item">';
        $str .= $dataTable['comment']['dataList'][$i]['comment_autor'] . ' dejo un comentario en ';
        $str .='<a href="' . siteUrl() . '?post=' . $post->getID() . '">' . $title . '</a>';
        $str .='</li>';
        $dataTable['index']['comment'] .= $str;
    }
    $dataTable['index']['comment'] .= '</ul>';
}

$github = simplexml_load_file('https://github.com/nmarulo/softn-cms/commits/master.atom');
$github = get_object_vars($github);
$lastUpdateGit = $github['updated'];

$leng = count($github['entry']);
$forEnd = $leng > 5 ? 5 : $leng;

$dataTable['index']['github'] = '<ul class="list-group">';
/*
 * Indices de $elements "id", "link"=>"@attributes"=>"href", "title",
 * "updated", "author"=>"name", "author"=>"uri", "content"
 */
for ($i = 0; $i < $forEnd; ++$i) {
    $element = get_object_vars($github['entry'][$i]);
    $title = $element['title'];
    $element['link'] = get_object_vars($element['link']);
    $element['author'] = get_object_vars($element['author']);

    if (isset($title{$lengTitle})) {
        $title = substr($title, 0, $lengTitle) . ' [...]';
    }

    $str = '<li class="list-group-item">';
    $str .= '<a href="' . $element['author']['uri'] . '" target="_blank"><span class="label label-success">' . $element['author']['name'] . '</span></a> ';
    $str .='<a href="' . $element['link']['@attributes']['href'] . '" target="_blank">' . $title . '</a>';
    $str .='</li>';
    $dataTable['index']['github'] .= $str;
}
$dataTable['index']['github'] .= '</ul>';

require ABSPATH . ADM_CONT . 'index.php';
