<?php

namespace mnhcc\ml\classes; {

	/**
	 * Combines all the important parameters that are needed for communication 
	 * with the methods of Control class or the View.
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus
	 * @copyright (c) 2013, Michael Hegenbarth
	 */
	class ParmsControl extends MNHcC{

		/**
		 * protected vars
		 * @var string 
		 */
		protected $_type, $_action, $_name, $_attribs;
				
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
		public function __construct(Control $control, $type, $action, $name, array $attribs) {
			list($this->_control, $this->_type, $this->_action, $this->_name, $this->_attribs) = func_get_args();
		}

		/**
		 * get the Control class
		 * @return Control
		 */
		public function getControl() {
			return $this->_control;
		}
		
		/**
		 * Get the same as DocType.
		 * Use the parameter "$search" to look after the value eg in the _attribs['renderType'] or _control->getDefaultDocType().
		 * @param bool $search
		 * @return string the caling type for example return Html
		 */
		public function getType($search = false) {
			$type = $this->_type;
			if($search) {
				if(isset ($this->_attribs['renderType'])) 
					$type = $this->_attribs['renderType'];
				else 
					$type = ( $this->_type ) ? $this->_type : $this->_control->getDefaultDocType();
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
			return $this->_action;
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
			return json_encode([$this->_control, $this->_type, $this->_action, $this->_name, $this->_attribs]);
		}
                public static function isInit() {
                    ;
                }
                public static function getInstance($instance = 'default') {
                     throw new exception\Exception('Call to undefined method ' . $this->getClass() . '::' . __FUNCTION__. '('.Helper::dump($instance).')', exception\Exception::noMethodImplement);
                }
	}

}