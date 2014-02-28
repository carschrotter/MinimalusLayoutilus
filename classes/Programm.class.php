<?php

namespace mnhcc\ml\classes {

    use mnhcc\ml\traits as traits;

    /**
     * Description of Programm
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     * @method static getInstance() return the instance (object) from class
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class Programm extends MNHcC {

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
	 * @var \mnhcc\ml\classes\Template 
	 */
	protected $_template = null;

	/**
	 *
	 * @var \mnhcc\ml\classes\Parm 
	 */
	private $_parm = null;

	/**
	 * get the control object
	 * @return \mnhcc\ml\classes\Control
	 */
	public function getControl() {
	    return $this->_control;
	}

	/**
	 * get the action name
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

	/**
	 * get the contoll class name
	 * @return string
	 */
	public function getControlClass() {
	    return $this->_controlClass;
	}

	/**
	 * get the parameters from Routing
	 * @return \mnhcc\ml\classes\Parm
	 */
	protected function getParm() {
	    return $this->_parm;
	}

	/**
	 * @see getParm()
	 * @return \mnhcc\ml\classes\Parm
	 */
	protected function getParameters() {
	    return $this->getParm();
	}

	/**
	 * set the Parameter from Routing
	 * @param \mnhcc\ml\classes\Parm $parm
	 * @return \mnhcc\ml\classes\Programm
	 */
	protected function setParm(Parm $parm) {
	    $this->_parm = $parm;
	    return $this;
	}

	/**
	 * set the controlClass
	 * @param string $controlClass
	 * @return \mnhcc\ml\classes\Programm
	 */
	public function setControlClass($controlClass) {
	    $this->_controlClass = $controlClass;
	    return $this;
	}

	/**
	 * set the Template
	 * @param \mnhcc\ml\classes\Template $template
	 * @return \mnhcc\ml\classes\Programm
	 */
	protected function setTemplate(Template $template) {
	    $this->_template = $template;
	    $this->_templateLoaded = true;
	    return $this;
	}

	/**
	 * set the name of called action
	 * @param string $action
	 * @return \mnhcc\ml\classes\Programm
	 */
	public function setAction($action) {
	    $this->_action = $action;
	    return $this;
	}

	/**
	 * set the control object
	 * @param \mnhcc\ml\classes\Control $control
	 * @return \mnhcc\ml\classes\Programm
	 */
	public function setControl(Control $control) {
	    $this->_control = $control;
	    $this->_controlLoaded = true;
	    return $this;
	}

	/**
	 * is the template object createt and loaded
	 * @return bool
	 */
	public function isTemplateLoaded() {
	    return (bool) $this->_templateLoaded;
	}

	/**
	 * is the control object createt and loaded
	 * @return bool
	 */
	public function isControlLoaded() {
	    return (bool) $this->_controlLoaded;
	}

	public function __construct($instance = self::DEFAULTINSTANCE) {
	    self::$_instances[$instance] = & $this;
	}

	public function runn($parms = []) {
	    $this->setParm(Router::getInstance()->getParm($parms));
	    $control = $this->getParm()->getControl('index', true);
	    $this->setAction($this->getParm()->getAction('index', true));

	    $this->setControlClass(BootstrapHandler::makeClassName(
			    BootstrapHandler::getRootNamespace(), BootstrapHandler::getClassNamespaceRoot(), 'Control', 'Control' . $control));

	    $this->docType = Template::getDocTypeFromExt($this->_parm->getExtention('html'));

	    $root_template = BootstrapHandler::addRootNamespace(BootstrapHandler::makeClassName('template', 'Template'));

	    $template = $root_template . ucfirst($this->docType);

	    if (Helper::classExists($template, false, true)) {
		$this->setTemplate((new ReflectionClass($template))->newInstance());
	    }

	    if (Helper::classExists($this->_controlClass, false, true)) {
		$this->setControl((new ReflectionStaticMethod($this->_controlClass, 'getInstance'))
			->invoke(Control::DEFAULTINSTANCE, 
				\mnhcc\ml\interfaces\Instances::INSTANCE_NOT_OVERIDE, 
				[[$this->docType, 
				    $this->_controlClass, 
				    $this->_action]]));
	    }

	    if ($this->isControlLoaded()) {
		try {
		    $this->_control->onBeforeAction();
		    Control::raiseAction($this->_control, $this->_action);
		} catch (\Exception $exc) {
		    $this->msg($exc->getMessage(), 'error', 'No Action');
		}
	    }
	    if ($this->isTemplateLoaded() && $this->isControlLoaded()) {
		if (!$this->getControl()->supportsDoctype($this->docType)) {
		    Error::getInstance()
			    ->raise(404, '"' . $this->docType
				    . '" doctype is not supported by the control "'
				    . $this->getParm()->getControl('index', true) . '"', '', $this->getTemplate());
		}
	    } elseif (!$this->isTemplateLoaded()) {
		if ($this->isControlLoaded()) {
		    $template = $root_template . ucfirst($this->getControl()
			    ->getDefaultDocType());
		} else {
		    $template = $root_template . ucfirst(Template::getDefDocType());
		}
		$this->setTemplate( (new ReflectionStaticMethod($template, 'GetInstance'))
			->invoke() );
		Error::getInstance()
			->raise(404, 'No template that supports the "' . $this->docType . '" doctype', '', $this->getTemplate());
	    } elseif (!$this->isControlLoaded()) {
		Error::getInstance()
			->raise(404, 'Controll for "' . $this->_parm->getControl() . '" not found!<br>No Class: ' . $this->_controlClass);
	    }

	    echo $this->_template->render();
	}

	public function get($type, $name, $attribs) {
	    $returnvar = false;
	    if ($type == 'system') {
		return $this->getSystem();
	    } else {
		if ($this->isControlLoaded()) {
		    try {
			$method = 'get' . ucfirst($type);
			return (new ReflectionObjectMethod($this->getControl(), $method))
				->invoke(new ParmsControl($this, $name, $attribs));
		    } catch (Exception\RenderException $renderException) {
			$this->msg($renderException->getMessage(), 'error', 'Modul not found!');
			return '';
			//Error::getInstance()->raise(404, $renderException);
			//throw $renderException;
		    }
		} elseif ($type == 'component') {
		    $exc = new Exception\RenderException('No Control "' . $this->_controlClass . '" found', -1);
		    Error::getInstance()->raise(404, $exc);
		    throw $exc;
		}
	    }
	    return $returnvar;
	}

	protected function getSystem() {
	    return $this->getTemplate()->renderMsg($this->_msg);
	}

	public function msg($msg, $type = 'info', $heading = null) {
	    $this->_msg[] = ['msg' => $msg, 'type' => $type, 'heading' => $heading];
	}

    }

}