<?php

/**
 * Controlador de la pagina de nuevos usuarios.
 */
require 'sn-admin.php';
SN_Users::checkRol('user', true);

/**
 * Fecha de registro del usuario.
 */
$date = getDate_now();
/*
 * Recoge los datos del usuario. Para mostrar los datos en
 * sus campos correspontiendes del modelo vista.
 */
$user = array(
    'user_login' => '',
    'user_name' => '',
    'user_email' => '',
    'user_rol' => '',
    'user_url' => ''
);
//Cambia el encabezado y los botones de publicar y actualizar
$action_edit = false;

if ((filter_input(INPUT_GET, 'action') == 'edit' && filter_input(INPUT_GET, 'id')) || filter_input(INPUT_POST, 'update')) {
    $action_edit = true;
    $auxUser = SN_Users::get_instance(filter_input(INPUT_GET, 'id'));

    if ($auxUser) {
        //Compruebo que el ID corresponde al usuario que ha iniciado sesión.
        if ($auxUser->getID() == SN_Users::getSession()->getID() || SN_Users::checkRol('admin', true)) {
            if (filter_input(INPUT_POST, 'update')) {
                $login = filter_input(INPUT_POST, 'user_login');
                $email = filter_input(INPUT_POST, 'user_email');
                $name = filter_input(INPUT_POST, 'user_name');
                $pass = filter_input(INPUT_POST, 'user_pass');
                $pass2 = filter_input(INPUT_POST, 'user_pass2');
                $rol = filter_input(INPUT_POST, 'user_rol');
                $url = filter_input(INPUT_POST, 'user_url');

                if ($pass == $pass2) {
                    $arg = array(
                        'ID' => $auxUser->getID(),
                        'user_login' => $login,
                        'user_name' => $name,
                        'user_email' => $email,
                        'user_pass' => $pass ? SN_Users::encrypt($pass) : $auxUser->getUser_pass(),
                        'user_rol' => SN_Users::checkRol() ? $rol : $auxUser->getUser_rol(),
                        'user_registred' => $auxUser->getUser_registred(),
                        'user_url' => $url
                    );

                    $auxUser = new SN_Users($arg);
                    if ($auxUser->update()) {
                        Messages::add('Usuario actualizado.', Messages::TYPE_S);
                        /*
                         * Si se redirecciona a lista de usuario y el usuario 
                         * no tiene permisos para ver esta lista se mostrara, ademas 
                         * del mensaje de actualizado, el mensaje "No tiene permisos ..."
                         * por eso se ha comentado
                         */
//                    redirect('users', ADM);
                    } else {
                        Messages::add('Error al actualizar usuario.', Messages::TYPE_E);
                    }
                } else {
                    Messages::add('Las contraseñas deben ser iguales.', Messages::TYPE_E);
                }
            }
            $user = [
                'ID' => $auxUser->getID(),
                'user_login' => $auxUser->getUser_login(),
                'user_name' => $auxUser->getUser_name(),
                'user_email' => $auxUser->getUser_email(),
                'user_rol' => $auxUser->getUser_rol(),
                'user_url' => $auxUser->getUser_url()
            ];
        }
    } else {
        Messages::add('El usuario no existe.', Messages::TYPE_E);
    }
} elseif (filter_input(INPUT_POST, 'publish')) {
    if (SN_Users::checkRol('admin', true)) {
        $login = filter_input(INPUT_POST, 'user_login');
        $email = filter_input(INPUT_POST, 'user_email');
        $name = filter_input(INPUT_POST, 'user_name');
        $pass = filter_input(INPUT_POST, 'user_pass');
        $pass2 = filter_input(INPUT_POST, 'user_pass2');
        $rol = filter_input(INPUT_POST, 'user_rol');
        $url = filter_input(INPUT_POST, 'user_url');

        if ($pass == $pass2) {
            $arg = [
                'user_login' => $login,
                'user_name' => $name,
                'user_email' => $email,
                'user_pass' => $pass,
                'user_rol' => $rol,
                'user_registred' => $date,
                'user_url' => $url
            ];

            $auxUser = new SN_Users($arg);
            if ($auxUser->insert()) {
                Messages::add('Usuario registrado.', Messages::TYPE_S);
                redirect('users', ADM);
            } else {
                Messages::add('Error al registrar usuario.', Messages::TYPE_E);
            }
        } else {
            Messages::add('Las contraseñas deben ser iguales.', Messages::TYPE_E);
        }
    }
}

require ABSPATH . ADM_CONT . 'user-new.php';
