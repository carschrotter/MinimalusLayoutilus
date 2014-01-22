<?php

namespace mnhcc\ml\classes;

use \mnhcc\ml\classes\exception as exception;
use \mnhcc\ml\interfaces as interfaces;
use \mnhcc\ml\traits as traits; {

    /**
     * ReflectionObjectMethod is a warpper for methods of objects, 
     * you can call method dynamical from the name (string)
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class ReflectionStaticMethod extends \ReflectionMethod implements interfaces\MNHcC {

        use traits\MNHcC;

        public function __construct($class, $name) {
            parent::__construct($class, $name);
            if (!$this->isStatic()) {
                throw new exception\Exception('Method is not Static');
            }
        }

        public function __invoke() {
            if (func_num_args() > 0) {
                return self::invokeArgs(func_get_args());
            } else {
                return self::invoke();
            }
        }

        /**
         * invokes a static method on the class of the object
         * @return mixed the result of method
         */
        public function invokeArgs(array $args) {
            return parent::invokeArgs(null, $args);
        }

        /**
         * invokes a method on the object
         * @param mixed $parameter (optional) Zero or more parameters to be passed to the method. It accepts a variable number of parameters which are passed to the method.
         * @param mixed $_ (optional)
         */
        public function invoke() {
            if (func_num_args() > 0) {
                return self::invokeArgs(func_get_args());
            } else {
                return parent::invoke(null);
            }
        }

    }

}
