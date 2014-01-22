<?php

namespace mnhcc\ml\classes\control;

use mnhcc\ml\classes as classes;
{

	/**
	 * Description of Control
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package Tasktus	 
	 */
	class ControlIndex extends extend\ControlDefault {

		public function getModulModul7(classes\ParmsControl $parm) {
			$view = classes\View::getView($parm->getType(true), __CLASS__);
			return $view->getModulModul7($parm);
		}

		public function getComponent(classes\ParmsControl $parm) {
			$view = classes\View::getView($parm->getType(true), __CLASS__);
			return $view->renderComponent($parm);
		}

		public function actionIndex() {
			return;
		}
		
		public function initialAction($access_check = true) {
			return;
		}

	}

}