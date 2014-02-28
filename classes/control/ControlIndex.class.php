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

namespace mnhcc\ml\classes\Control {

    use mnhcc\ml\classes;

    /**
     * Description of Control
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package Tasktus	 
     */
    class ControlIndex extends ControlDefault {

	public function getComponent(classes\ParmsControl $parm) {
	    $view = classes\View::getView($parm->getType(true), __CLASS__);
	    return $view->renderComponent($parm);
	}

	public function actionIndex() {
	    return parent::actionIndex();
	}

	public function onBeforeAction() {
	    parent::onBeforeAction();
	}

    }

}