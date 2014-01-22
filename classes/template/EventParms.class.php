<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace mnhcc\ml\classes\template;
use \mnhcc\ml\classes as classes;
/**
 * Description of EventParms
 *
 * @author carschrotter
 */
class EventParms extends \mnhcc\ml\classes\EventParms{

    public function __construct(classes\Template $template, $parms = []) {
	parent::__construct($parms);
	$this->_parms['template'] = $template;
    }
    /**
     * get the Template instance
     * @return mnhcc\ml\classes\Template
     */
    public function getTemplate() {
	return $this->get('template');
    }
}
