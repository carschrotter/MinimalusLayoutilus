<?php

namespace mnhcc\ml\classes;

use mnhcc\ml\traits as traits;
{

    /**
     * Description of Helper
     * 
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2013, Michael Hegenbarth
     */
    abstract class Helper {

	use traits\Caller;

	const toggleDump = '{toggleDump : "true", secure : "Ay0keRT1l8"}';

	public static function dump() {
	    return self::__callStatic('dump', func_get_args());
	}

	protected static function __defaultDump($toggleDump = self::toggleDump) {
	    $args = func_get_args();
	    $toggle = false;
	    $str = '';
	    if ($toggleDump === self::toggleDump) {
		$toggle = true;
		\array_shift($args);
	    }
	    foreach ($args as $arg) {
		ob_start();
		var_dump($arg);
		$content = ob_get_contents();
		if ($toggle)
		    $str .= self::toggle($content);
		else
		    $str .= $content;
		ob_end_clean();
	    }
	    return $str;
	}

	/**
	 * 
	 * @param mixed $arg variable to display with var_dump()
	 * @param mixed $arg,... unlimited OPTIONAL number of additional variables to display with var_dump()
	 * @return mixed debug string on sucsess falso on error
	 */
	protected static function __defaultDebug($arg) {
	    $temp = '<div class="debug" style="border:5px solid red;">';

	    try {
		$ref = new \ReflectionMethod(__CLASS__, 'dump');
		$temp .= $ref->invokeArgs(Null, func_get_args());
		$temp .= Error::renderBacktrace(debug_backtrace());
	    } catch (\Exception $exc) {
		return FALSE;
	    }
	    return $temp . '</div>';
	}

	public static function toggle($content, $container = 'div') {
	    $str = '<' . $container . ' class="preview_toggle">';
	    $str .= $content;
	    $str .= '</' . $container . '>';
	    return $str;
	}

	/**
	 * clean a name (class or id) for CSS
	 * @param string $classname the css classname
	 * @param mixed $legitimer string or int <p>
	 * string: put a string in order not to legitimize a valid name
	 * int: the same with the number of certain randomly generated characters
	 * </p>
	 * @return string teh cleaned name
	 */
	static public function cssNameClean($classname, $legitimer = null) {
	    // DE-de entfernt alle nicht alphanumerischen zeichen und ersetzt leerschritte durch das +
	    // removes all non-alphanumeric characters and replaced by the empty steps +
	    $clean_classname = preg_replace_callback('~(\W)~', function($test) {
		return ($test[1] == ' ') ? '_' : '_';
	    }, $classname);

	    // DE-de aufeinanderfolgende "+" werden auf genau ein + reduziert
	    // consecutive "+" are reduced to exactly one +
	    $clean_classname = preg_replace('~(\_){2,}~', '_', $clean_classname);
	    //does not start with valid characters:
	    if (!preg_match('~^[a-zA-Z]~', $clean_classname)) {
		if (is_string($legitimer)) {
		    return $legitimer . $clean_classname; // with custom string
		} else {
		    return self::generateLegitimer($legitimer) . $clean_classname; //with randomly generated characters
		}
	    }
	    return $clean_classname;
	}
	
	static public function generateLegitimer($lenght = 3) {
	    $lenght = (is_int($lenght) && $lenght >= 1) ? $lenght : 3;
	    $legitimerChars = ['a', 'b', 'c', 'd', 'e',
		'f', 'g', 'h', 'i', 'j',
		'k', 'l', 'm', 'n', 'o',
		'p', 'q', 'r', 's', 't',
		'v', 'w', 'x', 'y', 'z'];
	    $legitimer = '';
	    for ($i = 0; $i < $lenght; $i++) {
		$legitimer .= $legitimerChars[mt_rand(0, count($legitimerChars) - 1)];
	    }
	    return $legitimer;
	}
	
	static public function checkPost() {
	    return (bool) count($_POST);
	}

	static public function checkGet() {
	    return (bool) count($_GET);
	}

	static public function checkLogin() {
	    return ( ((bool) Parm::getInstance()->get('S_username', false, 'SESSION')) &&
		    ((bool) Parm::getInstance()->get('S_userid', false, 'SESSION')) );
	}

	static public function checkRight($level = 'user') {
	    if ($this->checkLogin()) {
		/* ToDo implenet user rights */
		return true;
	    }
	}

	static public function isArray($val) {
	    if (is_object($val)) {
		return ($val instanceof \ArrayAccess);
	    }
	    return is_array($val);
	}

	static public function isJson1($string) {
	    json_decode($string);
	    return (json_last_error() == JSON_ERROR_NONE);
	}

	static public function isJson2($string) {
	    return !preg_match('/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/', preg_replace('/"(\\.|[^"\\])*"/g', '', $string));
	}

	static public function getConstValue($value) {
	    // i
	}

	static public function isMLConst($value) {
	    return is_object(json_decode($value));
	}

	static public function classExists($class, $autoNamespace = true, $autoload = true) {
	    if ($autoNamespace) {
		$check = ClassHandler::addRootNamespace($class);
	    } else {
		$check = $class;
	    }
	    $answer = (bool) @\class_exists($check, $autoload);
	    return $answer;
	}

	/**
	 * Call a user method on an specific object
	 * @param string $method_name <p>
	 * The method name being called.
	 * </p>
	 * @param mixed $obj <p>
	 * The object or class that <i>method_name</i>
	 * is being called on.
	 * </p>
	 * @param array $params <p>
	 * An array of parameters.
	 * </p>
	 * @return mixed
	 */
	static public function callMethodArray($method_name, $obj, $params = []) {
	    return (new \ReflectionMethod($obj, $method_name))->invokeArgs($obj, $params);
	}

//                static public function callWhenAvailable($Class) {
//                    self
//                    return 
//                }
    }

}
    