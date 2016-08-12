<?php

/**
 * Modulo del modelo post.
 * Gestiona el proceso de insertar posts.
 */

namespace SoftnCMS\models\admin;

use SoftnCMS\models\admin\Post;
use SoftnCMS\controllers\DBController;

/**
 * Clase que gestiona el proceso de insertar posts.
 *
 * @author Nicolás Marulanda P.
 */
class PostInsert {

    /** @var string Titulo. */
    private $postTitle;

    /** @var int Estado. */
    private $postStatus;

    /** @var string Contenido. */
    private $postContents;

    /** @var int Estado de los comentarios. */
    private $commentStatus;

    /** @var int Identificador del autor. */
    private $userID;

    /** @var string Nombre de las columnas. */
    private static $COLUMNS = Post::POST_TITLE . ', ' . Post::POST_STATUS . ', ' . Post::POST_DATE . ', ' . Post::POST_UPDATE . ', ' . Post::POST_CONTENTS . ', ' . Post::COMMENT_STATUS . ', ' . Post::USER_ID;

    /** @var string Nombre de los indices para preparar la consulta. */
    private static $VALUES = ':' . Post::POST_TITLE . ', ' . ':' . Post::POST_STATUS . ', ' . ':' . Post::POST_DATE . ', ' . ':' . Post::POST_UPDATE . ', ' . ':' . Post::POST_CONTENTS . ', ' . ':' . Post::COMMENT_STATUS . ', ' . ':' . Post::USER_ID;

    /** @var array Lista con los indices, valores y tipos de datos para la consulta. */
    private $prepareStatement;

    /** @var int Identificador del INSERT realizado. */
    private $lastInsertId;

    /**
     * Constructor.
     * @param string $postTitle Titulo.
     * @param string $postContents Contenido.
     * @param int $commentStatus Estado de los comentarios.
     * @param int $postStatus Estado.
     * @param int $userID Identificador del autor.
     */
    public function __construct($postTitle, $postContents, $commentStatus, $postStatus, $userID) {
        $this->postTitle = $postTitle;
        $this->postContents = $postContents;
        $this->commentStatus = $commentStatus;
        $this->postStatus = $postStatus;
        $this->userID = $userID;
        $this->prepareStatement = [];
    }

    /**
     * Metodo que realiza el proceso de insertar el post en la base de datos.
     * @return bool Si es TRUE, todo se realizo correctamente.
     */
    public function insert() {
        $db = DBController::getConnection();
        $table = Post::getTableName();
        $this->prepare();

        if ($db->insert($table, self::$COLUMNS, self::$VALUES, $this->prepareStatement)) {
            $this->lastInsertId = $db->lastInsertId();
            return \TRUE;
        }

        return \FALSE;
    }

    /**
     * Metodo que obtiene el identificador del nuevo post.
     * @return int
     */
    public function getLastInsertId() {
        return $this->lastInsertId;
    }

    /**
     * Metodo que establece los datos a preparar.
     */
    private function prepare() {
        $date = \date('Y-m-d H:i:s', \time());
        $this->addPrepare(':' . Post::POST_TITLE, $this->postTitle, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_STATUS, $this->postStatus, \PDO::PARAM_INT);
        $this->addPrepare(':' . Post::POST_DATE, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_UPDATE, $date, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::POST_CONTENTS, $this->postContents, \PDO::PARAM_STR);
        $this->addPrepare(':' . Post::COMMENT_STATUS, $this->commentStatus, \PDO::PARAM_INT);
        $this->addPrepare(':' . Post::USER_ID, $this->userID, \PDO::PARAM_INT);
    }

    /**
     * Metodo que guarda los datos establecidos.
     * @param string $parameter Indice a buscar. EJ: ":ID"
     * @param string $value Valor del indice.
     * @param int $dataType Tipo de dato. EJ: \PDO::PARAM_*
     */
    private function addPrepare($parameter, $value, $dataType) {
        $this->prepareStatement[] = DBController::prepareStatement($parameter, $value, $dataType);
    }

}
