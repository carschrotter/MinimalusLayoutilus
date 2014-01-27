<?php
namespace mnhcc\example {
    define('INDEX', true);
     require_once './initial.php';
     \mnhcc\ml\classes\Programm::getInstance(DEFAULTINSTANCE)->runn();
}