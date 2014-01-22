<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mnhcc\classes {

    /**
     * Description of Array
     *
     * @author carschrotter
     */
    class ArrayHelper extends MNHcC{
	
	static public function pop(&$array) {
            return \array_pop($array);
	}
	
	static public function shift(&$array) {
	    return \array_shift($array); 
	}
	static public function count(&$array) {
	    return \count($array); 
	}
    }
}
