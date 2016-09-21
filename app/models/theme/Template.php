<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\models\theme;

use SoftnCMS\models\admin\Option;
use SoftnCMS\models\Login;
use SoftnCMS\models\admin\User;

/**
 * Description of Template
 *
 * @author Nicolás Marulanda P.
 */
class Template {

    /** @var string Nombre del tema actual */
    private $nameTheme;

    /** @var string Url de la aplicación */
    private $urlSite;

    /** @var string Titulo de la aplicación. */
    private $title;

    /** @var string Titulo de la pagina. */
    private $pageTitle;

    /** @var array Lista con los datos a incluir en el HEAD de la plantilla. */
    private $head;

    /** @var array Lista con los datos a incluir al final de la plantilla. */
    private $footer;

    /**
     * Constructor.
     * @global string $urlSite
     */
    public function __construct() {
        global $urlSite;

        $this->head = [];
        $this->footer = [];
        $this->nameTheme = Option::selectByName('optionTheme')->getOptionValue();
        $this->urlSite = $urlSite;
        $this->title = Option::selectByName('optionTitle')->getOptionValue();
        $this->pageTitle = $this->title;
    }

    public function getSessionUserName($isEcho = \TRUE) {
        $user = User::selectByID(Login::getSession());

        if ($user === \FALSE) {
            return \FALSE;
        }

        if (!$isEcho) {
            return $user->getUserName();
        }

        echo $user->getUserName();
    }

    public function getSessionUserID($isEcho = \TRUE) {
        if (!$isEcho) {
            return Login::getSession();
        }

        echo Login::getSession();
    }

    /**
     * Metodo que obtiene los estilos css y otras etiquetas a incluir 
     * en el "HEAD" de la plantilla.
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     * @return array Lista con los datos a incluir en el HEAD de la plantilla.
     */
    public function getHead($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->head;
        }

        $echo = "";

        foreach ($this->head as $value) {
            $echo .= $value;
        }

        echo $echo;
    }

    /**
     * Metodo que obtiene los script js y otras etiquetas a incluir 
     * al final de la pagina.
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     * @return array Lista con los datos a incluir al final de la plantilla.
     */
    public function getFooter($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->footer;
        }

        $echo = "";

        foreach ($this->footer as $value) {
            $echo .= $value;
        }

        echo $echo;
    }

    /**
     * Metodo que obtiene el titulo de la aplicación.
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     * @return string
     */
    public function getTitle($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->title;
        }

        echo $this->title;
    }

    /**
     * Metodo que obtiene el titulo de la pagina.
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     * @return string
     */
    public function getPageTitle($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->pageTitle;
        }

        echo $this->pageTitle;
    }

    /**
     * Metodo que obtiene la url de la plantilla.
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     * @return string
     */
    public function getUrlTheme($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->urlSite . \APP_THEMES . $this->nameTheme . '/';
        }

        echo $this->urlSite . \APP_THEMES . $this->nameTheme . '/';
    }

    /**
     * Metodo que obtiene los elementos del menu.
     * @param string $name Nombre del menu.
     * @return array
     */
    public function getMenu($name) {
        return [];
    }

    /**
     * Metodo comprueba si existe una sesión activa.
     * @return bool Si es TRUE, existe una sesión activa.
     */
    public function isLogin() {
        return Login::checkSession();
    }

    /**
     * Metodo que obtiene la url de "inicio de sesión".
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     * @return string
     */
    public function getUrlLogin($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->urlSite . 'login/';
        }

        echo $this->urlSite . 'login/';
    }

    /**
     * Metodo que obtiene la url del "panel de administración".
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     * @return string
     */
    public function getUrlAdmin($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->urlSite . 'admin/';
        }

        echo $this->urlSite . 'admin/';
    }

    /**
     * Metodo que obtiene la url para editar el usuario de la sesión actual.
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     */
    public function getUrlProfile($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->getUrlAdmin(\FALSE) . 'user/update/' . Login::getSession();
        }

        echo $this->getUrlAdmin(\FALSE) . 'user/update/' . Login::getSession();
    }

    /**
     * Metodo que obtiene la url de "cierre de sesión".
     * @param bool $isEcho [Opcional] Si es TRUE, se imprime el contenido.
     */
    public function getUrlLogout($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->urlSite . 'logout/';
        }

        echo $this->urlSite . 'logout/';
    }

    public function getUrlSite($isEcho = \TRUE) {
        if (!$isEcho) {

            return $this->urlSite;
        }

        echo $this->urlSite;
    }

    public function concatTitle($value) {
        $this->pageTitle .= " | $value";
    }

}
