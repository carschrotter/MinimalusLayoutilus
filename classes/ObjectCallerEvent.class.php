<?php

namespace mnhcc\ml\classes;
use mnhcc\ml\interfaces as interfaces;
use mnhcc\ml\traits as traits;
{

    /**
     * Description of Event
     * 
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class ObjectCallerEvent extends ReflectionMethod implements interfaces\Event {

	use traits\Event;
	
	
	/**
	 * @var  \mnhcc\ml\classes\ReflectionClass 
	 */
	protected $_object = null;
	protected $_parms = [];

	public function __construct($class, $name) {
	    if (is_object($class)) {
		$this->_object = $class;
	    }
	    parent::__construct($class, $name);
	}

	public function getObject() {
	    if ($this->isStatic())
		return null;
	    if ($this->_object == null) {
		$class = new ReflectionClass($this->getClass());
	    }
	    return $this->_object;
	}

	public function raise($parms = []) {
	    $this->addParms($parms, true);
	    return $this->invokeArgs($this->getObject(), $this->getParms());
	}

    }

}