<?php

/**
 * The placeholder project. A simple page relesed whit MinimalusLayoutilus
 */

namespace mnhcc\ml;

$version = '5.4.0';
if (!version_compare(PHP_VERSION, $version, '>=')) {
    die('The program requires PHP ' . $version . ' or higher! PHP ' . PHP_VERSION . '  installed.');
}
if (!\defined("INDEX"))
    die('Define constant "INDEX" befor run Programm!');

use mnhcc\ml\classes as classes;
{
    define('STARTTIME', \microtime(true));
    ini_set("log_errors", 1);
    ini_set("error_log", realpath('./php-error.log.php'));
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    global $config;
    $config = [];
    session_start();
    ini_set("display_errors", false);


    /**
     * NameSpace Seperator
     */
    define("NSS", '\\');
    /**
     * line break
     */
    define("n", PHP_EOL);
    define("br", "<br />" . n);
    define("php", ".php");
    define("DS", DIRECTORY_SEPARATOR);

    if (!defined('MNHCC_PATH')) {
	define("MNHCC_PATH", __DIR__);
    }
    if (!defined('ROOT_PATH')) {
	define("ROOT_PATH", __DIR__);
    }

    //die('Define constant "NHCC_PATH" and "ROOT_PATH"!' );
    foreach (['load', 'config', '..' . DS . 'config'] as $filename) {
	if (file_exists(ROOT_PATH . DS . $filename . '.php')) {
	    $config = include ROOT_PATH . DS . $filename . '.php';
	    $config = is_scalar($config) ? [] : $config;
	}
    }
    if (file_exists(ROOT_PATH . DS . 'config.json')) {
	$config = array_merge((array) $config, (array) json_decode(file_get_contents(ROOT_PATH . DS . 'config.json')));
    }

    if (!file_exists(MNHCC_PATH . DS . 'classes' . DS . 'ClassHandler.class' . php)) {
	die('No ClassHandler found!'.n.'Check constant "MNHCC_PATH"');
    }

    require_once(MNHCC_PATH . DS . 'classes' . DS . 'ClassHandler.class' . php);
    
    classes\ClassHandler::addExtensionDependency('PDO');
    classes\ClassHandler::initial();

    define('APPLICATIONNAMESPACE', (key_exists('provider', $config) && key_exists('aplication', $config)) ?
		    classes\ClassHandler::makeClassName($config['provider'], $config['aplication']) :
		    NULL
    );

    classes\ClassHandler::setErrorHandler(classes\Error::getInstance());

    $ConfigClass = APPLICATIONNAMESPACE ?
	    classes\ClassHandler::makeClassName(APPLICATIONNAMESPACE, 'Config') :
	    classes\ClassHandler::addRootNamespace('Config');

    define('CONFIG', $ConfigClass);
    classes\ClassHandler::setDefineLocation(CONFIG, ROOT_PATH . DS . 'Config.class' . php);
    classes\ClassHandler::Load(__NAMESPACE__ . "\\classes\\exception\\Exception");
    $config->paths['root'] = ROOT_PATH;

    //classes\ClassHandler::Load(classes\ClassHandler::addRootNamespace('Kint') );
    //classes\Helper::setFunction((new classes\ReflectionStaticMethod(classes\ClassHandler::addRootNamespace('Kint'), 'dump')), 'dump');
    //classes\Kint::defaultModifier('@');
}