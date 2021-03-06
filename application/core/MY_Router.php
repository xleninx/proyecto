<?php
    class MY_Router extends CI_Router {  
        var $suffix = '_controller';  
      
        function __construct() {  
            parent::__construct();  
        } 
        function set_class($class) {  
            $this->class = $class . $this->suffix;  
        }  
        function controller_name() {  
            if (strstr($this->class, $this->suffix)) {  
                return str_replace($this->suffix, '', $this->class);  
            }  
            else {  
                return $this->class;  
            }  
      
        }  
    }  
?>