<?php

/**
 * Gestión de la base de datos.
 * @package SoftN-CMS\sn-includes
 */

/**
 * Clase usada para instanciar la conexión a la base de datos.
 * @author Nicolás Marulanda P.
 */
class SN_DB {

    /** @var PDO Instancia de la conexion a la base de datos. */
    private $connection;

    /** @var string Prefijo de las tablas. */
    private $prefix;

    /** @var string Guarda la sentencia SQL. */
    private $query;

    /**
     * Constructor.
     * Realiza la conexion a la base de datos. (Datos de conexión ver sn-config.php)
     */
    public function __construct() {
        //Establecer conexión con la base de datos
        try {
            $this->prefix = DB_PREFIX;
            $strConexion = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
            //Conexión con la base de datos. PDO Object.
            $this->connection = new PDO($strConexion, DB_USER, DB_PASSWORD);
        } catch (PDOException $ex) {
            die('ERROR FATAL: ' . $ex->getMessage());
        }
    }

    /**
     * Metodo que ejecuta un consulta SELECT.
     * @param array $arg Lista de opciones.
     * <ul>
     *  <li>$table Nombre de la tabla. Sin prefijo.</li>
     *  <li>$column Columnas. Por defecto '*'.</li>
     *  <li>$where Condiciones.</li>
     *  <li>$orderBy Ordenar por.</li>
     *  <li>$groupBy Agrupar por.</li>
     *  <li>$limit Numero de datos a retornar.</li>
     *  <li>$prepare Lista de indices a reemplazar en la consulta. EJ: [[':usuario', 'Carlos'], [':apellido', 'James']]</li>
     * </ul>
     * <b>Ejemplo:</b><br/>
     * $arg = [<br/>
     * 'table' => 'mitabla',<br/>
     * 'columns' => 'id, micolumna',<br/>
     * 'where' => '(id = :id AND micolumna = :micolumna) OR micolumna LIKE :micolumnalike',<br/>
     * 'orderBy' => 'id desc',<br/>
     * 'groupBy' => 'micolumna',<br/>
     * 'limit' => 5,<br/>
     * 'prepare' => [[':id', 1], [':micolumna', 'hola'], [':micolumnalike', 'h%la']],<br/>
     * ];<br/>
     * No es necesario enviar todas las opciones, solo completa las que sean necesarias.
     * @param string $fetch [opcional] Tipo de datos a retornar. <br/>
     * Con 'fetchAll' retorna un array asociativo.<br/>
     * Con 'fetchObject' retorna un objeto.<br/>
     * Por defecto, es NULL, retorna un objeto PDOStatement.
     * @return 0|array|PDOStatement 
     */
    public function query($arg, $fetch = null) {
        $out = 0;
        $defaults = array(
            'column' => '*',
            'table' => '',
            'where' => '',
            'orderBy' => '',
            'limit' => '',
            'prepare' => []
        );

        $defaults = array_merge($defaults, $arg);

        $defaults['table'] = $this->prefix . $defaults['table'];

        $sql = "SELECT $defaults[column]";
        $sql .= empty($defaults['table']) ? '' : " FROM $defaults[table]";
        $sql .= empty($defaults['where']) ? '' : " WHERE $defaults[where]";
        $sql .= empty($defaults['orderBy']) ? '' : " ORDER BY $defaults[orderBy]";
        $sql .= empty($defaults['limit']) ? '' : " LIMIT $defaults[limit]";

        $this->query = $sql;

        $sql = $this->connection->prepare($sql);

        if ($defaults['prepare']) {
            $this->bindValue($sql, $defaults['prepare']);
        }

        if ($sql->execute()) {
            switch ($fetch) {
                case 'fetchAll':
                    $out = $sql->fetchAll();
                    break;
                case 'fetchObject':
                    $out = $sql->fetchObject();
                    break;
                default :
                    $out = $sql;
                    break;
            }
        }

        return $out;
    }

    /**
     * Metodo que ejecuta un consulta INSERT, UPDATE y DELETE.
     * @param array $arg Lista de opciones.
     * <ul>
     *  <li>$type Tipo de sentencia SQL (INSERT, UPDATE o DELETE).</li>
     *  <li>$table Nombre de la tabla. Sin el prefijo.</li>
     *  <li>$column Valores SET de la sentencia <b>UPDATE</b>.</li>
     *  <li>$where Condiciones.</li>
     *  <li>$values Valores de la sentencia <b>INSERT</b>.</li>
     *  <li>$prepare Lista de indices a reemplazar en la consulta. EJ: [[':usuario', 'Carlos'], [':apellido', 'James']]</li>
     * </ul>
     * <b>Ejemplo:</b><br/>
     * $arg = [<br/>
     * 'type' => 'INSERT',<br/>
     * 'table' => 'mitabla',<br/>
     * 'columns' => 'id, micolumna',<br/>
     * 'values' => ':id, :micolumna',<br/>
     * 'prepare' => [[':id', 1], [':micolumna', 'hola']],<br/>
     * ];<br/>
     * <b>Ejemplo:</b><br/>
     * $arg = [<br/>
     * 'type' => 'UPDATE',<br/>
     * 'table' => 'mitabla',<br/>
     * 'columns' => 'micolumna = :micolumna',<br/>
     * 'where' => 'id = :id',<br/>
     * 'prepare' => [[':id', 1], [':micolumna', 'hola']],<br/>
     * ];<br/>
     * <b>Ejemplo:</b><br/>
     * $arg = [<br/>
     * 'type' => 'DELETE',<br/>
     * 'table' => 'mitabla',<br/>
     * 'where' => 'id = :id',<br/>
     * 'prepare' => [[':id', 1], [':micolumna', 'hola']],<br/>
     * ];<br/>
     * @return bool Si la consulta falla retorna False.
     */
    public function exec($arg) {
        $out = false;
        $defaults = array(
            'type' => 'INSERT',
            'column' => '*',
            'table' => '',
            'where' => '',
            'values' => '',
            'set' => '',
            'prepare' => []
        );

        $defaults = array_merge($defaults, $arg);

        $defaults['table'] = $this->prefix . $defaults['table'];

        switch ($defaults['type']) {
            case 'INSERT':
                $sql = "INSERT INTO $defaults[table]";
                $sql .= strcmp($defaults['column'], '*') ? " ($defaults[column])" : '';
                $sql .= " VALUE ($defaults[values])";
                break;
            case 'UPDATE':
                $sql = "UPDATE $defaults[table] SET $defaults[set] WHERE $defaults[where]";
                break;
            case 'DELETE':
                $sql = "DELETE FROM $defaults[table] WHERE $defaults[where]";
                break;
        }

        $this->query = $sql;

        $sql = $this->connection->prepare($sql);

        if ($defaults['prepare']) {
            $out = $this->bindValue($sql, $defaults['prepare']);
        }

        /*
         * $defaults['type'] == 'UPDATE' && $sql->rowCount() == 0 Si la fila que 
         * se quiere actualizar, lo nuevos datos son los mismo "rowCount" sera 0.
         */
        return !$out && $sql->execute() && (($defaults['type'] == 'UPDATE' && $sql->rowCount() == 0) || $sql->rowCount());
    }

    /**
     * Obtiene la conexion de la base de datos.
     * @return PDOStatement
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Cierra la conexion de la base de datos.
     */
    function close() {
        $this->connection = null;
    }

    /**
     * Obtiene la consulta SQL actual.
     * @return string
     */
    public function getQuery() {
        return $this->query;
    }

    /**
     * Metodo que ejecuta la función bindParam del PDOStatement.
     * @param PDOStatement $sql Instancia de la funcion prepare() de PDO.
     * @param array $prepare Lista de indices a reemplazar en la consulta. <br/>
     * EJ: [[':usuario', 'Carlos'], [':Apellido', 'James']]
     * @return int Retorna el numero de errores.
     */
    private function bindValue($sql, $prepare) {
        $error = 0;
        foreach ($prepare as $value) {
            if (!$sql->bindValue($value[0], $value[1])) {
                ++$error;
            }
        }
        return $error;
    }

}
