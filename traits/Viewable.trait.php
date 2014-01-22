<?php

namespace mnhcc\ml\traits {
	/**
	 * Description of Viewable
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 */
	trait Viewable {

		public function getDataToTemplate($name = "") {
			$name = ($name) ? $name : "view." . $this->Name() . ".php";
			require "templates/" . $name;
		}

		public function Name() {
			return get_class($this);
		}

	}
}