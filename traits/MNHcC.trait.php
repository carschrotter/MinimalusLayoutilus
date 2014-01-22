<?php

namespace mnhcc\ml\traits;
use mnhcc\ml\classes\exception as exception;
use mnhcc\ml\classes as classes;
{
	/*
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 */
	trait MNHcC {

		protected static $___require = [];
		
		public function __toString() {
			return $this->getClass();
		}

		public function getClass() {
			return get_class($this);
		}

		/**
		 * replace the default Error to a Exeption
		 * @param string $name
		 * @param array $arguments
		 * @throws Exception
		 */
		public function __call($name, $arguments) {
			throw new exception\Exception('Call to undefined method ' . $this->getClass() . '::' . $name. '()', exception\Exception::noMethodImplement);
		}

		public static function __callStatic($name, $arguments) {
			throw new exception\Exception('Call to undefined method ' . __CLASS__ . '::' . $name. '()', exception\Exception::noStaticMethodImplement);
		}
                
                public static function ___onLoaded() {
                    classes\Error::triggerError(self::getCalledClass()."::___onLoaded() was not explicitly implemented", E_USER_NOTICE);
                }
                
                public static function getCalledClass() {
                   return get_called_class();
                }

	}

}
