<?php

namespace mnhcc\minimaluslayoutilus{
    use mnhcc\ml\classes\Programm;
    define('mnhcc\\ml\\INDEX', true);
    require_once 'vendor/autoload.php';
    require_once './initial.php';
    Programm::getInstance(Programm::DEFAULTINSTANCE)
	    ->runn();
}
