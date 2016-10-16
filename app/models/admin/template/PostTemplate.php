<?php
/**
 * PostTemplate.php
 */

namespace SoftnCMS\models\admin\template;

use SoftnCMS\controllers\Token;
use SoftnCMS\models\admin\Post;
use SoftnCMS\models\theme\Template as ThemeTemplate;

/**
 * Class PostTemplate
 * @author Nicolás Marulanda P.
 */
trait PostTemplate {
    
    /**
     * @var Post;
     */
    private $instance;
    
    /**
     * @return bool Si es TRUE, el objeto es una instancia por defecto.
     */
    public function isDefault() {
        return empty($this->instance->getID());
    }
    
    public function getUrlUpdate($concat = '', $isEcho = TRUE) {
        return Template::getUrlPost('update/' . $this->getID() . "/$concat", $isEcho);
    }
    
    public function getID() {
        return $this->instance->getID();
    }
    
    public function getUrlDelete($concat = '', $isEcho = TRUE) {
        $concat = Template::getPagination()
                          ->getRoutePagedNow() . "/$concat" . Token::urlField();
        
        return Template::getUrlPost('delete/' . $this->getID() . "/$concat", $isEcho);
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
        return ThemeTemplate::getUrlPost($this->getID() . "/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene el enlace a la pagina de usuario en la plantilla.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return bool
     */
    public function getUrlUser($concat = '', $isEcho = TRUE) {
        return ThemeTemplate::getUrlUser($this->instance->getUserID() . "/$concat", $isEcho);
    }
    
    public function getUserName($isEcho = TRUE) {
        return Template::get($this->instance->getUser()
                                            ->getUserName(), $isEcho);
    }
    
}
