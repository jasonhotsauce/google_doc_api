<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Custom Model for PDO.
 * @author Wenbin Zhang
 *
 */
class MY_Model extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Wrapper function to prepare PDO query.
     * @param string $query
     */
    protected function prepare($query){
        return $this->db->conn_id->prepare($query);
    }
    
    protected function lastInsertId($name=null){
        return $this->db->conn_id->lastInsertId($name);
    }
}