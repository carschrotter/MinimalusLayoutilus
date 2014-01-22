<?php

namespace mnhcc\ml\interfaces {

	/**
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 */
	interface Instances {
            public static function getInstance($instance = 'default');
            
            /**
              *  is a object createt also init
              * @return bool
              */
             public static function isInit();
            
	}

}