<?php

namespace mnhcc\ml\traits;
use mnhcc\ml\classes\exception as exception;
{

    /*
     * Caller
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    trait Event {
	protected $_parms = [];
	protected $_eventName = false;
	
	public function getEventName() {
	    return $this->_eventName;
	}
	
	public function getParms() {
	    return $this->_parms;
	}

	protected function setParms($parms) {
	    if (!Helper::isArray($parms)) {
		throw new exception\Exceptionon(__CLASS__ . '::' . __FUNCTION__ . 'no Array given', 0);
	    }
	    $this->_parms = $parms;
	}

	public function addParms($parms, $overide = false) {
	    if (!Helper::isArray($parms)) {
		throw new exception\Exceptionon(__CLASS__ . '::' . __FUNCTION__ . 'no Array given', 0);
	    }
	    if ($overide) {
		$this->_parms = Helper::arrayMerge($this->_parms, $parms);
	    } else {
		$this->_parms = Helper::arrayMerge($parms, $this->_parms);
	    }
	}
    }

}
