<?php

namespace mnhcc\ml\classes;
use \mnhcc\ml;
{

    /**
     * The interface to render the aplication to the output format
     * parse the templatefile and set results
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus
	 * @copyright (c) 2013, Michael Hegenbarth
	 */
    abstract class Template extends MNHcC {

        use \mnhcc\ml\traits\Instances;

        /**
         * the default Doctype for the template
         * @var string 
         */
        protected static $_defDocType = 'html';

        /**
         * the aliases for extention
         * $key                 =>  $value
         * the used extention       the alias
         * @var array 
         */
        protected static $_extAlias = ['php' => 'html', 'htm' => 'html', 'xhtml' => 'html'];

        /**
         * the name of used template
         * @var string 
         */
        protected $_template = '';

        /**
         * the raised errors for rendering errormessages
         * @var array 
         */
        protected $_errors = [];

        /**
         * is a error raised
         * @var bool 
         */
        protected $_error = false;


        /**
         * save instance to self::$instances and set self::$init to true;
         */
        public function __construct() {
	    self::setInstance(self::DEFAULTINSTANCE, $this);
	    EventManager::raise('templateCreated', new template\EventParms($this, []));
        }

        /**
         * get the DocType of template
         * @return string
         */
        public function getDocType() {
            return static::$_defDocType;
        }

        /**
         *  is a object createt also init
         * @return bool
         */
        public static function isInit() {
            return self::$init;
        }

        /**
         * Get the Templatefile
         * @return string
         */
        public function getTemplate() {
            return $this->_template;
        }

        /**
         * Set the Templatefile
         * @param type $template
         */
        public function setTemplate($template) {
            $this->_template = $template;
        }

        /**
         * Get the array whit the error messages
         * @return array
         */
        public function getErrors() {
            return $this->_errors;
        }

        /**
         * Set the array whit the error messages
         * @param array $errors
         */
        protected function setErrors($errors) {
            $this->_errors = $errors;
        }

        /**
         * Is a Error raised
         * @return boolean
         */
        public function isError() {
            return $this->_error;
        }

        /**
         * get the website base.
         * example to include css files : $template->base().'path/to/css/style.css'
         * @return string
         */
        public function base($absolut = true) {
	    
            return SERVER::getBase($absolut, false);
        }

        /**
         * Get de default DocType from the Template class
         * @return string
         */
        public static function getDefDocType() {
            return self::$_defDocType;
        }

        /**
         * Get the doctype from extention example: php to Html
         * @param string $ext
         * @return string
         */
        public static function getDocTypeFromExt($ext = 'html') {
            if (isset(self::$_extAlias[$ext]))
                return ucfirst(self::$_extAlias[$ext]);
            return ucfirst($ext);
        }

        /**
         * Raise a error on the Template. Is a error raised no Control rendering.
         * Get out the error message as component.
         * @param type $code
         * @param type $msg
         */
        public function error($code, $msg = 'On Error on this Page..') {
            $this->_error = true;
            $this->_errors[] = ['code' => $code, 'msg' => $msg];
        }

        /**
         * Render the template and get the output
         * @param array $params
         * @return string
         */
        public abstract function render($params = array());

        /**
         * Render the messages. Call from mnhcc\ml\classes\Programm
         * @param array $msg [ ['msg' => string, 'type' => string, 'heading' => string], ... ]
         * @return string
         */
        public abstract function renderMsg(array $msg);
    }

}