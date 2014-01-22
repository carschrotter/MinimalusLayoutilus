<?php

namespace mnhcc\ml\classes;

use mnhcc\ml\traits as traits; {

    class Kint extends \Kint implements \mnhcc\ml\interfaces\MNHcC {

        use traits\MNHcC;

        public static $defaultModifier;

        /**
         *
         * @var  \mnhcc\ml\classes\ReflectionClass 
         */
        protected static $_parent;

        /**
         * dump information about variables
         *
         * @param mixed $data
         *
         * @return void|string
         */
        public static function dump($data = null) {
            static $_css, $_wrapStart, $_wrapEnd, $func_init;
            if (!Kint::enabled())
                return;
            if (!$func_init) {
                $Kint_Decorators_Rich = new ReflectionClass('Kint_Decorators_Rich');
                
                $_cssrfl = $Kint_Decorators_Rich->getMethod('_css', ClassHandler::addRootNamespace('ReflectionStaticMethod'));
                $_cssrfl->setAccessible(true);
                $_css = $_cssrfl->getClosure();
                
                $_wrapStartrfl = $Kint_Decorators_Rich->getMethod('_wrapStart', ClassHandler::addRootNamespace('ReflectionStaticMethod'));
                $_wrapStartrfl->setAccessible(true);
                $_wrapStart = $_wrapStartrfl->getClosure();
                $_wrapStart->bindTo(null, 'Kint_Decorators_Rich');
                
                $_wrapEndrfl = $Kint_Decorators_Rich->getMethod('_wrapEnd', ClassHandler::addRootNamespace('ReflectionStaticMethod'));
                $_wrapEndrfl->setAccessible(true);
                $_wrapEnd = $_wrapEndrfl->getClosure();
                $_wrapEnd->bindTo(null, 'Kint_Decorators_Rich');
                
                $func_init = true;
            }

            # find caller information
            $trace = debug_backtrace();
            list( $names, $modifier, $callee, $previousCaller ) = self::_getPassedNames($trace);
            $modifier = ($modifier) ? $modifier : self::defaultModifier();
            if ($names === array(null) && func_num_args() === 1 && $data === 1) {
                $call = reset($trace);
                if (!isset($call['file']) && isset($call['class']) && $call['class'] === __CLASS__) {
                    array_shift($trace);
                    $call = reset($trace);
                }

                while (isset($call['file']) && $call['file'] === __FILE__) {
                    array_shift($trace);
                    $call = reset($trace);
                }

                self::trace($trace);
                return;
            }

            # process modifiers: @, + and -
            switch ($modifier) {
                case '-':
                    self::$_firstRun = true;
                    while (ob_get_level()) {
                        ob_end_clean();
                    }
                    break;

                case '!':
                    self::$expandedByDefault = true;
                    break;
                case '+':
                    $maxLevelsOldValue = self::$maxLevels;
                    self::$maxLevels = false;
                    break;
                case '@':
                    $firstRunOldValue = self::$_firstRun;
                    self::$_firstRun = true;
                    break;
            }

            $data = func_num_args() === 0 ? array("[[no arguments passed]]") : func_get_args();
            $output = $_css();
            $output .= $_wrapStart($callee);

            foreach ($data as $k => $argument) {
                $output .= self::_dump($argument, $names[$k]);
            }


            $output .= $_wrapEnd($callee, $previousCaller);

            // $output .= \Kint_Decorators_Rich::_wrapEnd($callee, $previousCaller);

            self::$_firstRun = false;

            switch ($modifier) {
                case '+':
                    self::$maxLevels = $maxLevelsOldValue;
                    echo $output;
                    break;
                case '@':
                    self::$_firstRun = $firstRunOldValue;
                    return $output;
                    break;
                default:
                    echo $output;
                    break;
            }

            return '';
        }

        protected static function _getPassedNames($trace) {
            static $_getPassedNames;
            if (!$_getPassedNames) {
                $class = new ReflectionClass(__CLASS__);
                $parent = $class->getParentClass();
                $_getPassedNames = $parent->getMethod('_getPassedNames');
                $_getPassedNames->setAccessible(true);
                $rr = $_getPassedNames->getClosure();
                $rr->bindTo(null, __CLASS__);
            }
            return $_getPassedNames->invoke(null, $trace);
        }

//        protected static function _getPassedNames($trace) {
//            return parent::_getPassedNames($trace);
//        }

        public static function defaultModifier($value = null) {
            # act both as a setter...
            if (func_num_args() > 0) {
                self::$defaultModifier = $value;
                return;
            }

            # ...and a getter
            return self::$defaultModifier;
        }

        public static function _init() {
            return parent::_init();
        }

        public static function ___onLoaded() {
        }

    }

}
