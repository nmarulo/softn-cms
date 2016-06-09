<?php

/**
 * Controlador de la pagina de configuraciones del panel de administración.
 */
require 'sn-admin.php';
SN_Users::checkRol('admin', true);
/*
 * Recoge los datos de las opciones principal de la aplicación. 
 * Para mostrar los datos en sus campos correspontiendes del modelo vista.
 */
$option = array(
    'optionTitle' => '',
    'optionDescription' => '',
    'optionEmailAdmin' => '',
    'optionPaged' => '',
    'optionSiteUrl' => '',
    'optionTheme' => '',
);

/*
 * Guarda los datos de las configuraciones guardas en la base de datos.
 * Cada indice corresponde al nombre unico en la base de datos.
 */
$arg = [];

if ($dataTable['option']['dataList']) {
    foreach ($dataTable['option']['dataList'] as $op) {
        $arg[$op['option_name']] = $op['option_value'];
    }
}

$option = array_merge($option, $arg);

if (filter_input(INPUT_POST, 'update')) {
    $error = 0;
    /**
     * PENDIENTE: Obtener los input de forma automatica, es decir, sin tener
     * que saber cual es el identificador del input, sin tener que escribirlos uno a uno
     * como esta puesto en las siguentes lineas.
     */
    $option['optionTitle'] = filter_input(INPUT_POST, 'optionTitle');
    $option['optionDescription'] = filter_input(INPUT_POST, 'optionDescription');
    $option['optionEmailAdmin'] = filter_input(INPUT_POST, 'optionEmailAdmin');
    $option['optionPaged'] = filter_input(INPUT_POST, 'optionPaged');
    $option['optionSiteUrl'] = filter_input(INPUT_POST, 'optionSiteUrl');
    $option['optionTheme'] = filter_input(INPUT_POST, 'optionTheme');

    $keys = array_keys($option);

    foreach ($keys as $option_name) {
        $auxOption = SN_Options::get_instance($option_name);

        $arg = [
            'option_name' => $option_name,
            'option_value' => $option[$option_name]
        ];

        /*
         * Si el identificador de la opcion existe se actualiza,
         * de lo contrario se crea.
         */
        if ($auxOption) {
            $arg['ID'] = $auxOption->getID();
            $auxOption = new SN_Options($arg);
            if (!$auxOption->update()) {
                ++$error;
            }
        } else {
            $auxOption = new SN_Options($arg);
            if (!$auxOption->insert()) {
                ++$error;
            }
        }
    }

    if ($error) {
        Messages::add('Algunos cambios no se guardaron correctamente.', Messages::TYPE_E);
    } else {
        Messages::add('Cambios guardados.', Messages::TYPE_S);
    }

    redirect('options', ADM);
}

$option['optionTheme'] = getThemes($dataTable['option']['theme']);

require ABSPATH . ADM_CONT . 'options.php';
