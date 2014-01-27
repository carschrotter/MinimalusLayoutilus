<?php

namespace mnhcc\ml\classes {
    /**
     * Description of Error
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus
     * @copyright (c) 2012, Michael Hegenbarth
     */
    class Error extends MNHcC {

	use \mnhcc\ml\traits\Instances;

	const ERROR = E_ERROR;
	const WARNING = E_WARNING;
	const NOTICE = E_NOTICE;
	const STRICT = E_STRICT;
	const DEPRECATED = E_DEPRECATED;
	const EXCEPTION = -1;
	const RAISEUSETEMPLATE = '{"RAISEUSETEMPLATE":true,"secure":"Ay0keRT1l8"}';
	const RAISEERROR = '{"RAISEERROR":true,"secure":"Ay0keRT1l8"}';

	protected static $_templateParms = ['~baseurl~' => '/', '~heading~' => '<h1>Error 500</h1>'];

	/**
	 * the default template for shutdown event
	 * @var string 
	 */
	protected static $template = <<<EOF
<!DOCTYPE html><!-- XHTML 5 -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<haed>
	<link rel="stylesheet" href="~baseurl~assets/css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="~baseurl~assets/css/override.css" type="text/css" />
	<link rel="stylesheet" href="~baseurl~assets/css/error.css" type="text/css" />
	<link rel="stylesheet" href="~baseurl~assets/css/debug.css" type="text/css" />
</haed>
<body id="error">
	<div class="container error">
		<div class="row">
			<div class="span12 box-outline" id="main-error-box">
				<div class class="box-header">
				~heading~
				</div>
				<div class="box-body">
				%s
				<p>
				<span style="color:inherit; font-weight:900;">Last Error:</span>
				<br /> %s <br />
				</p>
				</div>
			</div>
		</div>
	</div>
	<div id="placeholder">Error</div>
</body>
</html>
EOF;
	protected $_isDisplayedBugs = false;

	public function isDisplayedBugs() {
	    return $this->_isDisplayedBugs;
	}

	protected static function renderTemplate() {
	    $args = func_get_args();
	    $template = self::$template;

	    foreach (self::$_templateParms as $key => $value) {
		$template = str_replace($key, $value, $template);
	    }

	    \array_unshift($args, $template);
	    $template = call_user_func_array('\\sprintf', $args);

	    return $template;
	}

	/**
	 * array of all error in exeptionform
	 * @var array
	 */
	private $_exceptions = array();

	/**
	 * Value is true when is json vormat
	 * @var type
	 */
	protected $_isJson = false;

	/**
	 * responsecode (Errorstate)
	 * @var int 
	 */
	protected $_code = 0;

	/**
	 * set the responsecode (Errorstate)
	 * @param int $code
	 * @return \mnhcc\ml\classes\Error
	 */
	protected function _setCode($code) {
	    if ($this->_code < $code) {
		$this->_code = $code;
	    }
	    return $this;
	}

	/**
	 * get the responsecode (Errorstate)
	 * @return int
	 */
	public function getCode() {
	    return $this->_code;
	}

	/**
	 * render infos for json
	 * @param bool $isJson
	 * @return bool
	 */
	public function isJson($isJson = null) {
	    if ($isJson !== null)
		$this->_isJson = $isJson;
	    return $this->_isJson;
	}

	public function __construct() {
	    $this->parms = Parm::getInstance();
	    $this->blankScreenProtection = (bool) $this->parms->get('blankScreenProtection', 1);
	    if ($this->parms->get('enabeled', 1)) {
		register_shutdown_function([$this, 'shutdown']);
		set_error_handler([$this, 'handleError']);
		set_exception_handler([$this, 'handleException']);
	    }
	    if ($this->blankScreenProtection) {
		ob_start();
		ob_implicit_flush(false);
	    }
	    EventManager::register(new Event([self::getCalledClass(), 'onTemplateCreated'], 'onTemplateCreated'));
	}

	public function __destruct() {
	    restore_error_handler();
	    restore_exception_handler();
	    if (!$this->blankScreenProtection) {
		return;
	    }
	    $contents = ob_get_contents();
	    $baseurl = '/';
	    if ($contents == '') {
		$message = '';
		$exception = $this->getLastException();
		if (is_object($exception)) {
		    $message = $exception->getMessage();
		}
		if (Helper::classExists('SERVER', true)) {
		    self::$_templateParms['~baseurl~'] = SERVER::getBase();
		}
		echo self::renderTemplate('The program was completed without spending a content.', $message);
	    }
	    $contents = ob_get_contents();
	    ob_end_clean();
	    echo $this->documentPrepare($contents);
	}

	public function documentPrepare($doc) {
	    if (Helper::classExists('Bootstrap', true, false)) {
		if (Bootstrap::isDebug()) {
		    if ($this->isJson()) {
			return $this->documentPrepareJSON($doc);
		    } else {
			return $this->documentPrepareHTML($doc);
		    }
		}
	    }
	    return $doc;
	}

	public function documentPrepareJSON($doc) {
	    $exeptions = ', ' . ltrim(json_encode(['bugcatcher' => $this->_exceptions]), '{');
	    return rtrim($doc, '}') . $exeptions;
	}

	/**
	 * add the bugcatcher console to the document
	 * @param string $doc the document
	 * @return string the prepared document
	 */
	public function documentPrepareHTML($doc) {
	    $html = '';
	    if (!$this->isDisplayedBugs()) {
		$html = $this->displayBugs();
		$html .= "<script>
                    function toggleContainer(name) {
                    var e = document.getElementById(name);// jQery might not be available ;)
                    e.style.height = (e.style.height == '0px') ? 'auto' : '0px';
                    }
                    </script>";
	    }
	    $contents = str_replace('<div id="placeholder">Error</div>', '', $doc);
	    $contents = str_replace('</body>', n . $html . n . '</body>', $contents);
	    return $contents;
	}

	/**
	 * 
	 * @param int $code
	 * @param string $message
	 * @param string $log
	 * @param \mnhcc\ml\classes\Template $template
	 * @return mixed on succes Error::USETEMPLATE on error RAISEERROR 
	 */
	public function raise($code, $message = '', $log = '', $header = []) {
	    $this->_setCode($code);
	    $template = (Helper::classExists('Template', true) && Template::isInit()) ? Template::getInstance() : null;
	    $logmsg = 'Enabele debug to show this message.';
	    if (Helper::classExists('Bootstrap', true, false)) {
		if (Bootstrap::isDebug()) {
		    $logmsg = $log;
		}
	    }
	    switch ($code) {
		case 505:
		    if (Helper::classExists('Bootstrap', true, false)) {
			Bootstrap::header(505);
		    }
		    if (Helper::classExists('SERVER', true, false)) {
			self::$_templateParms['~baseurl~'] = SERVER::getBase();
		    }
		    die($this->documentPrepare(self::renderTemplate($message, $logmsg)));
		    break;
		case 404: case 403: default:
		    Bootstrap::header($code);
		    if ($template != null) {
			$template->error($code, $message);
			return self::RAISEUSETEMPLATE;
		    } else {

			if (Helper::classExists('SERVER', true))
			    self::$_templateParms['~baseurl~'] = SERVER::getBase();
			die($this->documentPrepare(self::renderTemplate($message, $logmsg)));
		    }
		    break;
	    }
	    return self::RAISEERROR;
	}

	/**
	 * <b>Generates a user-level error/warning/notice message</b>
	 * <p>Used to trigger a user error condition, it can be used by in conjunction with the built-in error handler, or with a user defined function that has been set as the new error handler
	 * <br>
	 * This function is useful when you need to generate a particular response to an exception at runtime.</p>
	 * @param string $error_msg The designated error message for this error. It's limited to 1024 bytes in length. Any additional characters beyond 1024 bytes will be truncated.
	 * @param int  $error_type The designated error type for this error. It only works with the E_USER family of constants, and will default to E_USER_NOTICE.
	 * @return type
	 */
	public static function triggerError($error_msg, $error_type = E_USER_NOTICE) {
	    switch (self::getType($error_type)) {
		case self::DEPRECATED :
		    $error_type = E_USER_DEPRECATED;
		    break;
		case self::ERROR :
		    $error_type = E_USER_ERROR;
		    break;
		case self::NOTICE :
		    $error_type = E_USER_NOTICE;
		    break;
		case self::WARNING :
		    $error_type = E_USER_WARNING;
		    break;

		default:
		    break;
	    }
	    return trigger_error($error_msg, $error_type);
	}

	public function shutdown($exception = null) {

	    if ($exception === null) {
		$exception = $this->getLastException(true);
	    }	   
	    if (Helper::classExists('EventManager', true, false)) {
		EventManager::raise('shutdown', new EventParms(['lastException' => $exception])) ;
	    }
	    if (!is_object($exception)) {
		return exit();
	    }
	    $this->isJson(false);
	    if (self::isExit($exception->getCode())) {
		ob_clean();

				
		$this->raise(505,
			'<span style="color:inherit; font-weight:900;">Shutdown with BugCatcher :-(</span><br />', 
			$exception->getMessage() . ' in '
			    . str_replace(ROOT_PATH, 'ROOT_PATH', $exception->getFile()) . ' on line '
			    . $exception->getLine()
		);
	    }
	    //exit();
	}

	public static function getType($code) {
	    $type = null;
	    switch ($code) {
		case 1:
		case E_ERROR:
		case E_USER_ERROR:
		case E_CORE_ERROR:
		case E_COMPILE_ERROR:
		case E_RECOVERABLE_ERROR:
		    $type = self::ERROR;
		    break;
		case E_WARNING:
		case E_CORE_WARNING:
		case E_USER_WARNING:
		case E_COMPILE_WARNING :
		    $type = self::WARNING;
		    break;
		case E_NOTICE:
		case E_USER_NOTICE:
		    $type = self::NOTICE;
		    break;
		case E_DEPRECATED:
		case E_USER_DEPRECATED:
		    $type = self::DEPRECATED;
		    break;
		case E_STRICT :
		    $type = self::STRICT;
		    break;
		case self::EXCEPTION:
		default :
		    $type = self::EXCEPTION;
		    break;
	    }
	    return $type;
	}

	/**
	 * 
	 * @param int $code the error code
	 * @return bool
	 */
	public static function isExit($code) {
	    $type = self::getType($code);
	    return (bool) ($type == self::ERROR || $type == self::EXCEPTION);
	}

	/**
	 * @return \Exception
	 */
	function getLastException() {
	    $test = function($exeption, $error) {
		return (
			($exeption->getMessage() == $error['message']) &&
			($exeption->getFile() == $error['file']) &&
			($exeption->getLine() == $error['line'])
			);
	    };
	    $last = error_get_last();
	    if ($last)
		$this->handleError($last['type'], $last['message'], $last['file'], $last['line']);
	    if (count($this->_exceptions) > 0) {
		$index = (count($this->_exceptions) - 1);
		if ($test($this->_exceptions[$index], $last)) { //check error on Helper and co
		    return $this->_exceptions[$index];
		} else {
		    $_exceptions = array_reverse($this->_exceptions);
		    foreach ($_exceptions as $i => $exeption) {
			if ($test($exeption, $last))
			    return $exeption;
		    }
		}
	    } else {
		return null;
	    }
	}

	/**
	 * 
	 * @param \Exception $exception
	 */
	protected function log($exception) {
	    if (Helper::classExists('Config', true, false)) {
		Config::getInstance()->get('errror.log', false);
	    } else {
		$logfile = ( ini_get('error_log') ) ? ini_get('error_log') : './php-error.log.php';
		$message = (new \DateTime())->format('Y-m-d H:i:s') 
			. ': '
			. $exception->getMessage() 
			. ' on '  
			. $exception->getFile() 
			. ':[' . $exception->getLine() . ']' 
			. $exception->getTraceAsString() . PHP_EOL. PHP_EOL;
		\error_log($message, 3, $logfile);
	    }
	}

	/**
	 * @param \Exception $exception
	 * @return boolean
	 */
	public function handleException($exception) {
	    EventManager::raise('exception', new EventParms(['exception' => $exception])) ;
	    $this->_exceptions[] = $exception;
	    self::log($exception);
	    return true;
	}

	/**
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 * @param mixed $errcontext
	 * @return boolean
	 */
	public function handleError($errno, $errstr, $errfile, $errline, $errcontext = NULL) {
	    try {
		self::throwErrorException($errno, $errstr, $errfile, $errline);
	    } catch (\Exception $exc) {
		return $this->handleException($exc); //return the result handleException to disable the php error reporting
	    }
	}

	/**
	 * 
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param string $errline
	 * @throws \ErrorException
	 */
	public static function throwErrorException($errno, $errstr, $errfile, $errline) {
	    throw new \ErrorException($errstr, $errno, $errno, $errfile, $errline);
	}

	private function param($name, $default = NULL) {
	    return $this->params->get($name, $default);
	}

	protected function displayBugs() {
	    $this->_isDisplayedBugs = true;
	    $count = count($this->_exceptions);
	    $js = "toggleContainer('dbg-content-bugcatcher-$count');";
	    $html = '<div class="container" >'
		     . ' <div class="row">' . n
		    . '     <div class="span12">' . n
		    . '		<div class="accordion" id="mnhcc-bugcatcher-debug">' . n
		    . '		    <div class="accordion-group">' . n
		    . '			<div class="accordion-heading dbg-header" data-toggle="collapse" data-parent="#mnhcc-bugcatcher-debug" onclick="' . $js . '">' . n
		    . '			    <a class="accordion-toggle" href="#mnhcc-bugcatcher-debug">' . n
		    . '				Bug Catcher [' . $count . ']' . n
		    . '			    </a>' . n
		    . '			</div>' . n
		    . '			<div id="dbg-content-bugcatcher-' . $count . '" class="accordion-body collapse dbg-container" style="height:0px;">' . n;
	    $i = 0;
	    $limit = 500;
	    foreach ($this->_exceptions As $exception) {
		$i++;
		if ($i > $limit) {
		    $html .= '<b>more as ' . $limit . ' errors is to mutch! the list ist break</b>' . n;
		    break;
		}
		$memoryUsage = new Bytes(memory_get_usage());
		$memoryLimit = new Bytes(ini_get('memory_limit'));
		if ($memoryUsage->toFloat() < ($memoryLimit->toFloat() - 128)) {
		    $html .= $this->renderError($exception);
		} else {
		    $Bytes = new Bytes(memory_get_usage());
		    $html .= '<b>' . $memoryUsage->getUfriendlySize() . ' is to mutch! ' . $memoryLimit->getUfriendlySize() . ' is The Limit!</b>' . n;
		    break;
		}
	    }
	    $memoryUsage = new Bytes(memory_get_usage());
	    $memoryLimit = new Bytes(ini_get('memory_limit'));
	    $runtime = microtime(true) - (STARTTIME);
	    $html .= '<div><code>Runtime: ' . $runtime . ' ' . $memoryUsage->getUfriendlySize() . ' from max ' . $memoryLimit->getUfriendlySize() . '</code>';
	    $html .= '</div></div></div></div></div></div>';
	    return $html;
	}

	/**
	 * @staticvar string $html
	 * @staticvar int $id
	 * @param Exception $exception
	 * @return string
	 */
	protected function renderError($exception) {
	    static $id;
	    if ($id === null)
		$id = 1;
	    $html = '';
	    $js = "toggleContainer('dbgContainer_BugCatcher" . $id . "');";
	    $style = ' style="display: none;"';
	    $errorType = (is_a($exception, 'ErrorException')) ? self::FriendlyErrorType($exception->getCode()) : get_class($exception);
	    $html .= '          <div class="dbgHeader" onclick="' . $js . '">' . n
		    . '           <a href="javascript:void(0);">' . n
		    . '               <h3 title="' . self::FriendlyErrorType($exception->getCode()) . ' in ' . $exception->getFile() . '">       ' . n
		    . $exception->getMessage() . n
		    . '               </h3>' . n
		    . '           </a>' . n
		    . '          </div>' . n;
	    $html .= '          <div ' . $style . ' class="dbgContainer" id="dbgContainer_BugCatcher' . $id . '">' . n
		    . '           <p class="' . Helper::cssNameClean($errorType) . ' alert alert-info">' . n
		    . '               <b>[' . $errorType . '] </b>'
		    . $exception->getMessage() . ': in file '
		    . $exception->getFile()
		    . ' on line '
		    . $exception->getLine()
		    . '               <br /><br /><br />' . n
		    . '            </p>' . n;
	    $html .= static::renderBacktrace($exception->getTrace());
	    $html .= '          </div>' . n;
	    $id++;
	    return str_replace(ROOT_PATH, 'ROOT_PATH', $html);
	}

	public static function renderBacktrace(array $trace) {
	    $str = '<table cellpadding="0" cellspacing="0" class="table backtrace-table">' . n
		    . ' <thead>' . n
		    . '     <tr>' . n
		    . '         <th colspan="3" class="TD"><strong>Call stack</strong></th>' . n
		    . '     </tr>' . n
		    . '     <tr>' . n
		    . '         <th class="TD"><strong>#</strong></th>' . n
		    . '         <th class="TD"><strong>Function</strong></th>' . n
		    . '         <th class="TD"><strong>Location</strong></th>' . n
		    . '     </tr>' . n
		    . ' </thead>' . n
		    . ' <tbody>' . n;
	    foreach ($trace as $key => $trace) {
		$str .= static::renderTraceArray($trace, $key + 1);
	    }
	    $str .= '   </tbody>' . n
		    . '</table>' . n;
	    return $str;
	}

//        public static function renderTraceArray($trace) {
//            return '<pre>' . print_r($trace, true) . '</pre>';
//        }

	public static function renderTraceArray($trace, $key = 0) {

	    $highlight = [];
	    $highlight['keyword'] = ini_get('highlight.keyword') ? ini_get('highlight.keyword') : '#007700';
	    $highlight['comment'] = ini_get('highlight.comment') ? ini_get('highlight.comment') : '#FF8000';
	    $highlight['default'] = ini_get('highlight.default') ? ini_get('highlight.default') : '#0000BB';
	    $highlight['html'] = ini_get('highlight.html') ? ini_get('highlight.html') : '#000000';
	    $highlight['string'] = ini_get('highlight.string') ? ini_get('highlight.string') : '#DD0000';
	    $args = '';
	    if (isset($trace['args'])) {
		$args .= '<ol>';
		foreach ($trace['args'] as $arg) {
		    $args .= '<li>' . str_replace(['<pre', '</pre'], ['<code', '</code'], Helper::dump($arg)) . '</li>';
		}
		$args .= '</ol>';
	    }


	    $contents = '     <tr>' . n
		    . '         <td class="TD">' . $key . '</td>' . n;
	    if (isset($trace['class'])) {
		$contents .= '         <td class="TD" style="color:' . $highlight['default'] . '">' . $trace['class'] . '<span style="color:' . $highlight['keyword'] . '">' . $trace['type'] . '</span>' . $trace['function'] . '<span style="color:' . $highlight['keyword'] . '">(' . $args . ')</span></td>' . n;
	    } else {
		$contents .= '         <td class="TD" style="color:' . $highlight['default'] . '">' . $trace['function'] . '<span style="color:' . $highlight['keyword'] . '">(' . $args . ')</span></td>' . n;
	    }
	    if (isset($trace['file'])) {
		$contents .= '         <td class="TD" style="color:' . $highlight['string'] . '">' . $trace['file'] . ':' . $trace['line'] . '</td>' . n;
	    } else {
		$contents .= '         <td class="TD">&#160;</td>' . n;
	    }
	    $contents .= '     </tr>' . n;
	    return $contents;
	}

	public static function FriendlyErrorType($type) {
	    switch ($type) {
		case E_ERROR: // 1 //
		    return 'E_ERROR';
		case E_WARNING: // 2 //
		    return 'E_WARNING';
		case E_PARSE: // 4 //
		    return 'E_PARSE';
		case E_NOTICE: // 8 //
		    return 'E_NOTICE';
		case E_CORE_ERROR: // 16 //
		    return 'E_CORE_ERROR';
		case E_CORE_WARNING: // 32 //
		    return 'E_CORE_WARNING';
		case E_CORE_ERROR: // 64 //
		    return 'E_COMPILE_ERROR';
		case E_CORE_WARNING: // 128 //
		    return 'E_COMPILE_WARNING';
		case E_USER_ERROR: // 256 //
		    return 'E_USER_ERROR';
		case E_USER_WARNING: // 512 //
		    return 'E_USER_WARNING';
		case E_USER_NOTICE: // 1024 //
		    return 'E_USER_NOTICE';
		case E_STRICT: // 2048 //
		    return 'E_STRICT';
		case E_RECOVERABLE_ERROR: // 4096 //
		    return 'E_RECOVERABLE_ERROR';
		case E_DEPRECATED: // 8192 //
		    return 'E_DEPRECATED';
		case E_USER_DEPRECATED: // 16384 //
		    return 'E_USER_DEPRECATED';
	    }
	    return $type;
	}
	
	/**
	 * 
	 * @param \mnhcc\ml\classes\EventParms $eventArgs
	 */
	public static function onTemplateCreated(template\EventParms $eventArgs) {
	    $template = $eventArgs->getTemplate();
	    if( method_exists($template, 'addStyle') ) {
		$template->addStyle('error');
		$template->addStyle('debug');
	    }
	}

	public static function ___onLoaded() {
	    self::$___require = [
		ClassHandler::TYPE_CLASS => __NAMESPACE__ .'\\Helper',
		ClassHandler::TYPE_CLASS => __NAMESPACE__ .'\\Bytes'];
	    
	    return ClassHandler::Load('Helper', true, ClassHandler::TYPE_CLASS) &&
		ClassHandler::Load('Bytes', true, ClassHandler::TYPE_CLASS) &&
		ClassHandler::Load('Bootstrap', false, ClassHandler::TYPE_CLASS);
	}
//		public function renderBacktrace($exception) {
//			return '<pre>'.htmlentities(print_r($exception, true)).'</pre>';
//		}
    }

}