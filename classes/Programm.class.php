<?php

namespace mnhcc\ml\classes;

use mnhcc\ml\traits as traits; {


	/**
	 * Description of Programm
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 * @method static getInstance() return the instance (object) from class
	 * @copyright (c) 2013, Michael Hegenbarth
	 */
	class Programm extends MNHcC{

		use traits\Instances;

		/**
		 *
		 * @var bool 
		 */
		protected $_templateLoaded = false;

		/**
		 *
		 * @var bool 
		 */
		protected $_controlLoaded = false;

		/**
		 *
		 * @var array  
		 */
		protected $_msg = [];

		/**
		 *
		 * @var string 
		 */
		protected $_action = '';

		/**
		 *
		 * @var Control 
		 */
		protected $_control = null;

		/**
		 * The class name from Control object
		 * @var string 
		 */
		protected $_controlClass = '';

		/**
		 *
		 * @var Template 
		 */
		protected $_template = null;

		/**
		 *
		 * @var Parm 
		 */
		private $parm = null;

		/**
		 * 
		 * @return \mnhcc\ml\classes\Control
		 */
		public function getControl() {
			return $this->_control;
		}

		/**
		 * 
		 * @return string
		 */
		public function getAction() {
			return $this->_action;
		}

		/**
		 * 
		 * @return \mnhcc\ml\classes\Template
		 */
		public function getTemplate() {
			return $this->_template;
		}

		protected function setTemplate(Template $template) {
			$this->_template = $template;
		}

		public function __construct($instance = 'default') {
			self::$instances[$instance] = & $this;
		}

		public function isTemplateLoaded() {
			return $this->_templateLoaded;
		}

		public function isControlLoaded() {
			return $this->_controlLoaded;
		}

		public function runn($parms = []) {
			$this->parm = Parm::getInstance();
			$control = $this->parm->getControl('Index', true);
		 	$this->_action = $this->parm->getAction('index', true);
                        
                        
            $this->_controlClass = ClassHandler::makeClassName(
                    ClassHandler::getRootNamespace(), 
                    ClassHandler::getClassNamespaceRoot(),
                    'control',
					'Control'.$control);
				
			$this->docType = Template::getDocTypeFromExt($this->parm->getExtention('html'));
			
            $roottemplate = ClassHandler::addRootNamespace(ClassHandler::makeClassName('template', 
					'Template'));
                        
            $template = $roottemplate . ucfirst( $this->docType );
                        
			if (Helper::classExists($template, false, true)) {
				$this->_template = (new ReflectionClass($template))->newInstance();
				$this->_templateLoaded = true;
			}
                        
			if (Helper::classExists($this->_controlClass, false, true)) {
				$this->_control = (new ReflectionClass($this->_controlClass))->newInstance(
					[[$this->docType, $this->_controlClass, $this->_action]]);
				$this->_controlLoaded = true;
			}
			
			if ($this->isControlLoaded()) {
				try {
					$this->_control->initialAction();
					Control::raiseAction($this->_control, $this->_action);
				} catch (\Exception $exc) {
					$this->msg($exc->getMessage(), 'error', 'No Action');
				}
			}
			if ($this->isTemplateLoaded() && $this->isControlLoaded()) {
				if (!$this->getControl()->supportsDoctype($this->docType)) {
					Error::getInstance()->raise(404, '"' . $this->docType . '" doctype is not supported by the control "' . $this->parm->getControl('Index', true) . '"', '', $this->getTemplate());
				}
			} elseif (!$this->isTemplateLoaded()) {
				if ($this->isControlLoaded())
					$template = $roottemplate . ucfirst($this->_control->getDefaultDocType());
				else
					$template = $roottemplate . ucfirst(Template::getDefDocType());
				$this->_template = new $template();
				$this->_templateLoaded = true;
				Error::getInstance()->raise(404, 'No template that supports the "' . $this->docType . '" doctype', '', $this->getTemplate());
			} elseif (!$this->isControlLoaded()) {
				Error::getInstance()->raise(404, 'Controll for "' . $this->parm->getControl('Index', true) . '" not found!<br>No Class: '.$this->_controlClass);
			}

			echo $this->_template->render();
		}

		public function get($type, $name, $attribs) {
			$returnvar = false;
			if ($type == 'system') {
				return $this->getTemplate()->renderMsg($this->_msg);
			} else {
				if ($this->_controlLoaded) {
					try {
						$method = 'get' . ucfirst($type);
						$methodObj = new ReflectionObjectMethod($this->_control, $method);
						return $methodObj->invoke(new ParmsControl($this->_control, $this->getTemplate()->getDocType(), $this->_action, $name, $attribs));
					} catch (exception\RenderException $exc) {
						Error::getInstance()->raise(404, $exc);
						throw $exc;
					}
				} elseif ($type == 'component') {
					$exc = new exception\RenderException('No Control "' . $this->_controlClass . '" found', -1);
					Error::getInstance()->raise(404, $exc);
					throw $exc;
				}
			}
			return $returnvar;
		}

		public function msg($msg, $type = 'info', $heading = null) {
			$this->_msg[] = ['msg' => $msg, 'type' => $type, 'heading' => $heading];
		}

	}

}