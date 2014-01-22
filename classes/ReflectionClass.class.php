<?php

namespace mnhcc\ml\classes;

use \mnhcc\ml\classes\exception as exception;
use \mnhcc\ml\interfaces as interfaces;
use \mnhcc\ml\traits as traits; {
	/**
	 * Description of ReflectionClass
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 * @copyright (c) 2013, Michael Hegenbarth
	 */
	class ReflectionClass extends \ReflectionClass implements interfaces\MNHcC {

		use traits\MNHcC;
		
		public static function replacedMasterClass($class, $to_class, $contains_in_namespace = false, $namespace_limit = null) {
                        
			$names = preg_split('/(?<=.)(?=\p{Lu}\P{Lu})|(?<=\P{Lu})(?=\p{Lu})/U', $class);
			$namespace = false;
			if (strpos($class, NSS) !== false){
				$namespace = array_shift($names);
			}
			$masterClass = array_shift($names);
			if ($contains_in_namespace) {
				if($namespace)
					return str_replace(strtolower($masterClass), strtolower($to_class), $namespace, $namespace_limit) . ucfirst($to_class) . implode($names);
				else
					return NSS. ucfirst($to_class) . implode($names);
			} else {
				return ucfirst($to_class) . implode($names);
			}
		}

                /**
                 * 
                 * @param string $name the method name
                 * @return mixed
                 */
		public function callStatic($name) {
			$args = func_get_args();
                        array_shift($args);
                        $callee = $this->getMethod($name, ClassHandler::addRootNamespace('ReflectionStaticMethod') );
			return $callee->invokeArgs($args);
		}

		public function getReplacedMasterClass($toClass, $namespace = false) {
			return self::replacedMasterClass($this->getName(), $toClass, $namespace);
		}

                /**
                 * Gets a <b>ReflectionMethod</b> for a class method.
                 * @param string $name <p>
                 * The method name to reflect.
                 * </p>
                 * @param string $class
                 * @return ReflectionMethod|null
                 */
                public function getMethod($name, $class = null) {
                    if(func_num_args() > 1) {
                        if(Helper::classExists($class, false, true) && is_subclass_of($class, 'ReflectionMethod') ) {
                            return new $class($this->getName(), $name);
                        }
                    } else {
                        return parent::getMethod($name);
                    }
                    return null;
                }

//		public function getNameCleened() {
//			$name = $this->getName();
//			$parts = explode('\\', $name);
//			$class = array_pop($parts);
//			return $class;
//		}
//		public static function autoload($klasse) {
//			if (self::$basisPfad === null)
//				self::$basisPfad = dirname(__FILE__);
//			if (substr($klasse, 0, 8) !== "SELFHTML")
//				return;
//			if (strpos($klasse, '.') !== false || strpos($klasse, '/') !== false || strpos($klasse, '\\') !== false || strpos($klasse, ':') !== false) {
//				return;
//			}
//
//			$pfad = self::$basisPfad . DIRECTORY_SEPARATOR .
//					join(DIRECTORY_SEPARATOR, $teile) . '.php';
//			if (!file_exists($pfad))
//				return;
//			include_once $pfad;
//		}

	}

}