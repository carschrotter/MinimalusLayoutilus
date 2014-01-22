<?php
namespace mnhcc\ml\interfaces {
	
	/**
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 */
	interface Event {
	    public function raise(\mnhcc\ml\classes\EventParms $eparm);
	    public function getParms();
	    public function setParms($parms);
	    public function addParms($parms, $overide = false);
	    public function getEventName();
	}
}