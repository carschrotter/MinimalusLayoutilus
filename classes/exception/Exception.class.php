<?php

namespace mnhcc\ml\classes\exception;

use mnhcc\ml\interfaces as interfaces; 
use mnhcc\ml\traits as traits; 
{

	class Exception extends \Exception implements \JsonSerializable, interfaces\MNHcC, interfaces\Exception {
		use traits\Exception;
		public static function ___onLoaded() {
		    //parent::___onLoaded();
		}
	}

}