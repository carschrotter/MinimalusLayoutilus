<?php

namespace mnhcc\ml\classes {

    use \mnhcc\ml\traits,
     \mnhcc\ml\interfaces;

    /**
     * Combines all the important parameters that are needed for communication 
     * with the methods of Control class or the View.
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class ParmsControl extends MNHcC implements interfaces\Instances{

	use traits\NoInstances;

	/**
	 * protected vars
	 * @var string 
	 */
	protected $_name, $_attribs;

	/**
	 * @var Control 
	 */
	protected $_control;

	
	
	
	/**
	 * @param Control $control
	 * @param string $type
	 * @param string $action
	 * @param string $name
	 * @param array $attribs
	 */
	public function __construct(Programm $programm, $name, array $attribs) {
	    $this->_programm = $programm;
	    $this->_name = $name;
	    $this->_attribs = $attribs;
	    //list($this->getControl(), $this->_type, $this->_action, $this->_name, $this->_attribs) = func_get_args();
	}
	
	/**
	 * get the Control class
	 * @return Programm
	 */
	public function getProgramm() {
	    return $this->_programm;
	}
	
	/**
	 * get the Control class
	 * @return Control
	 */
	public function getControl() {
	    return $this->_programm->getControl();
	}

	/**
	 * Get the same as DocType.
	 * Use the parameter "$search" to look after the value eg in the _attribs['renderType'] or _control->getDefaultDocType().
	 * @param bool $search
	 * @return string the caling type for example return Html
	 */
	public function getType($search = false) {
	    $type = $this->getProgramm()->getTemplate()->getDocType();
	    if ($search) {
		$serachtype = $type = ArrayHelper::get('renderType', $this->_attribs, 
			function() {
			    $type = $this->getProgramm()->getTemplate()->getDocType();
			    return ($type) ?
				    $type :
				    $this->getControl()->getDefaultDocType();
			}, true);
		$type = ucfirst($type);
	    }
	    return $type;
	}

	/**
	 * Get the requested action from Bootstraping in Program.
	 * <code>
	 * <?php
	 * $parmsControl->getAction(); //index for example;
	 * ?>
	 * </code>
	 * @return string
	 */
	public function getAction() {
	    return $this->_programm->getAction();
	}

	/**
	 * Get the requested name of control from Bootstraping in Program.
	 * <code>
	 * <?php
	 * $parmsControl->getName(); //return index for example;
	 * ?>
	 * </code>
	 * @return string
	 */
	public function getName() {
	    return $this->_name;
	}

	/**
	 * Get the attribute for the element from the Template.
	 * @return array
	 */
	public function getAttribs() {
	    return $this->_attribs;
	}

	public function __toString() {
	    return json_encode([$this->getControl(), $this->_type, $this->_action, $this->_name, $this->_attribs]);
	}

    }

}