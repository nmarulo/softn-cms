<?php
/**
 * HTML.php
 */

namespace SoftnCMS\util;

use SoftnCMS\rute\Router;

/**
 * Class HTML
 * @author Nicolás Marulanda P.
 */
class HTML {
    
    private static $INPUT_ID = 1;
    
    public static function inputRadio($name, $text, $value = 'on', $data = []) {
        self::inputTypeSelect('radio', $name, $text, $value, $data);
    }
    
    private static function inputTypeSelect($type, $name, $text, $value = 'on', $data = []) {
        $data['name']  = $name;
        $data['text']  = $text;
        $data['value'] = $value;
        $data['type']  = $type;
        $labelClass    = self::getAttribute(Arrays::get($data, 'labelClass'), 'class');
        
        if (!Arrays::keyExists($data, 'class')) {
            $data['class'] = '';
        }
        
        echo "<label $labelClass>";
        self::input($data);
        echo '</label>';
    }
    
    private static function getAttribute($value, $attributeName) {
        if (empty(trim($value))) {
            return "";
        }
        
        return "$attributeName='$value'";
    }
    
    public static function input($data = []) {
        $default = [
            'id'    => '',
            'class' => 'form-control',
            'type'  => 'text',
            'name'  => '',
            'value' => '',
            'text'  => '',
            /*
             * Si no tiene valor no se le agregara el "=".
             * EJ: ['placeholder' => 'text', 'autofocus' => '']
             */
            'data'  => [],
        ];
        
        $data  = array_merge($default, $data);
        $class = self::getAttribute(Arrays::get($data, 'class'), 'class');
        $id    = Arrays::get($data, 'id');
        $name  = Arrays::get($data, 'name');
        $type  = Arrays::get($data, 'type');
        $value = Arrays::get($data, 'value');
        $text  = Arrays::get($data, 'text');
        $attr  = self::getAttributes(Arrays::get($data, 'data'));
        
        if (empty($id)) {
            $id = 'input-' . self::$INPUT_ID;
            ++self::$INPUT_ID;
        }
        
        echo sprintf('<input id="%1$s" %2$s type="%3$s" name="%4$s" value="%5$s" %6$s/>%7$s', $id, $class, $type, $name, $value, $attr, $text);
    }
    
    private static function getAttributes($attr) {
        if (!empty($attr) && is_array($attr)) {
            $keys = array_keys($attr);
            $attr = array_map(function($value, $key) {
                if (empty($value)) {
                    return $key;
                }
                
                return "$key='$value'";
            }, $attr, $keys);
            
            return implode(' ', $attr);
        }
        
        return '';
    }
    
    public static function inputCheckbox($name, $text, $value = 'on', $data = []) {
        self::inputTypeSelect('checkbox', $name, $text, $value, $data);
    }
    
    public static function inputHidden($name, $value, $data = []) {
        self::input(array_merge($data, [
            'name'  => $name,
            'type'  => 'hidden',
            'value' => $value,
            'class' => '',
        ]));
    }
    
    public static function inputEmail($name, $labelText, $value = '', $data = []) {
        self::inputType('email', $name, $value, $labelText, $data);
    }
    
    public static function inputType($type, $name, $value, $labelText, $data = []) {
        $data = array_merge($data, [
            'type'      => $type,
            'name'      => $name,
            'value'     => $value,
            'labelText' => $labelText,
        ]);
        self::inputLabel($data);
    }
    
    public static function inputLabel($data = []) {
        self::label($data);
        self::input($data);
    }
    
    public static function label(&$data = []) {
        $default = [
            'labelClass' => 'control-label',
            'labelData'  => [],
            'labelText'  => '',
        ];
        
        $data  = array_merge($default, $data);
        $id    = Arrays::get($data, 'id');
        $class = self::getAttribute(Arrays::get($data, 'labelClass'), 'class');
        $attr  = self::getAttributes(Arrays::get($data, 'labelData'));
        
        if (empty($id)) {
            $id = 'input-' . self::$INPUT_ID;
            ++self::$INPUT_ID;
            $data['id'] = $id;
        }
        
        echo sprintf('<label for="%1$s" %2$s %3$s>%4$s</label>', $id, $class, $attr, Arrays::get($data, 'labelText'));
    }
    
    public static function inputTel($name, $labelText, $value = '', $data = []) {
        self::inputType('tel', $name, $value, $labelText, $data);
    }
    
    public static function inputUrl($name, $labelText, $value = '', $data = []) {
        self::inputType('url', $name, $value, $labelText, $data);
    }
    
    public static function inputNumber($name, $labelText, $value = '', $data = []) {
        self::inputType('number', $name, $value, $labelText, $data);
    }
    
    public static function inputPassword($name, $labelText, $value = '', $data = []) {
        self::inputType('password', $name, $value, $labelText, $data);
    }
    
    public static function inputText($name, $labelText, $value = '', $data = []) {
        self::inputType('text', $name, $value, $labelText, $data);
    }
    
    public static function buttonSubmit($text, $name = '', $value = '', $data = []) {
        $data['text']  = $text;
        $data['name']  = $name;
        $data['value'] = $value;
        self::button($data);
    }
    
    public static function button($data = []) {
        $default = [
            'text'  => '',
            'name'  => '',
            'value' => '',
            'type'  => 'submit',
            'id'    => '',
            'class' => 'btn btn-primary',
            'data'  => [],
        ];
        $data    = array_merge($default, $data);
        $id      = self::getAttribute(Arrays::get($data, 'id'), 'id');
        $class   = self::getAttribute(Arrays::get($data, 'class'), 'class');
        $value   = self::getAttribute(Arrays::get($data, 'value'), 'value');
        $name    = self::getAttribute(Arrays::get($data, 'name'), 'name');
        $text    = Arrays::get($data, 'text');
        $type    = Arrays::get($data, 'type');
        $attr    = self::getAttributes(Arrays::get($data, 'data'));
        
        echo sprintf('<button %1$s %2$s %3$s %4$s type="%5$s" %6$s>%7$s</button>', $id, $class, $name, $value, $type, $attr, $text);
    }
    
    public static function buttonButton($text, $data = []) {
        $data['text'] = $text;
        $data['type'] = 'button';
        self::button($data);
    }
    
    public static function textAreaBasic($name, $content, $data = []) {
        $data['name']    = $name;
        $data['content'] = $content;
        self::textAreaLabel($data);
    }
    
    public static function textAreaLabel($data = []) {
        self::label($data);
        self::textArea($data);
    }
    
    public static function textArea($data = []) {
        $default = [
            'id'      => '',
            'class'   => '',
            'rows'    => '3',
            'name'    => '',
            'content' => '',
            'data'    => [],
        ];
        $data    = array_merge($default, $data);
        $id      = Arrays::get($data, 'id');
        $class   = self::getAttribute(Arrays::get($data, 'class'), 'class');
        $rows    = Arrays::get($data, 'rows');
        $name    = Arrays::get($data, 'name');
        $content = Arrays::get($data, 'content');
        $attr    = self::getAttributes(Arrays::get($data, 'data'));
        
        if (empty($id)) {
            $id = 'textarea-' . self::$INPUT_ID;
            ++self::$INPUT_ID;
        }
        
        echo sprintf('<textarea id="%1$s" %2$s name="%3$s" %4$s %5$s>%6$s</textarea>', $id, $class, $name, $rows, $attr, $content);
    }
    
    public static function linkRoute($route, $controller = '', $action = '', $param = '', $data = []) {
        $data['route'] = $route;
        self::linkController($controller, $action, $param, $data);
    }
    
    public static function linkController($controller, $action = '', $param = '', $data = []) {
        $data['controller'] = $controller;
        self::linkAction($action, $param, $data);
    }
    
    public static function linkAction($action, $param = '', $data = []) {
        $data['action'] = $action;
        $data['param']  = $param;
        self::link($data);
    }
    
    public static function link($data = []) {
        $default = [
            'action'     => '',
            'controller' => Router::getCurrentNameController(),
            'param'      => '',
            //Texto a mostrar, si no esta se usara el contenido de "href"
            'text'       => '',
            'title'      => '',
            'target'     => '_blank',
            'addParam'   => '',
            'class'      => '',
            'id'         => '',
            'addAttr'    => '',
            //EJ: admin, login, install
            'route'      => Router::getCurrentDirectory(),
            'url'        => Router::getSiteURL(),
        ];
        
        $data       = array_merge($default, $data);
        $route      = self::addSlashEnd(Arrays::get($data, 'route'));
        $controller = self::addSlashEnd(Arrays::get($data, 'controller'));
        $action     = self::addSlashEnd(Arrays::get($data, 'action'));
        $url        = Arrays::get($data, 'url');
        $addParam   = Arrays::get($data, 'addParam');
        $class      = self::getAttribute(Arrays::get($data, 'class'), 'class');
        $id         = self::getAttribute(Arrays::get($data, 'id'), 'id');
        $text       = Arrays::get($data, 'text');
        
        if (!empty($addParam)) {
            if (is_array($addParam)) {
                $keys     = array_keys($addParam);
                $addParam = array_map(function($param, $key) {
                    return "$key=$param";
                }, $addParam, $keys);
                $addParam = implode('&', $addParam);
            }
            
            $addParam = "?$addParam";
        }
        
        $href = sprintf('%1$s%2$s%3$s%4$s%5$s%6$s', $url, $route, $controller, $action, Arrays::get($data, 'param'), $addParam);
        
        //Si el texto a mostrar esta vacio se reemplaza por el enlace.
        if (empty($text)) {
            $text = $href;
        }
        
        echo sprintf('<a %1$s %2$s href="%3$s" title="%4$s" target="%5$s" %6$s>%7$s</a>', $id, $class, $href, Arrays::get($data, 'title'), Arrays::get($data, 'target'), Arrays::get($data, 'addAttr'), $text);
    }
    
    private static function addSlashEnd($value) {
        return empty($value) ? '' : "$value/";
    }
    
    public static function selectMultiple($name, $options, $data = []) {
        $data['data']['multiple'] = '';
        self::selectOne($name . '[]', $options, $data);
    }
    
    public static function selectOne($name, $options, $data = []) {
        $data['name']    = $name;
        $data['options'] = $options;
        self::selectLabel($data);
    }
    
    public static function selectLabel($data = []) {
        self::label($data);
        self::select($data);
    }
    
    public static function select($data = []) {
        $default = [
            'id'      => '',
            'class'   => '',
            'name'    => '',
            'data'    => [],
            'options' => [],
        ];
        $data    = array_merge($default, $data);
        $id      = Arrays::get($data, 'id');
        $class   = self::getAttribute(Arrays::get($data, 'class'), 'class');
        $name    = Arrays::get($data, 'name');
        $attr    = self::getAttributes(Arrays::get($data, 'data'));
        $options = self::selectOption(Arrays::get($data, 'options'));
        
        if (empty($id)) {
            $id = 'select-' . self::$INPUT_ID;
            ++self::$INPUT_ID;
        }
        
        echo sprintf('<select id="%1$s" %2$s name="%3$s" %4$s>%5$s</select>', $id, $class, $name, $attr, $options);
    }
    
    private static function selectOption($options) {
        $options = array_map(function($option) {
            $value = Arrays::get($option, 'optionValue');
            $attr  = self::getAttributes(Arrays::get($option, 'optionData'));
            $text  = Arrays::get($option, 'optionText');
            
            return sprintf('<option value="%1$s" %2$s>%3$s</option>', $value, $attr, $text);
        }, $options);
        
        return implode('', $options);
    }
    
    public static function createSelectOption($dataList, $closureGetValueAndGetText, $selected = '') {
        if (is_callable($closureGetValueAndGetText)) {
            return array_map(function($data) use ($closureGetValueAndGetText, $selected) {
                $optionValueAndText = $closureGetValueAndGetText($data);
                $value              = Arrays::get($optionValueAndText, 'optionValue');
                
                if (is_array($selected)) {
                    $isSelected = array_search($value, $selected) !== FALSE;
                } else {
                    $isSelected = $value == $selected;
                }
                
                $optionValueAndText['optionData'] = $isSelected ? ['selected' => ''] : [];
                
                return $optionValueAndText;
            }, $dataList);
        }
        
        return [];
    }
    
    public static function table($value, $columnData = [], $rowData = [], $data = []) {
        $value['columnData'] = $columnData;
        $value['rowData']    = $rowData;
        //EJ: 'tBody' => [[col1, col2, col3], [row2-col1, row2-col2, row2-col3]]
        $default            = [
            'id'                 => '',
            'class'              => '',
            'data'               => [],
            'tBody'              => [],
            'tHead'              => [],
            'tFoot'              => [],
            'columnData'         => [
                //Aplica a todas las columnas.
                0 => [],
                //Aplica a la columna 1
                1 => [],
                //2 => [],//Aplica a la columna 2
                //3 => [],
                //. => [],
                //. => [],
                //(n) => [],//Aplica a la columna (n)
            ],
            'rowData'            => [],
            'columnDataHeadFoot' => [],
            'rowDataHeadFoot'    => [],
        ];
        $data               = array_merge($data, $value);
        $data               = array_merge($default, $data);
        $columnData         = Arrays::get($data, 'columnData');
        $rowData            = Arrays::get($data, 'rowData');
        $columnDataHeadFoot = Arrays::get($data, 'columnDataHeadFoot');
        $rowDataHeadFoot    = Arrays::get($data, 'rowDataHeadFoot');
        $id                 = self::getAttribute(Arrays::get($data, 'id'), 'id');
        $class              = self::getAttribute(Arrays::get($data, 'class'), 'class');
        $attr               = self::getAttributes(Arrays::get($data, 'attr'));
        $tFoot              = Arrays::get($data, 'tFoot');
        $tHead              = self::getTableRows(Arrays::get($data, 'tHead'), 'th', $columnDataHeadFoot, $rowDataHeadFoot);
        $tBody              = self::getTableRows(Arrays::get($data, 'tBody'), 'td', $columnData, $rowData);
        
        if ($tFoot === '') {
            $tFoot = $tHead;
        } elseif ($tFoot == NULL || (is_array($tFoot) && empty($tFoot))) {
            $tFoot = '';
        } else {
            $tFoot = self::getTableRows($tFoot, 'th', $columnDataHeadFoot, $rowDataHeadFoot);
        }
        
        echo sprintf('<table %1$s %2$s %3$s><thead>%4$s</thead><tfoot>%5$s</tfoot><tbody>%6$s</tbody></table>', $id, $class, $attr, $tHead, $tFoot, $tBody);
    }
    
    private static function getTableRows($dataList, $tagColumnName = 'td', $columnData = [], $rowData = []) {
        $rowPosition    = 0;
        $columnPosition = 0;
        $isArray        = FALSE;
        $value          = array_map(function($data) use ($tagColumnName, &$isArray, $columnData, &$columnPosition) {
            ++$columnPosition;
            
            if (is_array($data)) {
                $isArray = TRUE;
                
                return implode('', self::getTableColumns($data, $tagColumnName, $columnData, $columnPosition));
            }
            
            return self::getTableColumns($data, $tagColumnName, $columnData, $columnPosition);
        }, $dataList);
        
        ++$rowPosition;
        $classAll = self::getClassAttributeTable($rowData, 0);
        $attrAll  = self::getAttributes(Arrays::get($rowData, 0));
        $class    = "$classAll " . self::getClassAttributeTable($rowData, $rowPosition);
        $attr     = "$attrAll " . self::getAttributes(Arrays::get($rowData, $rowPosition));
        
        if ($isArray) {
            $len = count($value) - 1;
            $value = array_map(function($data) use (&$rowPosition, $rowData, $classAll, $attrAll, $len) {
                ++$rowPosition;
                $class = "$classAll " . self::getClassAttributeTable($rowData, $rowPosition);
                $attr  = "$attrAll " . self::getAttributes(Arrays::get($rowData, $rowPosition));
                $class = self::getAttribute($class, 'class');
                //Para evitar crear una fila sin datos.
                if($len == $rowPosition){
                    return "$data</tr>";
                }
                
                return sprintf('%1$s</tr><tr %2$s %3$s>', $data, $class, $attr);
            }, $value);
        }
        
        return sprintf('<tr %1$s %2$s>%3$s</tr>', self::getAttribute($class, 'class'), $attr, implode('', $value));
    }
    
    private static function getTableColumns($dataColumns, $tagName = 'td', $columnData, $columnPosition) {
        $value         = $dataColumns;
        $auxColumnData = $columnData;
        $classAll      = self::getClassAttributeTable($auxColumnData, 0);
        $attrAll       = self::getAttributes(Arrays::get($auxColumnData, 0));
        $class         = "$classAll " . self::getClassAttributeTable($auxColumnData, $columnPosition);
        $attr          = "$attrAll " . self::getAttributes(Arrays::get($auxColumnData, $columnPosition));
        
        if (is_array($dataColumns)) {
            return array_map(function($data) use ($tagName, $columnData, $columnPosition) {
                return self::getTableColumns($data, $tagName, $columnData, $columnPosition);
            }, $dataColumns);
        }
        
        return sprintf('<%1$s %2$s %3$s>%4$s</%1$s>', $tagName, self::getAttribute($class, 'class'), $attr, $value);
    }
    
    private static function getClassAttributeTable(&$data, $key) {
        $class = Arrays::get(Arrays::get($data, $key), 'class');
        
        if (empty($class)) {
            return '';
        }
        
        unset($data[$key]['class']);
        
        return $class;
    }
    
    public static function createTableValue($body, $head = [], $foot = []) {
        return [
            'tBody' => $body,
            'tHead' => $head,
            'tFoot' => $foot,
        ];
    }
    
    public static function imageLocal($src, $title, $data = []) {
        $data['title'] = $title;
        $data['src']   = Router::getSiteURL() . "app/resources/img/$src";
        
        if (!Arrays::keyExists($data, 'alt')) {
            $data['alt'] = $title;
        }
        
        self::image($data);
    }
    
    public static function image($data = []) {
        $default = [
            'id'    => '',
            'class' => '',
            'src'   => '',
            'alt'   => '',
            'title' => '',
            'data'  => [],
        ];
        $data    = array_merge($default, $data);
        $id      = self::getAttribute(Arrays::get($data, 'id'), 'id');
        $class   = self::getAttribute(Arrays::get($data, 'class'), 'class');
        $src     = Arrays::get($data, 'src');
        $alt     = self::getAttribute(Arrays::get($data, 'alt'), 'alt');
        $title   = self::getAttribute(Arrays::get($data, 'title'), 'title');
        $attr    = self::getAttributes(Arrays::get($data, 'data'));
        
        echo sprintf('<img %1$s %2$s src="%3$s" %4$s %5$s %6$s/>', $id, $class, $src, $alt, $title, $attr);
    }
}
