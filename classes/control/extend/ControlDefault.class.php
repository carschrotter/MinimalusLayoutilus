<?php

namespace mnhcc\ml\classes\control\extend;

use mnhcc\ml\classes as classes;
use \mnhcc\ml\traits as traits;
{


	/**
	 * ControlTasktus is the mastercontrol from tasktus programm
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package Tasktus	 
	 */
	abstract class ControlDefault extends classes\Control {
		
		use traits\ComponentDefaultMethods;
		
		public function initialAction($access_check = true) {
			if ($access_check) {
				if (!classes\Helper::checkLogin()) {
					classes\Error::getInstance()->raise(403, null, null, ['Location: '.classes\Parm::getInstance()->requestPath() .'index.php?error=nologin']);
				}
			}
			parent::initialAction();
		}
		
		public function getModulNav(classes\ParmsControl $parm) {
			$base = classes\SERVER::getBase();
			return '<a class="btn btn-smal" href="'.$base.'">Zurück zum Hauptmenü</a>'.n
				.'<a id="logout" class="btn" href="'.$base.'login/logout.json">Abmelden</a>';
		}
		
		
	}

}
