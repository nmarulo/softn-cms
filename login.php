<?php

/**
 * Controlador de la pagina de inicio de sesión.
 */
//Para evitar el bucle de redireccionamiento.
$formSignin = true;
require dirname(__FILE__) . '/sn-admin/sn-admin.php';

if (isset($_SESSION['username'])) {
    header("Location: " . $dataTable['option']['siteUrl'] . ADM . 'index.php');
    exit();
}

$auxLogin = [
    'user_login' => '',
    'user_email' => '',
    'user_pass' => '',
    'ruser_pass' => '',
];

if (isset($_POST['signin'])) {
    $userlogin = filter_input(INPUT_POST, 'user_login');
    $userpass = filter_input(INPUT_POST, 'user_pass');
    $user_rememberMe = filter_input(INPUT_POST, 'user_rememberMe', FILTER_VALIDATE_BOOLEAN);

    $auxLogin = [
        'user_login' => $userlogin,
        'user_pass' => $userpass,
    ];

    if ($userlogin && $userpass) {
        $auxUser = SN_Users::get_instance($userlogin);
        SN_Users::login($auxUser, $userpass, $user_rememberMe);
    } else {
        Messages::add('Debe completar todos los campos para continuar.', Messages::TYPE_W);
    }
} elseif (isset($_POST['signup'])) {
    $userlogin = filter_input(INPUT_POST, 'user_login');
    $useremail = filter_input(INPUT_POST, 'user_email');
    $userpass = filter_input(INPUT_POST, 'user_pass');
    $ruserpass = filter_input(INPUT_POST, 'ruser_pass');

    $auxLogin = [
        'user_login' => $userlogin,
        'user_email' => $useremail,
        'user_pass' => $userpass,
        'ruser_pass' => $ruserpass,
    ];
    if ($userlogin && $useremail && $userpass && $ruserpass) {
        SN_Users::checkSignup($auxLogin);
    } else {
        Messages::add('Debe completar todos los campos para continuar.', Messages::TYPE_W);
    }
}

require ABSPATH . ADM_CONT . 'login.php';
