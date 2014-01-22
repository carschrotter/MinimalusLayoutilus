<?php

namespace mnhcc\ml\interfaces {

    /**
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    interface Parameters {

	public function getParms();

	public function get($key, $default = null);

	public function is($key, $check = true, $method = false);
    }

}