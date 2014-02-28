<?php

namespace mnhcc\ml\classes {
    use  \mnhcc\ml\interfaces\Instances;

    /**
     * Description of Bootstrap
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    class Router extends MNHcC {
	use \mnhcc\ml\traits\Instances;
	

	protected $_path = [];
	protected $_parmsURI = [];
	
	public function getPath() {
	    return $this->_path;
	}

	public function getParmsURI() {
	    return $this->_parmsURI;
	}

	public static function header($headers) {
	    $headers = (ArrayHelper::isArray($headers)) ? $headers : [$headers];
	    headers_list();
	    foreach ($headers as $header) {
		switch (true) {
		    case is_int($header):
			$answer = http_response_code($header);
			break;
		    case is_string($header):
			header($header);
			break;
		    case ArrayHelper::isArray($header):
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
	    return Bootstrap::isDebug();
	}

//	public static function getControl($default = 'index') {
//	    $_this = self::getInstance();
//	    return $_this->getParm()->getControl($default, true);
//	    //return ucfirst(strtolower($_this->parmsURI['control']));
//	}
//
//	public static function getAction($default = 'index') {
//	    $_this = self::getInstance();
//	    return $_this->getParm()->getAction($default, true);
//	    //return ucfirst(strtolower($_this->parmsURI['action']));
//	}

	/**
	 * get the parmd for the curent route
	 * @return Parm
	 */
	public function getParm($parms = []) {
	    return Parm::getInstance($this->getInstanceID(),Instances::INSTANCE_NOT_OVERIDE, $this->getPath(), $this->getParmsURI(), $parms);
	}
	
	public function __construct($path = null) {
	    $this->_path = explode('/', rtrim( (($path !== null) ? $path : SERVER::virtualPath()), '/'));
	    $last = &$this->_path[count($this->_path) - 1];
	    $lastsplit = explode('.', $last);
	    $last = \array_shift($lastsplit);
	    $this->extention = $lastsplit;
	    $this->_parmsURI = $this->_path;
	    $control = ArrayHelper::shift($this->_parmsURI);
	    $action =  ArrayHelper::shift($this->_parmsURI);
	    foreach ($this->_parmsURI as $value) {
		list($key, $arg) = @explode("|", $value);
		if ($key && $arg) {
		    $this->_parmsURI[$key] = $arg;
		} else {
		    $this->_parmsURI[$value] = true;
		}
	    }
	    if($control){$this->_parmsURI['control'] = $control;}
	    if($action){$this->_parmsURI['action'] = $action;}
	}

    }

}