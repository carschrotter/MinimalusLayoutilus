<?php

namespace mnhcc\ml\classes {

    /**
     * Description of URI
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     * @copyright (c) 2013, Michael Hegenbarth
     */
    abstract class SERVER extends MNHcC {

	private static $host = false;
	private static $request_uri = false;
	private static $ssl = false;
	private static $port = false;
	private static $script_name = false;
	private static $script_dir = false;
	private static $virtualPath = false;

	/**
         * 
         * @staticvar string $protocol
         * @staticvar string $host
         * @return string
         */
        public static function getServer() {
            return self::getProtocol(true) . self::getHost();
        }

        public static function getHost() {
            
            if (!self::$host) {
                 self::$host = (self::get('HTTP_HOST')) ? self::get('HTTP_HOST') : getenv('HTTP_HOST');
            }
            return self::$host;
        } 

        public static function getProtocol($urlstyle = false) {
            if ($urlstyle) {
                return (self::isSSL() ? 'https://' : 'http://');
            }
            return isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : getenv('SERVER_PROTOCOL');
        }
        
        public static function getRequestUri() {
            if (!self::$request_uri) {
                self::$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : getenv('REQUEST_URI');
            }
            return self::$request_uri;
        }

        public static function isSSL() {
            if (!self::$ssl) {
                $HTTPS = isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : getenv('HTTPS');
                $ssl = ($HTTPS != 'off');
            }
            return self::$ssl;
        }

        public static function getPort() {
            if (!self::$port) {
                $port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : getenv('HTTP_HOST');
            }
            return self::$port;
        }
        public static function getScriptName() {
            self::$script_name;
            if (!$script_name) {
                $script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : getenv('SCRIPT_NAME');
            }
            return $script_name;
        }
         public static function  isDefaultPort() {
             return ( (self::getPort() == 80 && !self::isSSL()) || (self::getPort() == 443 && self::isSSL()) );
         }
         

        /**
         * 
         * @staticvar string $script_dir the saved dir from SCRIPT_NAME 
         * @param bool $relativ get relative path without the hostname
         * @param bool $virtual also looking for virtual paths e.g. with apache alias
         * @return string
         */
        public static function getBase($absolut = true, $virtual = false) {

            if (!self::$script_dir) {
                $script_dir = dirname(self::getScriptName());
                $script_dir = str_replace("\\", '/', $script_dir);
                self::$script_dir = rtrim($script_dir, '/') . '/';
		unset($script_dir);
            }
            $base_url = '';
            if ($absolut) {
                $base_url = (is_string($absolut)) ? $absolut : self::getServer();
            }
            $base_url .= (isset($_SERVER["CONTEXT_PREFIX"]) && $virtual) ? rtrim($_SERVER["CONTEXT_PREFIX"], '/') . '/' : self::$script_dir;
            return $base_url;
        }
	
	public static function base($absolut = true, $virtual = false) {
	    Error::getInstance()->triggerError(" is deprecated", Error::WARNING);
	    return self::getBase($absolut, $virtual);
	}
	
	public static function get($key) {
	    return Filter::input_server($key);
	}

        /**
         * 
         * @staticvar string $virtualPath
         * @return string
         */
        public static function virtualPath() {
            if (!self::$virtualPath) {
                self::$virtualPath = ltrim(explode('?', self::getRequestUri())[0], self::getBase(true, true));
            }
            return self::$virtualPath;
        }

        /**
         * 
         * @param bool $relativ
         * @return string
         */
        public static function requestPath($relativ = false) {
            $URI = rtrim(explode('?', self::getRequestUri())[0], '/') . '/';
            return ($relativ) ? $URI : self::getServer() . $URI;
        }

        public static function __callStatic($name, $arguments) {
            parent::__callStatic($name, $arguments);
        }

    }

}