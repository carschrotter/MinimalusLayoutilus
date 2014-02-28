<?php

/*
 * Copyright (C) 2013 Michael Hegenbarth (carschrotter) <mnh@mn-hegenbarth.de>.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301  USA
 */

namespace mnhcc\ml\classes {

    use mnhcc\ml\interfaces,
	mnhcc\ml\traits;

    /**
     * Description of Control
     * Fürt die angeforderten Aktionen aus und gbt sie gegebenenfals an die Views weiter
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	
     * @copyright (c) 2013, Michael Hegenbarth
     */
    abstract class Control extends MNHcC implements interfaces\Actions {

	use traits\Instances,
	    traits\Actions;

	protected $_defaultDocument = 'Html';
	protected $_supportetDoctypes = ['Html'];
	protected $_args = [];

	public function __construct($args) {
	    $this->_args = $args;
	}

//	public static function getInstance($args = null) {
//	    if (!isset(self::$_instances[0])) {
//		Control::$_instances[0] = new static($args);
//	    }
//	    return Control::$_instances[0];
//	}

	
	/**
	 * get
	 * @param \mnhcc\ml\classes\ParmsControl $parms
	 * @return string
	 */
	public function getModul(ParmsControl $parms) {
	    $name = $parms->getName();
	    // todo remove compatibili
	    //old method 
	    $methodType = 'renderModul' . ucfirst($name) . ($parms->getType(true));
	    $method = 'getModul' . ucfirst($name);
	    if (method_exists($this, $methodType)) {
		$action = $parms->getAction();
		return \call_user_func_array([$this, $methodType], [$action, $parms->getAttribs()]);
	    } elseif (method_exists($this, $method)) {
		return \call_user_func_array([$this, $method], [$parms]);
	    } elseif (method_exists($this, 'renderModulDefault')) {
		return \call_user_func_array([$this, 'renderModulDefault'], [$parms, $method]);
	    } else {
		$extp = new Exception\ModulGetterNotFoundException(ucfirst($name), get_class($this), -1);
		throw $extp;
	    }
	}

	/**
	 * Get the rendered Component the main fram of programm.
	 * @param \mnhcc\ml\classes\ParmsControl $parms
	 * @return string
	 */
	public function getComponent(ParmsControl $parms) {
	    $action = $parms->getAction();
	    $method = 'getComponent' . ucfirst($action);
	    try {
		$methodObj = new ReflectionObjectMethod($this, $method);
		return $methodObj->invoke($parms);
	    } catch (Exception\ReflectionMethodException $exc) {
		throw new Exception\ComponentGetterNotCallableException($exc->getClassName(), $exc->getClassName(), -1, $exc);
	    } catch (\ReflectionException $exc) {
		throw new Exception\ComponentGetterNotCallableException($method, get_class($this), -1, $exc);
	    }
	}

	/**
	 * is doctype supportet?
	 * @param string $doctype
	 * @return bool
	 */
	public function supportsDoctype($doctype) {
	    return in_array($doctype, $this->_supportetDoctypes);
	}

	/**
	 * the default doctype for the control (html)
	 * @return string
	 */
	public function getDefaultDocType() {
	    return $this->_defaultDocument;
	}

	/**
	 * Specifies the default module, if no other was found.
	 * @param \mnhcc\ml\classes\ParmsControl $parms
	 * @param string $method the called method as string
	 * @throws Exception\ModulRendererNotFoundException
	 */
	public abstract function renderModulDefault(ParmsControl $parms, $method);

	/**
	 * The default action if no other has been found.
	 * @throws \Exception
	 */
	public abstract function actionDefault($action, $method);
	
	public abstract function getModulDefault($action, $method);

	/**
	 * Index is the default action if no action is specified.
	 */
	public abstract function actionIndex();

	/**
	 * The initial action call after initial Programm.
	 */
	abstract public function onBeforeAction();
	
	abstract public function initialAction();
	/**
	 * 
	 * @param type $name
	 * @param type $arguments
	 * @throws Exception\ComponentRendererNotFoundException
	 * @throws Exception\ModulRendererNotFoundException
	 * @throws \Exception
	 */
	public function __call($name, $arguments) {
	    if (strpos($name, 'getComponent') !== false) {
		throw new Exception\ComponentRendererNotFoundException($name, get_class($this), -1);
	    }
	    if (strpos($name, 'getModul') !== false) {
		throw new Exception\ModulRendererNotFoundException($name, get_class($this), -1);
	    }
	    // default exception
	    throw new \Exception('no Method ' . $name . '() implement in ' . get_class($this), -1);
	}

	/**
	 * raise a Action
	 * @param \mnhcc\ml\classes\Control $control
	 * @param string $action
	 * @return bool
	 * @throws \Exception
	 */
	public static function raiseAction(Control $control, $action) {
	    try {
		(new ReflectionObjectMethod($control, 'action' . $action))->invoke($action);
	    } catch (\Exception $exc) {
		(new ReflectionObjectMethod($control, 'actionDefault'))->invoke($action, $method);
	    }
	}

	/**
	 * 
	 * @param string $name the renderer name get automatet replace to render
	 * @param \mnhcc\ml\classes\ParmsControl $parm
	 * @return string
	 * @throws Exception\ViewNotFoundException
	 */
	protected function render($name, ParmsControl $parm) {
	    $view = View::getView($parm->getType(true), $this->getClass());
	    $renderer = str_replace('get', 'render', $name);
	    $method = new ReflectionObjectMethod($view, $renderer);
	    return $method->invoke($parm);
	}

    }

}