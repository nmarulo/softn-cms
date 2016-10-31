<?php
/**
 * CommentTemplate.php
 */
/**
 * Modulo: Agrega funciones de plantilla a los datos de un comentario.
 */
namespace SoftnCMS\models\admin\template;

use SoftnCMS\controllers\Token;
use SoftnCMS\models\admin\Comment;
use SoftnCMS\models\theme\Template as ThemeTemplate;

/**
 * Clase TRAIT CommentTemplate
 * @author Nicolás Marulanda P.
 */
trait CommentTemplate {
    
    /** @var Comment */
    private $instance;
    
    /**
     * Método que comprueba si es una instancia por defecto, donde su ID es igual a 0.
     * @return bool Si es TRUE, el objeto es una instancia por defecto.
     */
    public function isDefault() {
        return empty($this->instance->getID());
    }
    
    /**
     * Método que obtiene la url de la pagina de actualización.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
    public function getUrlUpdate($concat = '', $isEcho = TRUE) {
        return Template::getUrlComment('update/' . $this->getID() . "/$concat", $isEcho);
    }
    
    /**
     * Método que obtiene el identificador.
     * @return int
     */
    public function getID() {
        return $this->instance->getID();
    }
    
    /**
     * Método que obtiene la url de la pagina de borrado.
     *
     * @param string $concat
     * @param bool   $isEcho
     *
     * @return string
     */
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
