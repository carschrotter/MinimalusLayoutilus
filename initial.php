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

/**
 * The initscript
 * load Bootstraping , Erorrohandling,  Configs and Library
 */

namespace mnhcc\ml {

    use mnhcc\ml\classes;

    /**
     * NameSpace Seperator
     */
    const NSS = '\\';

    /**
     * line break
     */
    const n = PHP_EOL;

    /**
     * const br xhtml linebreak
     */
    define('\\mnhcc\\ml\\br', "<br />" . n);

    /**
     * php file extention
     */
    const php = ".php";

    /**
     * The directory separator
     */
    const DS = DIRECTORY_SEPARATOR;

    $version = '5.4.0';
    if (!version_compare(PHP_VERSION, $version, '>=')) { //versions check
	die('The program requires PHP ' . $version . ' or higher! PHP ' . PHP_VERSION . '  installed.');
    }
    if (!\defined("\\mnhcc\\ml\\INDEX")) { //no direckt call
	die(INDEX . 'Define constant "INDEX" in the "\\mnhcc\\ml\\" namespace befor run Programm!');
    }

    define('\\mnhcc\\ml\\STARTTIME', \microtime(true)); //for benchmark and analytic
//ini_set("log_errors", 1);
    ini_set("error_log", realpath('./php-error.log.php'));
//error_reporting(E_ALL);
//ini_set('display_errors', TRUE);
    global $config;
    $config = [];
    session_start();
//ini_set("display_errors", false);


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
    define("mnhcc\\ml\\MNHCC_PATH", __DIR__);
//echo '...' . mnhcc\ml\MNHCC_PATH;
//die('Define constant "NHCC_PATH" and "ROOT_PATH"!' );
    loadConfs(['config', '..' . DS . 'config']);
    if (file_exists(ROOT_PATH . DS . 'config.json')) {
	$config = array_merge((array) $config, (array) json_decode(file_get_contents(ROOT_PATH . DS . 'config.json')));
    }

    if (!file_exists(MNHCC_PATH . DS . 'classes' . DS . 'BootstrapHandler.class' . php)) {
	die('No BootstrapHandler found!' . n . 'Check constant "MNHCC_PATH"');
    }

    require_once(MNHCC_PATH . DS . 'classes' . DS . 'BootstrapHandler.class' . php);

    classes\BootstrapHandler::addExtensionDependency('PDO');
    classes\BootstrapHandler::initial();

    loadConfs(['load']);

    define('mnhcc\\ml\\APPLICATIONNAMESPACE', (key_exists('provider', $config) && key_exists('aplication', $config)) ?
		    classes\BootstrapHandler::makeClassName($config['provider'], $config['aplication']) :
		    false
    );

    classes\BootstrapHandler::setErrorHandler(classes\Error::getInstance());

    if (APPLICATIONNAMESPACE) {
	$ConfigClass = classes\BootstrapHandler::makeClassName(APPLICATIONNAMESPACE, 'Config\\ApplicationConfig');
	if (isset($config['paths']['Config']) && file_exists($config['paths']['Config'])) {
	    classes\BootstrapHandler::setDefineLocation($ConfigClass, $config['paths']['Config']);
	} else {
	    classes\BootstrapHandler::setDefineLocation($ConfigClass, ROOT_PATH . DS . 'Config.class' . php);
	}
    }
    $config = classes\Config\ApplicationConfig::getInstance(classes\Config\ApplicationConfig::DEFAULTINSTANCE, classes\Config::INSTANCE_OVERIDE, $config);
//classes\BootstrapHandler::addRootNamespace('Config');
    classes\BootstrapHandler::Load(__NAMESPACE__ . "\\classes\\Exception");
    $config->paths['root'] = ROOT_PATH;
    $config->paths['test'] = "schrumpel";

//classes\BootstrapHandler::Load(classes\BootstrapHandler::addRootNamespace('Kint') );
//classes\Helper::setFunction((new classes\ReflectionStaticMethod(classes\BootstrapHandler::addRootNamespace('Kint'), 'dump')), 'dump');
//classes\Kint::defaultModifier('@');
    function loadConfs($confs) {
	foreach ($confs as $filename) {
	    if (file_exists(ROOT_PATH . DS . $filename . '.php')) {
		$config = include ROOT_PATH . DS . $filename . '.php';
		$config = is_scalar($config) ? [] : $config;
	    }
	}
    }

//    \FirePHP::getInstance(true)->dump('$c->getClosure()', $c->getClosure());
//    classes\Kint::dump($c->getClosure());
//    classes\Kint::dump($config->paths);
//    classes\Kint::dump($config);
//var_dump($_SERVER);
//var_dump(new classes\URL());
}