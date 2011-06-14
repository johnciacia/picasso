<?php

/**
 * Singleton ErrorHelper
 * Use: hold onto errors between page loads
 */
class ErrorHelper {
    
    private static $instance = null;
    private static $error_array = array();
    
    private function __construct()
    {
        //Don't allow outside instantiations of this class
    }
    
    private function __clone()
    {
        //we don't want this class to be cloned
    }
    
    public static function getInstance() 
    { 
        if (!isset(self::$instance)) 
        { 
            self::$instance = new ErrorHelper(); 
        } 

        return self::$instance; 
    }  
    
    //return a reference to the error array
    public function getErrors()
    {
        return self::$error_array;
    } 
    
    public function getErrorCount()
    {
        //@TODO: O(n) time, not acceptable, modify a constant variable??
        return count(self::$error_array);
    } 
    
    public function getError($id)
    {
        return self::$error_array[$id];
    } 
    
    public function setError($error_text)
    {
        array_push(self::$error_array, $error_text);
        update_option('PROPEL_ERROR', self::$error_array);
    }
    
    public function clearAll()
    {
        self::$error_array = array();
        update_option('PROPEL_ERROR', '');
    }
    
}