<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mnhcc\ml\classes;

use mnhcc\ml; {

    /**
     * Description of Filter
     *
     * @author carschrotter
     */
    abstract class Filter extends MNHcC {

	public static function __callStatic($name, $arguments) {
	    switch (true) {
		case (strpos($name, 'input') === 0):
		    $filter = preg_replace("~^input~", '', $name);
		    $filter = preg_replace("~^_~", '', strtolower($filter));

		    array_unshift($arguments, $filter);
		    return (new ReflectionStaticMethod(self::getCalledClass(), 'input'))->invokeArgs($arguments);
		    break;
		case ($name == 'var') :
		    return (new ReflectionStaticMethod(self::getCalledClass(), 'var_'))->invokeArgs($arguments);
		default:
		    break;
	    }

	}
	
	public static function getInputConstant() {
	    
	}
	
	public static function input($type, $variable_name, $filter = FILTER_DEFAULT, $options = NULL) {
	    if (function_exists('\\filter_input')) {
		return \filter_input(constant('INPUT_'.strtoupper($type)), $variable_name, $filter, $options);
	    } else {

	    }
	}
	
	public static function html($value, $filter = 'specialchars') {
	    switch($filter) {
		case 'specialchars':
		    return \htmlspecialchars($value);
		    break;
	    }
	    
	}
	
	/**
	 * 
	 * @param mixed $variable
	 * @param int $filter default FILTER_DEFAULT
	 * @param array $options deafult null
	 * @return mixed
	 */
	public static function var_($variable, $filter = FILTER_DEFAULT, $options = null) {
	    return \filter_var($variable, $filter, $options);
	}
	
	/**
	 * 
	 * @param mixed $variable
	 * @param int $filter default FILTER_DEFAULT
	 * @param array $options deafult null
	 * @return bool
	 */
	public static function varIs($variable, $filter = FILTER_DEFAULT, $options = null) {
	    $filter_var = self::var_($variable, $filter, $options);
	    return (bool) !empty($filter_var);
	}
    }

}
   


