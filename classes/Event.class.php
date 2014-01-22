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
    class Event implements interfaces\Event {

	use traits\Event;
	/**
	 * @var callable
	 */
	protected $_callback = null;

	/**
	 * 
	 * @param callable $callback
	 * @param string $event the event name
	 */
	public function __construct($callback, $event) {
	    $this->_eventName = EventManager::cleanEventName($event);
	    if (is_callable($callback)) {
		$this->_callback = $callback;
	    } else {
		throw new exception\Exception('$callback ('.gettype($callback).') is not callable!' );
	    }
	}
	
	public function getCallback() {
	    return $this->_callback;
	}

	/**
	 * 
	 * @param \mnhcc\ml\classes\EventParms $eparm
	 * @return type
	 */
	public function raise(EventParms $eparm) {
	    return \call_user_func($this->getCallback(), $eparm);
	}

    }

}