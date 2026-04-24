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

namespace mnhcc\ml\classes\Control {
    use mnhcc\ml\classes;

    /**
     * Default index controller — detects browser language and provides
     * localised project introduction content.
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     */
    class ControlIndex extends ControlDefault
    {
        protected static $_lang = 'en';

        protected static $_i18n = [
            'de' => [
        	'lang'        => 'de',
        	'title'       => 'Minimalus Layoutilus',
        	'tagline'     => 'Ein schlankes PHP-MVC-Framework mit Templating',
        	'intro'       => 'Minimalus Layoutilus ist ein leichtgewichtiges PHP-Framework, das ein sauberes MVC-Konzept mit einem flexiblen Templating-System verbindet. Es eignet sich als Basis für eigene Webanwendungen und ist als Composer-Paket verfügbar.',
        	'features_h'  => 'Features',
        	'features'    => [
        	    'MVC-Architektur mit Controller-, View- und Template-Trennung',
        	    'Flexibles Namespace-basiertes Autoloading',
        	    'Event-System mit EventManager und EventParms',
        	    'Konfigurierbares Bootstrapping und Fehlerbehandlung',
        	    'Installierbar via Composer, PHP &ge; 5.4',
        	],
        	'install_h'   => 'Installation',
        	'install_cmd' => 'composer require mnhcc/minimalus-layoutilus',
        	'github'      => 'GitHub',
        	'license'     => 'Lizenz: LGPL 2.1',
            ],
            'en' => [
        	'lang'        => 'en',
        	'title'       => 'Minimalus Layoutilus',
        	'tagline'     => 'A lightweight PHP MVC framework with templating',
        	'intro'       => 'Minimalus Layoutilus is a lightweight PHP framework combining a clean MVC concept with a flexible templating system. It serves as a foundation for web applications and is available as a Composer package.',
        	'features_h'  => 'Features',
        	'features'    => [
        	    'MVC architecture with separated controller, view and template layers',
        	    'Flexible namespace-based autoloading',
        	    'Event system with EventManager and EventParms',
        	    'Configurable bootstrapping and error handling',
        	    'Installable via Composer, PHP &ge; 5.4',
        	],
        	'install_h'   => 'Installation',
        	'install_cmd' => 'composer require mnhcc/minimalus-layoutilus',
        	'github'      => 'GitHub',
        	'license'     => 'License: LGPL 2.1',
            ],
        ];

        public static function getI18n()
        {
            return self::$_i18n[self::$_lang];
        }

        public static function getLang()
        {
            return self::$_lang;
        }

        public function getComponent(classes\ParmsControl $parm)
        {
            $view = classes\View::getView($parm->getType(true), __CLASS__);
            return $view->renderComponent($parm);
        }

        public function actionIndex()
        {
            $accept = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
            self::$_lang = preg_match('/^de/i', $accept) ? 'de' : 'en';
        }

        public function onBeforeAction()
        {
            parent::onBeforeAction();
        }
    }
}
