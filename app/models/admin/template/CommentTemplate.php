<?php
/**
 * CommentTemplate.php
 */

namespace SoftnCMS\models\admin\template;

use SoftnCMS\controllers\Token;
use SoftnCMS\models\admin\Comment;
use SoftnCMS\models\theme\Template as ThemeTemplate;

/**
 * Class CommentTemplate
 * @author Nicolás Marulanda P.
 */
trait CommentTemplate {
    
    /**
     * @var Comment;
     */
    private $instance;
    
    /**
     * @return bool Si es TRUE, el post es una instancia por defecto.
     */
    public function isDefault() {
        return empty($this->instance->getID());
    }
    
    public function getUrlUpdate($concat = '', $isEcho = TRUE) {
        return Template::getUrlComment('update/' . $this->getID() . "/$concat", $isEcho);
    }
    
    public function getID() {
        return $this->instance->getID();
    }
    
    public function getUrlDelete($concat = '', $isEcho = TRUE) {
        $concat = Template::getPagination()
                          ->getRoutePagedNow() . "/$concat" . Token::urlField();
        
        return Template::getUrlComment('delete/' . $this->getID() . "/$concat", $isEcho);
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
        return ThemeTemplate::getUrlPost($this->instance->getPostID() . "/$concat#comment-" . $this->getID(), $isEcho);
    }
}
