<?php

namespace mnhcc\ml\traits;
use mnhcc\ml;
use mnhcc\ml\classes\exception as exception; 
use mnhcc\ml\classes as classes;{

    if(!defined('APLICATIONNAMESPACE')) define ('APLICATIONNAMESPACE', null);
    
    /**
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    trait NoInstances {

        /**
         * 
         * @param type $instance
         * @return static
         */
        public static function getInstance($instance = 'default') {
           $class = get_called_class();
           if(APLICATIONNAMESPACE) {
                $tmp_class = classes\ClassHandler::makeClassName(
                        classes\ClassHandler::cutRootNamespace(get_called_class()),
                        APLICATIONNAMESPACE);
                $class = (classes\Helper::classExists($class, false, true)) ? $tmp_class : $class;
            }
            $reflection = new classes\ReflectionClass($class);
            if($instance == 'default') classes\Error::triggerError($class.'::getInstance() store no reference. Pleas use new '.$class.'() or '.$class.'::getInstance(NULL)');
            return $reflection->newInstanceArgs(func_get_args());
        }

        public static function isInit() {
            throw new exception\Exception('Call to undefined method ' . $this->getClass() . '::' . __FUNCTION__ . '()', exception\Exception::noMethodImplement);
        }

    }

}