<?php

namespace mnhcc\ml\classes;
{
    /**
     * Description of EventParms
     *
     * @author carschrotter
     */
    class EventParms implements \mnhcc\ml\interfaces\Parameters{

        const ISCASESENSETIV = '{"ISCASESENSETIV":true,"secure":"Ay0keRT1l8"}';
        const ISISSET = '{"ISISSET":true,"secure":"Ay0keRT1l8"}';
        const NOTFOUND = '{"NOTFOUND":true,"secure":"Ay0keRT1l8"}';


        protected $_parms = [];

        public function __construct($parms = []) {
	    if(!Helper::isArray($parms)) throw new Exception('$parms is not a Array', 0);
            $this->_parms = $parms;
        }

        public function getParms() {
            return  $this->_parms;
        }

        public function get($key, $default = null, $filter = false) {
	    $parms = $this->getParms();
	    $answer = ((isset($parms[$key])) ? $parms[$key] : $default );
            return (is_string($answer) && $filter) ? Filter::html($answer) : $answer;
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