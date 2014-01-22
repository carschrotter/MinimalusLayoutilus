<?php

namespace mnhcc\ml\traits;
use mnhcc\ml\classes\exception as exception;
{

    /*
     * Caller
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    trait Caller {

        protected $_objFunction = [];
        protected static $_staticFunction = [];

        /**
         * replace the default Error to a Exeption
         * @param string $name
         * @param array $arguments
         * @throws Exception
         */
        public function __call($name, $arguments) {
            switch (true) {
                case ( $name === 'setFunction' ):
                    if (is_callable($arguments[1])) {
                        $this->_objFunction[$arguments[0]] = $arguments[1];
                        return true;
                    } else {
                        throw new exception\Exception('parm is not callable');
                    }
                    return false;
                    break;
                case ( key_exists($name, $this->_objFunction) ):
                    return call_user_func_array($this->_objFunction[$name], $arguments);
                    break;
                case ( method_exists($this, '__default' . ucfirst($name)) ):
                    $method = (new \mnhcc\ml\classes\ReflectionObjectMethod($this, '__default' . ucfirst($name)));
                    $method->setAccessible(true);
                    return $method->invokeArgs($arguments);
                    break;
                case ( method_exists(get_parent_class(), '__call') ):
                    return parent::__call($name, $arguments);
                    break;
                 default:    
                    break;
            }
        }

        public static function __callStatic($name, $arguments) {
            switch (true) {
                case ( $name === 'setFunction' ):
                    if (is_callable($arguments[0])) {
                        self::$_staticFunction[$arguments[1]] = $arguments[0];
                        return true;
                    } else {
                        throw new exception\Exception(gettype($arguments[0]) . ' is not callable');
                    }
                    return false;
                    break;
                case ( key_exists($name, self::$_staticFunction) ):
                    return call_user_func_array(self::$_staticFunction[$name], $arguments);
                    break;
                case ( method_exists(get_called_class(), '__default' . ucfirst($name)) ):
                    $method = (new \ReflectionMethod(get_called_class(), '__default' . ucfirst($name)));
                    $method->setAccessible(true);
                    return $method->invokeArgs(null, $arguments);
                    break;
                case ( method_exists(get_parent_class(), '__call') ):
                    return parent::__callStatic($name, $arguments);
               default:    
                    break;
            }
        }

    }

}
