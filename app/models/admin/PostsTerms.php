<?php

/**
 * Modulo del modelo post-etiqueta.
 * Gestiona grupos de relaciones post-etiqueta.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\controllers\DBController;
use SoftnCMS\models\admin\PostTerm;
use SoftnCMS\models\admin\base\BaseModels;

/**
 * Clase que gestiona grupos de relaciones post-etiqueta.
 *
 * @author Nicolás Marulanda P.
 */
class PostsTerms extends BaseModels {

    /**
     * Lista de identificadores(ID) de etiquetas, donde el indice o clave corresponde al ID 
     * del post.
     * @var array 
     */
    private $termsID;

    /**
     * Lista de identificadores(ID) de posts, donde el indice o clave corresponde al ID 
     * de la etiqueta.
     * @var array 
     */
    private $postsID;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->termsID = [];
        $this->postsID = [];
    }

    /**
     * Metodo que obtiene todos las relaciones post-etiqueta de la base de datos.
     * @return PostsTerms|bool Si es False, no hay datos.
     */
    public static function selectAll() {
        $select = self::select(PostTerm::getTableName(), '', [], '*', '', '');

        return self::getInstanceData($select);
    }

    /**
     * Metodo que obtiene todos los identificadores(ID) de las etiquetas vinculadas a un post.
     * @param int $value Identificador del post.
     * @return array|bool Si es False, no hay datos.
     */
    public static function selectByPostID($value) {
        $select = self::selectBy(PostTerm::getTableName(), $value, PostTerm::RELATIONSHIPS_POST_ID, \PDO::PARAM_INT);

        $output = self::getInstanceData($select);

        if ($output === \FALSE) {
            return \FALSE;
        }

        return $output->getTerms($value);
    }

    /**
     * Metodo que obtiene todos los identificadores(ID) de los posts vinculados a una etiqueta.
     * @param int $value Identificador de la etiqueta.
     * @return array|bool Si es False, no hay datos.
     */
    public static function selectByTermID($value) {
        $select = self::selectBy(PostTerm::getTableName(), $value, PostTerm::RELATIONSHIPS_TERM_ID, \PDO::PARAM_INT);

        $output = self::getInstanceData($select);

        if ($output === \FALSE) {
            return \FALSE;
        }

        return $output->getPosts($value);
    }

    /**
     * Metodo que obtiene un objeto segun las especificaciones dadas.
     * @param string $table Nombre de la tabla.
     * @param int|string $value Valor a buscar.
     * @param string $column Nombre de la columna en la tabla.
     * @param int $dataType Tipo de dato.
     * @return array|bool Si es FALSE, no hay datos.
     */
    protected static function selectBy($table, $value, $column, $dataType = \PDO::PARAM_STR) {
        $parameter = ":$column";
        $where = "$column = $parameter";
        $prepare[] = DBController::prepareStatement($parameter, $value, $dataType);

        return self::select($table, $where, $prepare, '*', '', '');
    }

    /**
     * Metodo que recibe un lista de datos y retorna un instancia.
     * @param array $data Lista de datos.
     * @return PostsCategories|bool Si es FALSE, no hay datos.
     */
    public static function getInstanceData($data) {
        if ($data === \FALSE) {
            return \FALSE;
        }

        $output = new PostsTerms();
        $output->addData($data);

        return $output;
    }

    /**
     * Metodo que obtiene todos los identificadores(ID) de los posts vinculados a una etiqueta.
     * @param int $id Identificador de la etiqueta.
     * @return array
     */
    public function getPosts($id) {
        return $this->postsID[$id];
    }

    /**
     * Metodo que obtiene todos los identificadores(ID) de las etiquetas vinculadas a un post.
     * @param int $id Identificador del post.
     * @return array
     */
    public function getTerms($id) {
        return $this->termsID[$id];
    }

    /**
     * Metodo que agrega las relaciones post-etiqueta.
     * @param PostTerm $postTerm
     */
    public function add(PostTerm $postTerm) {
        $this->postsID[$postTerm->getTermID()][] = $postTerm->getPostID();
        $this->termsID[$postTerm->getPostID()][] = $postTerm->getTermID();
    }

    /**
     * Metodo que obtiene un array con los datos de la relación post-etiqueta y los agrega a la lista.
     * @param array $postTerm
     */
    public function addData($postTerm) {
        foreach ($postTerm as $value) {
            $this->add(new PostTerm($value));
        }
    }

}
