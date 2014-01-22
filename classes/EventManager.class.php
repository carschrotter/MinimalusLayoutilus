<?php
namespace mnhcc\ml\classes; {

    /**
     * Description of EventManager
     *
     * @author carschrotter
     */
    abstract class EventManager {
	static protected $_events =[];
	/**
	 * 
	 * @param string $name
	 * @param \mnhcc\ml\classes\EventParms $parms
	 */
	static public function raise($name, EventParms $parms) {
	    $cName = self::cleanEventName($name);
		if(isset(self::$_events[$cName])) {
		foreach (self::$_events[$cName] as $index => $event) {
		    $event->raise($parms, $index);
		}
	    }
	}
	
	static public function cleanEventName($name) {
	    $cleanEventName = \preg_replace("~^on~i", '', strtolower($name));
	    return \ucfirst($cleanEventName);
	}
	
	/**
	 * 
	 * @param \mnhcc\ml\classes\Event $event
	 */
	static public function register(Event $event) {
	    return self::$_events[$event->getEventName()][] = $event;
	}
    }
}