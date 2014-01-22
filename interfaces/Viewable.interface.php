<?php
namespace mnhcc\ml\interfaces {
	
	/**
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 */
	interface Viewable {

		public function getDataToTemplate($name = "");

		public function Name();
	}
}