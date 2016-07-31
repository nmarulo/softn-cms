<?php

/**
 * Modulo del modelo user.
 * Gestiona la lista de Usuarios.
 */

namespace SoftnCMS\models;

use SoftnCMS\models\User;

/**
 * Clase que gestiona la lista con todos los usuarios de la base de datos.
 *
 * @author Nicolás Marulanda P.
 */
class Users {

    /**
     * Lista de usuarios, donde el indice o clave corresponde al ID.
     * @var array 
     */
    private $users;

    /**
     * Constructor.
     */
    public function __construct() {
        $this->users = [];
    }

    /**
     * Metodo que obtiene todos los usuarios.
     * @return array
     */
    public function getUsers() {
        return $this->users;
    }

    /**
     * Metodo que obtiene, segun su ID, un usuario.
     * @param int $id
     * @return Users
     */
    public function getUser($id) {
        return $this->users[$id];
    }

    /**
     * Metodo que agrega un usuario a la lista.
     * @param User $user
     */
    public function addUser(User $user) {
        $this->users[$user->getID()] = $user;
    }

    /**
     * Metodo que obtiene un array con los datos de los usuarios y los agrega a la lista.
     * @param array $user
     */
    public function addUsers($user) {
        foreach ($user as $value) {
            $this->addUser(new User($value));
        }
    }
    
    public function count(){
        $select = $this->select('', [], 'COUNT(*) AS count');
        return $select[0]['count'];
    }
    
    public function selectAll(){
        if(!empty($this->users)){
            $this->users = [];
        }
        $select = $this->select();
        $this->addUsers($select);
    }

    /**
     * Metodo que realiza una consulta a la base de datos y obtiene todos los usuarios.
     */
    private function select($where = '', $prepare = [], $columns = '*', $limit = '', $orderBy = 'ID DESC') {
        $db = \SoftnCMS\controllers\DBController::getConnection();
        $table = User::getTableName();
        $fetch = 'fetchAll';
        return $db->select($table, $fetch, $where, $prepare, $columns, $orderBy, $limit);
    }

}
