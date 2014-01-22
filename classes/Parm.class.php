<?php

namespace mnhcc\ml\classes;

use mnhcc\ml\traits as traits;
use mnhcc\ml\interfaces as interfaces;
{

    /**
     * Description of Parm
     * @todo Description
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class Parm extends MNHcC implements interfaces\Parameters {

        use traits\Instances;

        const ISCASESENSETIV = '{"ISCASESENSETIV":true,"secure":"Ay0keRT1l8"}';
        const ISISSET = '{"ISISSET":true,"secure":"Ay0keRT1l8"}';
        const NOTFOUND = '{"NOTFOUND":true,"secure":"Ay0keRT1l8"}';

        protected $path = [];
        protected $parms = [];
        protected $parmsURI = [];
        protected $extention = [];

        public function __construct($parms = []) {
            $this->parms = $parms;
            $this->path = explode('/', rtrim(SERVER::virtualPath(), '/'));
            $last = &$this->path[count($this->path) - 1];
            $lastsplit = explode('.', $last);
            $last = \array_shift($lastsplit);
            $this->extention = $lastsplit;
            foreach ($this->parmsURI as $value) {
                list($key, $arg) = explode("|", $value);
                if ($key && $arg) {
                    $this->parmsURI[$key] = $arg;
                } else {
                    $this->parmsURI[$value] = true;
                }
            }
            if (isset($this->path[0]) && $this->path[0] != '')
                $this->parmsURI['control'] = $this->path[0];
            if (isset($this->path[1]) && $this->path[1] != '')
                $this->parmsURI['action'] = $this->path[1];
        }

        public function getExtention($default = '') {
            $ext = end($this->extention);
            return ($ext) ? end($this->extention) : $default;
        }

        public function getControl($default = 'index', $storeDefault = false) {
            $parms = $this->getParms();
            $control = (isset($parms['control']) && $parms['control'] != '') ? $parms['control'] : $default;
            $control = strtolower($control);
            if ($storeDefault)
                $this->parms['control'] = $control;
            return ucfirst($control);
        }

        public function getAction($default = 'index', $storeDefault = false) {
            $parms = $this->getParms();
            $action = (isset($parms['action']) && $parms['action'] != '') ? $parms['action'] : $default;
            $action = strtolower($action);
            if ($storeDefault)
                $this->parms['action'] = $action;
            return ucfirst($action);
        }

        public function getParms() {
            $parms = array_merge($_COOKIE, $this->parmsURI, $this->parms, $_REQUEST, $_FILES, $_SESSION);
            return $parms;
        }

        public function get($key, $default = null, $type = 'ALL') {

            switch (strtoupper($type)) {
                case 'ALL':
                    $parms = $this->getParms();
                    break;
                case 'SESSION':
                    $parms = $_SESSION;
                    break;
                case 'REQUEST':
                    $parms = $_SESSION;
                    break;
                case 'POST':
                    $parms = $_POST;
                    break;
                case 'GET':
                    $parms = $_GET;
                    break;
                case 'COOKIE':
                    $parms = $_COOKIE;
                    break;
                case 'FILES':
                    $parms = $_FILES;
                    break;
                default:
                    $parms = $this->getParms();
                    break;
            }
            return htmlspecialchars((isset($parms[$key])) ? $parms[$key] : $default );
        }

        /**
         * 
         * @param string $key
         * @param mixed $check number or string
         * @return bool
         */
        public function is($key, $check = true, $method = false) {
            switch ($method) {
                case self::ISCASESENSETIV:
                    if (is_string($this->get($key, false)) && is_string($check)) {
                        return (bool) (strtolower($this->get($key)) === strtolower($check));
                    }
                    break;
                case self::ISISSET:
                    return ($this->get($key, self::NOTFOUND) !== self::NOTFOUND);
                default:
                    return (bool) ( $this->get($key) == $check);
                    break;
            }
            return false;
        }

    }

}