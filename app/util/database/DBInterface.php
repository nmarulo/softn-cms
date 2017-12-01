<?php
/**
 * DBInterface.php
 */

namespace SoftnCMS\util\database;

/**
 * Interface DBInterface
 * @author Nicolás Maruladan P.
 */
interface DBInterface {
    
    /**
     * @param string $query
     *
     * @return array|bool
     */
    public function select($query);
    
    /**
     * @param string $query
     *
     * @return bool
     */
    public function update($query);
    
    /**
     * @param string $column
     *
     * @return bool
     */
    public function updateByColumn($column);
    
    /**
     * @param string $query
     *
     * @return bool
     */
    public function delete($query = '');
    
    /**
     * @param string $allLogicalOperators
     *
     * @return bool
     */
    public function deleteByPrepareStatement($allLogicalOperators = 'AND');
    
    /**
     * @param string $query
     *
     * @return bool
     */
    public function insert($query = '');
    
    /**
     * @param string $parameter
     * @param mixed  $value
     * @param string $dataType
     * @param string $column
     */
    public function addPrepareStatement($parameter, $value, $dataType = '', $column = '');
    
    /**
     * @param array $prepareStatement
     */
    public function setPrepareStatement($prepareStatement);
    
    public function close();
    
    /**
     * @return string
     */
    public function getQuery();
    
    /**
     * @return int
     */
    public function getLastInsetId();
    
    /**
     * @return int
     */
    public function getRowCount();
    
    /**
     * @param string $table
     */
    public function setTable($table);
    
}
