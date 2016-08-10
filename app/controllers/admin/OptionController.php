<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace SoftnCMS\controllers\admin;

use SoftnCMS\models\admin\Options;
use SoftnCMS\models\admin\OptionUpdate;

/**
 * Description of OptionController
 *
 * @author Nicolás Marulanda P.
 */
class OptionController {

    public function index() {
        return ['data' => $this->dataIndex()];
    }

    private function dataIndex() {
        $this->dataUpdate();
        $options = Options::selectAll();
        return $options->getOptions();
    }

    private function dataUpdate() {
        if (\filter_input(\INPUT_POST, 'update')) {
            $options = Options::selectAll();
            $dataInput = $this->getDataInput();
            $keys = \array_keys($dataInput);
            $count = \count($keys);
            $error = \FALSE;

            for ($i = 0; $i < $count && !$error; ++$i) {
                $optionName = $keys[$i];
                $optionValue = $dataInput[$optionName];
                $option = $options->getOption($optionName);
                $update = new OptionUpdate($option, $optionValue);
                $error = !$update->update();
            }
        }
    }

    private function getDataInput() {
        return [
            'optionTitle' => \filter_input(\INPUT_POST, 'optionTitle'),
            'optionDescription' => \filter_input(\INPUT_POST, 'optionDescription'),
            'optionEmailAdmin' => \filter_input(\INPUT_POST, 'optionEmailAdmin'),
            'optionSiteUrl' => \filter_input(\INPUT_POST, 'optionSiteUrl'),
            'optionPaged' => \filter_input(\INPUT_POST, 'optionPaged'),
            'optionTheme' => \filter_input(\INPUT_POST, 'optionTheme'),
            'optionMenu' => \filter_input(\INPUT_POST, 'optionMenu'),
        ];
    }

}
