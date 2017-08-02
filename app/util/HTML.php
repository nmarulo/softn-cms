<?php
/**
 * HTML.php
 */

namespace SoftnCMS\util;

use SoftnCMS\models\managers\OptionsManager;
use SoftnCMS\route\Route;
use SoftnCMS\rute\Router;

/**
 * Class HTML
 * @author Nicolás Marulanda P.
 */
class HTML {
    
    public static function input($name, $value, $type = 'text', $attributes = []) {
        $attributesList = self::getAttributes($attributes);
        $placeholder    = Arrays::get($attributes, 'placeholder');
        $autofocus      = Arrays::get($attributes, 'autofocus');
        $placeholder    = empty($placeholder) ? '' : "placeholder='$placeholder'";
        $autofocus      = empty($autofocus) ? '' : "autofocus";
        
        echo sprintf('<input %1$s %2$s type="%3$s" name="%4$s" value="%5$s" %6$s %7$s />', $attributesList['id'], $attributesList['class'], $type, $name, $value, $placeholder, $autofocus);
    }
    
    private static function getAttributes($attributes) {
        $class = Arrays::get($attributes, 'class');
        $id    = Arrays::get($attributes, 'id');
        $data  = Arrays::get($attributes, 'data');
        
        return [
            'id'    => empty($id) ? '' : "id='$id'",
            'class' => empty($class) ? '' : "class='$class'",
            'data'  => empty($data) ? '' : $data,
        ];
    }
    
    public static function button($name, $value, $content, $type, $attributes = []) {
        $attributesList = self::getAttributes($attributes);
        
        echo sprintf('<button %1$s %2$s name="%3$s" value="%4$s" type="%6$s">%5$s</button>', $attributesList['id'], $attributesList['class'], $name, $value, $content, $type);
    }
    
    public static function textArea() {
    
    }
    
    public static function link($action, $rute, $param, $content, $attributes = []) {
        $attributesList = self::getAttributes($attributes);
        $title          = Arrays::get($attributes, 'title');
        $target         = Arrays::get($attributes, 'target');
        $title          = empty($title) ? '' : "title='$title'";
        $target         = empty($target) ? '' : "target='$target'";
        $href           = Router::getSiteURL() . "$rute";
        $href           .= empty($action) ? '' : "/$action";
        
        if (!empty($param)) {
            $href .= '?';
            
            if (is_array($param)) {
                $href .= implode('&', $param);
            } else {
                $href .= $param;
            }
        }
        
        echo sprintf('<a %1$s %2$s href="%3$s" %5$s %6$s>%4$s</a>', $attributesList['id'], $attributesList['class'], $href, $content, $title, $target);
    }
    
    public static function select() {
    
    }
    
    public static function table() {
    
    }
    
    public static function image($fileName, $alt = '', $title = '', $attributes = []) {
        $attributesList = self::getAttributes($attributes);
        $src            = 'app/resources/img/';
        
        if (Router::getCurrentDirectory() == Route::DIRECTORY_THEME) {
            $optionsManager = new OptionsManager();
            $theme          = $optionsManager->searchByName(OPTION_THEME)
                                             ->getOptionValue();
            $src            = "app/themes/$theme/resources/img/";
        }
        
        $src = Router::getSiteURL() . $src . $fileName;
        
        echo sprintf('<img %1$s %2$s src="%3$s" alt="%4$s" title="%5$s"/>', $attributesList['id'], $attributesList['class'], $src, $alt, $title);
    }
}
