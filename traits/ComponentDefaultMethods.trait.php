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

namespace mnhcc\ml\traits {

    use \mnhcc\ml\classes;
	//\mnhcc\ml\classes\Exception;

    /**
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    trait ComponentDefaultMethods {

	public function renderModulDefault(classes\ParmsControl $parms, $method) {
	    throw new \mnhcc\ml\classes\Exception\ModulRendererNotFoundException($method, $this->getClass(), -1);
	}
	
	/**
	 * default action
	 * @throws mnhcc\ml\classes\Exception
	 */
	public function actionDefault($action, $method) {
	    throw new \mnhcc\ml\classes\Exception('no Method ' . $method . '() implement in ' . $this->getClass(), -1);
	}

    }

}