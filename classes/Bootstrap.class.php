<?php

namespace mnhcc\ml\classes {

    /**
     * Description of Bootstrap
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class Bootstrap extends MNHcC {

	use \mnhcc\ml\traits\Instances;

	protected static $_path = [];
	protected static $_parmsURI = [];

	public static function header($headers) {
	    $headers = (Helper::isArray($headers)) ? $headers : [$headers];
	    headers_list();
	    foreach ($headers as $header) {
		switch (true) {
		    case is_int($header):
			$answer = http_response_code($header);
			break;
		    case is_string($header):
			header($header);
			break;
		    case Helper::isArray($header):
			$header;
		    default:
			break;
		}
	    }
	}

	public static function isHeaderSent($header) {
	    $headers = headers_list();
	    $header = trim($header, ': ');
	    $result = false;

	    foreach ($headers as $hdr) {
		if (stripos($hdr, $header) !== false) {
		    $result = true;
		}
	    }

	    return $result;
	}

	public static function isDebug() {
	    return (defined('DEBUG') && DEBUG);
	}

	public static function getControl() {
	    $_this = self::getInstance();
	    if (!$_this->parmsURI['control']) {
		return false;
	    }
	    return ucfirst(strtolower($_this->parmsURI['control']));
	}

	public static function getAction() {
	    $_this = self::getInstance();
	    if (!$_this->parmsURI['action']) {
		return false;
	    }
	    return ucfirst(strtolower($_this->parmsURI['action']));
	}

	function __construct() {
	    $this->_path = explode('/', rtrim(SERVER::virtualPath(), '/'));
	    $last = &$this->_path[count($this->_path) - 1];
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
	    if (isset($this->path[0]) && $this->path[0] != ''){
		$this->parmsURI['control'] = $this->path[0];
	    }
	    if (isset($this->path[1]) && $this->path[1] != ''){
		$this->parmsURI['action'] = $this->path[1];
	    }
	}

    }

}