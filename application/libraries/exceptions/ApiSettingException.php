<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is an exception for your api setting. 
 * For demo purpose, this exception is simple. You definitely can do more about it
 * @author Wenbin Zhang
 *
 */
class ApiSettingException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    } 
}
