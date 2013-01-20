<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class is the parent class for all other data type classes. It defines attributes, values
 * and provides an construct function that can be used in its child classes.
 * 
 * @author Wenbin Zhang
 *
 */
class DataModel {
    public function __construct() {
        if (func_num_args() == 1 && is_array(func_get_arg(0))) {
            // Pass in data array, create an object with it.
            $data = func_get_arg(0);
            $this->fromDataArray($data);
        }
    }
    
    protected function fromDataArray($data) {
        foreach ($data as $key=>$value) {
            $this->$key = $value;
            $keyType = "__$key".'Type';
            if (isset($this->$keyType) && property_exists($this, $keyType)) {
                if ($this->isSingleObjectArray($value)) {
                    $this->$key = $this->createObjectForType($keyType, $value);
                } elseif (is_array($value)) {
                    $objectArray = array();
                    foreach ($value as $valueItem) {
                        $objectArray[] = $this->createObjectForType($keyType, $valueItem);
                    }
                    $this->$key = $objectArray;
                } 
            }
        }
    }
    
    private function createObjectForType($keyType, $value) {
        if (is_array($value)) {
            $object = $this->$keyType;
            return new $object($value);
        }
    }
    
    private function isSingleObjectArray($arr) {
        if (!is_array($arr))
            return false;
        $keys = array_keys($arr);
        foreach ($keys as $key) {
            if (is_string($key))
                return true;
        }
        return false;
    }
    
    protected function assertIsArray($array, $obj, $method) {
        if (!is_array($array))
            throw new Exception("Parameter pass in method $method must be an array of type $obj");
    }
}