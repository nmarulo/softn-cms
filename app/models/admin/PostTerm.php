<?php

/**
 * Modulo del modelo post-etiqueta.
 * Gestiona los datos de cada relación post-etiqueta.
 */

namespace SoftnCMS\models\admin;

/**
 * Clase que gestiona los datos de cada relación post-etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class PostTerm {

    /** Identificador de la entrada. */
    const RELATIONSHIPS_POST_ID = 'relationships_post_ID';

    /** Identificador de la etiqueta. */
    const RELATIONSHIPS_TERM_ID = 'relationships_term_ID';

    /** @var string Nombre de la table. */
    private static $TABLE = \DB_PREFIX . 'posts_terms';

    /** @var array Datos. */
    private $postTerm;

    /**
     * Constructor.
     * @param array $data
     */
    public function __construct($data) {
        $this->postTerm = $data;
    }

    /**
     * Metodo que obtiene el nombre de la tabla.
     * @return string
     */
    public static function getTableName() {
        return self::$TABLE;
    }

    /**
     * Metodo que obtiene el identificador del post.
     * @return int
     */
    public function getPostID() {
        return $this->postTerm[self::RELATIONSHIPS_POST_ID];
    }

    /**
     * Metodo que obtiene el identificador de la etiqueta.
     * @return int
     */
    public function getTermID() {
        return $this->postTerm[self::RELATIONSHIPS_TERM_ID];
    }

}
