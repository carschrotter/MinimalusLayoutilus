<?php

namespace mnhcc\ml\traits;
use \mnhcc\ml;
use \mnhcc\ml\classes as classes; {
    
    if(!defined('APPLICATIONNAMESPACE')) define ('APPLICATIONNAMESPACE', null);
    if(!defined('DEFAULTINSTANCE')) define ('DEFAULTINSTANCE', 'default');
    /**
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    trait Instances {

        protected static $instances = [];
        
        /**
         * is init (a object createt)
         * @var bool 
         */
        protected static $init = false;

        /**
         *  is a object createt also init
         * @return bool
         */
        public static function isInit() {
            return self::$init;
        }

        /**
         * 
         * @param string $instance
         * @return static
         */
        public static function getInstance($instance = DEFAULTINSTANCE) {
            if (!isset(self::$instances[$instance])) {
                if(APPLICATIONNAMESPACE) {
                   $class = classes\ClassHandler::makeClassName(
			APPLICATIONNAMESPACE,
			classes\ClassHandler::cutRootNamespace(get_called_class())
		    );
                   if(classes\Helper::classExists($class, false, true)) {
		       $args = func_get_args();
		       if(classes\ArrayHelper::count($args) > 0) classes\ArrayHelper::shift($args);
                       self::$instances[$instance] = (new classes\ReflectionClass($class))->newInstanceArgs($args);
                       self::$init = true;
                   }
                }
                
                if(!self::$init) {
                    self::$instances[$instance] = new static(); 
                    self::$init = true;
                }
            }
            return self::$instances[$instance];
        }

    }

}