<?php

namespace mnhcc\ml\traits;

use \mnhcc\ml\classes as classes; 
use mnhcc\ml\classes\exception as exception;
{

	/**
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 */
	trait ComponentDefaultMethods {

		public function renderModulDefault(classes\ParmsControl $parms, $method) {
			throw new exception\ModulRendererNotFoundException($method, $this->getClass(), -1);
		}

		/**
		 * default action
		 * @throws mnhcc\ml\classes\exception\Exception
		 */
		public function actionDefault($action, $method) {
			throw new exception\Exception('no Method '.$method.'() implement in ' . $this->getClass(), -1);
		}

	}

}