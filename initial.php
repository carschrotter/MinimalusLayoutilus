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
 * load Bootstrapping, Error Handling, Configs and Library
 */

namespace mnhcc\ml {

    // Namespace-level constants must be declared at compile time and cannot be
    // defined inside a function, so they remain here.
    /** @var string Namespace separator */
    const NSS = '\\';
    /** @var string Line break */
    const n   = PHP_EOL;
    /** @var string PHP file extension */
    const php = ".php";
    /** @var string Directory separator */
    const DS  = DIRECTORY_SEPARATOR;

    global $config;
    $config = [];
    session_start();

    ini_set("error_log", realpath('./php-error.log.php'));

    // Bootstrap: validates environment, defines global constants (NSS, n, br,
    // php, DS), sets path constants (MNHCC_PATH, ROOT_PATH) and registers the
    // SPL autoloaders.
    classes\BootstrapHandler::addExtensionDependency('PDO');
    classes\BootstrapHandler::initial(__DIR__);

    loadConfs(['config', '..' . DS . 'config']);
    if (file_exists(ROOT_PATH . DS . 'config.json')) {
        $config = array_merge((array) $config, (array) json_decode(file_get_contents(ROOT_PATH . DS . 'config.json')));
    }

    loadConfs(['load']);

    define(
        'mnhcc\\ml\\APPLICATIONNAMESPACE',
        (key_exists('provider', $config) && key_exists('application', $config)) ?
		    classes\BootstrapHandler::makeClassName($config['provider'], $config['application']) :
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

    classes\BootstrapHandler::Load(__NAMESPACE__ . "\\classes\\Exception");
    $config->paths['root'] = ROOT_PATH;
    $config->paths['test'] = "schrumpel";

    function loadConfs($confs)
    {
        global $config;
        foreach ($confs as $filename) {
            if (file_exists(ROOT_PATH . DS . $filename . '.php')) {
                $loaded = include ROOT_PATH . DS . $filename . '.php';
                if (!is_scalar($loaded)) {
                    $config = array_merge((array) $config, (array) $loaded);
                }
            }
        }
    }
}
