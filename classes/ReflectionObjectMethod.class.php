<?php

namespace mnhcc\ml\classes;

use \mnhcc\ml\classes\exception as exception;
use \mnhcc\ml\interfaces as interfaces;
use \mnhcc\ml\traits as traits;
{

    /**
     * ReflectionObjectMethod is a warpper for methods of objects, 
     * you can call method dynamical from the name (string)
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class ReflectionObjectMethod extends \ReflectionMethod implements interfaces\MNHcC {

        use traits\MNHcC;

        /**
         * is a default value
         * vor invoke method
         */

        const defaultArg = '{defaultArg : "true", secure : "Ay0keRT1l8"}';

        protected $object;

        /**
         * @param object $object
         * @param string $name
         * @throws \Exception
         */
        public function __construct($object, $name) {
            if (!is_object($object)) {
                throw new exception\Exception('Argument 1 passed to ' . __CLASS__ . '::' . __METHOD__ . ' must be an object , ' . gettype($object) . ' given', -1);
            }
            try {
                parent::__construct($object, $name);
            } catch (\Exception $exc) {
                throw new exception\ReflectionMethodException($object, $name, $exc->getCode(), $exc);
            }
            $this->object = $object;
        }

        public function __invoke() {
            return self::invokeArgs($this->object, func_get_args());
        }

        /**
         * invokes a static method on the class of the object
         * @return mixed the result of method
         */
        public function invokeStatic() {
            return self::invokeArgs(null, func_get_args());
        }

        /**
         * invokes a method on the object
         * @param mixed $parameter (optional) Zero or more parameters to be passed to the method. It accepts a variable number of parameters which are passed to the method.
         * @param mixed $_ (optional)
         */
        public function invoke() {
            return self::invokeArgs($this->object, func_get_args());
        }

        /**
         * set another object of the same type.
         * @param object $object
         * @return boolean 
         */
        public function setObject($object) {
            if (is_a($object, $this->class)) {
                $this->object = $object;
                return true;
            } else {
                //@ToDo implement a exeption
                return false;
            }
        }

    }

}
