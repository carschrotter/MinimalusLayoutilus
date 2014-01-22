<?php

namespace mnhcc\ml\classes;

use mnhcc\ml\interfaces as interfaces;
use mnhcc\ml\traits as traits; {

    /**
     * Default class for classes in mnhcc namespace implement this functions
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2012, Michael Hegenbarth
     * @license GPL  
     */
    abstract class MNHcC implements interfaces\MNHcC, interfaces\Instances {

	use traits\MNHcC;

	public static function ___onLoaded() {
	    
	}

	public static function ___require() {
	    return self::$___require;
	}

    }

}
