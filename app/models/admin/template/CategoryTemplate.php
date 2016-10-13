<?php
/**
 * CategoryTemplate.php
 */

namespace SoftnCMS\models\admin\template;

use SoftnCMS\controllers\Token;
use SoftnCMS\models\admin\Category;
use SoftnCMS\models\theme\Template as ThemeTemplate;

/**
 * Class CategoryTemplate
 * @author Nicolás Marulanda P.
 */
trait CategoryTemplate {
    
    /**
     * @var Category;
     */
    private $instance;
    
    /**
     * @return bool Si es TRUE, el objeto es una instancia por defecto.
     */
    public function isDefault() {
        return empty($this->instance->getID());
    }
    
    public function getUrlUpdate($concat = '', $isEcho = TRUE) {
        return Template::getUrlCategory('update/' . $this->getID() . "/$concat", $isEcho);
    }
    
    public function getID() {
        return $this->instance->getID();
    }
    
    public function getUrlDelete($concat = '', $isEcho = TRUE) {
        $concat = Template::getPagination()
                          ->getRoutePagedNow() . "/$concat" . Token::urlField();
        
        return Template::getUrlCategory('delete/' . $this->getID() . "/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene el enlace a la publicación en la plantilla.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public function getUrl($concat = '', $isEcho = TRUE) {
        return ThemeTemplate::getUrlCategory($this->getID() . "/$concat", $isEcho);
    }
}
