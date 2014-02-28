<?php
namespace mnhcc\example{
    use mnhcc\ml\classes,
	mnhcc\ml\classes\Programm;
    define('mnhcc\\ml\\INDEX', true);
    require_once './initial.php';
    Programm::getInstance(Programm::DEFAULTINSTANCE)
	    ->runn();
}