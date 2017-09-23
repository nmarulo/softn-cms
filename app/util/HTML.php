<?php
/**
 * HTML.php
 */

namespace SoftnCMS\util;

use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\models\tables\User;
use SoftnCMS\route\Route;
use SoftnCMS\rute\Router;

/**
 * Class HTML
 * @author Nicolás Marulanda P.
 */
class HTML {
    
    private static $INPUT_ID = 1;
    
    public static function inputRadio($name, $text, $value = 'on', $data = []) {
        self::inputCheckBoxRadio('radio', $name, $text, $value, $data);
    }
    
    private static function inputCheckBoxRadio($type, $name, $text, $value = 'on', $data = []) {
        $data['name']  = $name;
        $data['text']  = $text;
        $data['value'] = $value;
        $data['type']  = $type;
        $class         = Arrays::get($data, 'labelClass');
        
        if (!empty($class)) {
            $class = "class='$class'";
        }
        
        if (!Arrays::keyExists($data, 'class')) {
            $data['class'] = '';
        }
        
        echo "<label $class>";
        self::input($data);
        echo '</label>';
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
        $attr  = self::getAttributes(Arrays::get($data, 'data'));
        $class = Arrays::get($data, 'class');
        $id    = Arrays::get($data, 'id');
        $name  = Arrays::get($data, 'name');
        $type  = Arrays::get($data, 'type');
        $value = Arrays::get($data, 'value');
        $text  = Arrays::get($data, 'text');
        
        if (empty($id)) {
            $id = 'input-' . self::$INPUT_ID;
            ++self::$INPUT_ID;
        }
        
        if (!empty($class)) {
            $class = "class='$class'";
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
        self::inputCheckBoxRadio('checkbox', $name, $text, $value, $data);
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
        $class = Arrays::get($data, 'labelClass');
        $attr  = self::getAttributes(Arrays::get($data, 'labelData'));
        
        if (!empty($class)) {
            $class = "class='$class'";
        }
        
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
    
    public static function button($name, $value, $content, $type, $attributes = []) {
        $attributesList = self::getAttributes($attributes);
        
        echo sprintf('<button %1$s %2$s name="%3$s" value="%4$s" type="%6$s">%5$s</button>', $attributesList['id'], $attributesList['class'], $name, $value, $content, $type);
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
        $class   = Arrays::get($data, 'class');
        $rows    = Arrays::get($data, 'rows');
        $name    = Arrays::get($data, 'name');
        $content = Arrays::get($data, 'content');
        $attr    = self::getAttributes(Arrays::get($data, 'data'));
        
        if (!empty($class)) {
            $class = "class='$class'";
        }
        
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
        $class      = Arrays::get($data, 'class');
        $id         = Arrays::get($data, 'id');
        $text       = Arrays::get($data, 'text');
        
        if (!empty($class)) {
            $class = "class='$class'";
        }
        
        if (!empty($id)) {
            $id = "id='$id'";
        }
        
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
        self::selectOne($name, $options, $data);
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
        $class   = Arrays::get($data, 'class');
        $name    = Arrays::get($data, 'name');
        $attr    = self::getAttributes(Arrays::get($data, 'data'));
        $options = self::selectOption(Arrays::get($data, 'options'));
        
        if (!empty($class)) {
            $class = "class='$class'";
        }
        
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
    
    public static function table() {
    
    }
    
    public static function image($fileName, $alt = '', $title = '', $attributes = []) {
        //        $attributesList = self::getAttributes($attributes);
        //        $src            = 'app/resources/img/';
        //
        //        if (Router::getCurrentDirectory() == Route::DIRECTORY_THEME) {
        //            $optionsManager = new OptionsManager();
        //            $theme          = $optionsManager->searchByName(OPTION_THEME)
        //                                             ->getOptionValue();
        //            $src            = "app/themes/$theme/resources/img/";
        //        }
        //
        //        $src = Router::getSiteURL() . $src . $fileName;
        //
        //        echo sprintf('<img %1$s %2$s src="%3$s" alt="%4$s" title="%5$s"/>', $attributesList['id'], $attributesList['class'], $src, $alt, $title);
    }
}
