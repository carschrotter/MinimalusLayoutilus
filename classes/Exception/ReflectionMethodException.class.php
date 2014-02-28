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

namespace mnhcc\ml\classes\Exception {

    use \mnhcc\ml,
	\mnhcc\ml\classes,
	\mnhcc\ml\traits,
	\mnhcc\ml\interfaces,
	\mnhcc\ml\classes\Exception;

    /**
     * Description of ReflectionMethodException
     * 
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 */
    class ReflectionMethodException extends \ReflectionException implements interfaces\MNHcC, interfaces\Exception {

	use traits\MNHcC;

	protected $_className = false;
	protected $_methodName = false;

	/**
	 * 
	 * @param string $method the name of the called method
	 * @param string $class the name of the class
	 * @param int $code
	 * @param \Exception $previous
	 */
	public function __construct($method, $class, $code, $previous) {
	    $message = 'no Method ' . $method . '() implement in ' . $class;
	    parent::__construct($message, $code, $previous);
	    $this->_methodName = $method;
	    $this->_className = $class;
	}

	public function __toString() {
	    return $this->getMessage();
	}

	public function getClassName() {
	    return $this->_className;
	}

	public function getMethodName() {
	    return $this->_methodName;
	}

    }

}