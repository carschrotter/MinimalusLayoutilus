<?php

namespace mnhcc\ml\classes; {
    /**
     * Description of Config
     * 
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class Config extends MNHcC {

        use \mnhcc\ml\traits\Instances;
        
        protected static $_instanceClass = __CLASS__;
        
        public $secure = 'Ay0keRT1l8';
        public $paths = ['root' => __DIR__];
        protected static $Consts = [];
        
        /**
         * 
         * @param string $name
         * @param mixed $value
         * @return boolean
         */
        public static function setConst($name, $value) {
            if (self::isInit()){
                self::$Consts[$name] = $value;
                return true;
            } else {
                return false;
            }
            
        }
        
        protected static function setConsts() {
            foreach (self::$Consts as $name => $val) {
                if(!defined($name)) {
                    define($name, $val);
                }
            }
        }
   
        public function __construct($config = NULL) {
            $parts = explode(NSS, static::getCalledClass());
            $this->provider = $parts[0];
            $this->aplication = $parts[1];
	    if($config !== NULL) $this->add($config);
            self::$instances['default'] = & $this;

            $this->setConsts();
        }
	
	public function add($var, $key = Null) {
	    if((is_array($var) || is_object($var)) && $key === NULL) {
		foreach($var as $key => $value) {
		    $this->set($key, $value);
		}
	    } else {
		$this->set($key, $var);
	    }	
	    return false;
	}
	
        public function set($key, $value) {
            $this->$key = $value;
        }

        public function get($key, $default = null) {
	    $keys = explode('.', $key);
	    if (!isset($this->$keys[1])) return $default;
	    
	    foreach($keys as $K) {
		if(isset($ref)) {
		    $ref  = &$this->$K;
		} else {
		    if (Helper::isArray($ref) && isset($ref[$K])) {
			$ref = &$ref[$K];
		    } else {
			return $default;
		    }
		}
	    }
	    
            return $default;
        }

        public static function __callStatic($name, $arguments) {
            return static::getInstance()->get($name);
        }
        
        protected static function _setDefault($class) {
            self::$_instanceClass = $class;
        }
        
        /**
         * 
         * @param string $instance
         * @return static
         */
        public static function getInstance($instance = 'default') {
                if (!isset(self::$instances[$instance])) {
                        self::$instances[$instance] = new static();
                }
                return self::$instances[$instance];
        }
    }

}
