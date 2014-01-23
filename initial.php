<?php

/**
 * The placeholder project. A simple page relesed whit MinimalusLayoutilus
 */

namespace mnhcc\ml;

if(!\defined("INDEX")) die();

use mnhcc\ml\classes as classes; {
    define('STARTTIME', \microtime(true));
    ini_set("log_errors", 1);
    ini_set("error_log", realpath('./php-error.log.php'));
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    global $config;
    $config = [];
    session_start();
    ini_set("display_errors", false);
    
    if (!defined('MNHCC_PATH') && !defined('ROOT_PATH') ) {
	die('Define constant "NHCC_PATH" and "ROOT_PATH"!' );
    }
    /**
     * NameSpace Seperator
     */
    define("NSS", '\\');
    /**
     * line break
     */
    define("n", PHP_EOL);
    define("br", "<br />".n);
    define("php", ".php");
    define("DS", DIRECTORY_SEPARATOR);

    if (file_exists(ROOT_PATH.DS.'load.php')) {
        $config = include ROOT_PATH.DS.'load.php';
	$config = is_scalar($config) ? [] : $config;
    }
    if (file_exists(ROOT_PATH.DS.'config.json')) {
        $config = array_merge((array) $config, (array)  json_decode(file_get_contents(ROOT_PATH.DS.'config.json'))) ;
    }
    
    require_once MNHCC_PATH . DS . 'classes' . DS . 'ClassHandler.class' . php;

    classes\ClassHandler::addExtensionDependency('PDO');
    classes\ClassHandler::initial();
    
    define('APLICATIONNAMESPACE', 
            (key_exists('provider', $config) && key_exists('aplication', $config)) ?
            classes\ClassHandler::makeClassName($config['provider'], $config['aplication']) :
            NULL
        );
    
    classes\ClassHandler::setErrorHandler(classes\Error::getInstance());

    $ConfigClass = APLICATIONNAMESPACE ? 
            classes\ClassHandler::makeClassName(APLICATIONNAMESPACE, 'Config') :
            classes\ClassHandler::addRootNamespace('Config');
    
    define('CONFIG', $ConfigClass);
    classes\ClassHandler::setDefineLocation(CONFIG, ROOT_PATH . DS . 'Config' . php);
    classes\ClassHandler::Load(__NAMESPACE__."\\classes\\exception\\Exception");
    
    //classes\ClassHandler::addDependence('interface', 'FOOBAR');
    //classes\ClassHandler::addDependence('extension', 'PHPEXTENSION');
    /**
     * initial the config
     * @global Config $config
     */
    

    $config->paths['root'] = ROOT_PATH;
    classes\ClassHandler::loadLibrary('FirePHP');
    //classes\ClassHandler::Load(classes\ClassHandler::addRootNamespace('Kint') );
    //classes\Helper::setFunction((new classes\ReflectionStaticMethod(classes\ClassHandler::addRootNamespace('Kint'), 'dump')), 'dump');
    //classes\Kint::defaultModifier('@');
}