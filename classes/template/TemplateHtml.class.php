<?php

namespace mnhcc\ml\classes\template {

    use mnhcc\ml\classes;

    /**
     * Description of Template
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    class TemplateHtml extends classes\Template {

	const SCRIPT_TEXT = 'text';
	const SCRIPT_AUTOPATH = 'autopath';
	const STYLE_TEXT = 'text';
	const CSS_AUTOPATH = 'autopath';

	public $script = [];
	public $style = [];
	public $head = [];
	protected $_template = '';
	protected $_ScriptLocation = 'assets/js/';
	protected $_CSSLocation = 'assets/css/';

	/**
	 * Method to extract key/value pairs out of a string with XML style attributes
	 * @param   string  $string  String containing XML style attributes
	 * @return  array  Key/Value pairs for the attributes
	 * @from Joomla   11.1
	 */
	public static function parseAttributes($string) {
	    // Initialise variables.
	    $attr = array();
	    $retarray = array();

	    // Let's grab all the key/value pairs using a regular expression
	    preg_match_all('/([\w:-]+)[\s]?=[\s]?"([^"]*)"/i', $string, $attr);

	    if (classes\ArrayHelper::isArray($attr)) {
		$numPairs = count($attr[1]);
		for ($i = 0; $i < $numPairs; $i++) {
		    $retarray[$attr[1][$i]] = $attr[2][$i];
		}
	    }

	    return $retarray;
	}

	public function addStyle($options) {
	    if (classes\ArrayHelper::isArray($options)) {
		$autopath = isset($options[self::CSS_AUTOPATH]) ? $options[self::CSS_AUTOPATH] : true;
		$href = isset($options['href']) ? $options['href'] : false;

		if (!isset($options['type'])) {
		    $options['type'] = 'text/css';
		} elseif (!$options['type']) {
		    unset($options['type']);
		}

		if (!isset($options['rel'])) {
		    $options['rel'] = 'stylesheet';
		} elseif (!$options['rel']) {
		    unset($options['rel']);
		}

		if ($href) {
		    $options['href'] = $this->getCSSPath($href, $autopath);
		}
	    } else {
		$options = ['href' => $this->getCSSPath($options, true)];
		$options['type'] = 'text/css';
		$options['rel'] = 'stylesheet';
	    }
	    $this->style[] = $options;
	}

	public function addScript($options) {
	    if (classes\ArrayHelper::isArray($options)) {
		$autopath = isset($options[self::SCRIPT_AUTOPATH]) ? $options[self::SCRIPT_AUTOPATH] : true;
		$src = isset($options['src']) ? $options['src'] : false;

		if (!isset($options['type'])) {
		    $options['type'] = 'text/javascript';
		} elseif (!$options['type']) {
		    unset($options['type']);
		}

		if ($src) {
		    $options['src'] = $this->getScriptPath($src, $autopath);
		}
	    } else {
		$options = ['src' => $this->getScriptPath($options, true)];
		$options['type'] = 'text/javascript';
	    }
	    $this->script[] = $options;
	}

	public function getScriptLocation() {
	    return $this->_ScriptLocation;
	}

	public function getCSSLocation() {
	    return $this->_CSSLocation;
	}

	public function setScriptLocation($ScriptLocation) {
	    return $this->_ScriptLocation = $ScriptLocation;
	}

	public function setCSSLocation($CSSLocation) {
	    return $this->_CSSLocation = $CSSLocation;
	}

	public function getCSSPath($href, $auto_complete = true) {
	    $extention = (bool) (count(explode('.', $href)) - 1);
	    if ($auto_complete) {
		return $this->base() . $this->getCSSLocation() . $href . (($extention) ? '' : '.css');
	    } else {
		return $href;
	    }
	}

	public function getScriptPath($src, $auto_complete = true) {
	    $extention = \array_shift(\explode('.', $src));
	    if ($auto_complete) {
		return $this->base() . $this->getCSSLocation() . $src . ($extention) ? '' : '.js';
	    } else {
		return $src;
	    }
	}

	public function getBuffer($type, $name, $attribs) {
	    static $programm;
	    $programm = ($programm) ? $programm : classes\Programm::getInstance();
	    if ($type == 'head') {
		return $this->_renderHead($name, $attribs);
	    } else {
		return $programm->get($type, $name, $attribs);
	    }
	}

	/**
	 * 
	 * @param array $params
	 * @return string
	 */
	public function render($params = array()) {
	    classes\EventManager::raise('beforeRender', new EventParms($this, []));
	    $this->_fetchTemplate($params);
	    $this->_parseTemplate();
	    return $this->_renderTemplate();
	}

	public function renderMsg(array $msg) {
	    $str = '<div class="">';
	    foreach ($msg as $args) {
		$str .= '<div class="alert alert-' . $args['type'] . '">';
		if ($args['heading']) {
		    $str .= '<h3>' . $args['heading'] . '</h3>';
		}
		$str .= '<span class="msg">' . $args['msg'] . '</span>';
		$str .= '</div>';
	    }
	    $str .= '</div>';
	    return $str;
	}

	/**
	 * Fetch the template, and initialise the params
	 * @param   array  $params  Parameters to determine the template
	 * @return \mnhcc\ml\classes\template\TemplateHtml instance of $this to allow chaining
	 * @from Joomla   11.1
	 */
	protected function _fetchTemplate($params = array()) {
	    // Check
	    $directory = isset($params['directory']) ? $params['directory'] : 'templates';
	    $template = isset($params['template']) ? $params['template'] : 'default';

	    $file = $directory . DS . 'template.' . $template . php;

	    if (!file_exists($file)) {
		$this->_template = '<!DOCTYPE html>
<html>
    <body>
        <head>
            <meta charset="utf-8">
        </head>
        <h1>No Template!</h1>
        <h4>"' . $file . '"</h4>
        <div>
            <mnhccTemplate:include type="system" name="message" />
            <mnhccTemplate:include type="component" name="content" />
        </div>
    </body>
</html>';
	    } else {
		ob_start();
		include $file;
		$this->_template = ob_get_contents();
		ob_end_clean();
	    }
	    return $this;
	}

	/**
	 * Parse a document template
	 * @return \mnhcc\ml\classes\template\TemplateHtml instance of $this to allow chaining
	 * @from foomla   11.1
	 */
	protected function _parseTemplate() {
	    $matches = [];

	    if (preg_match_all('~<mnhccTemplate:include\ type="([^"]+)" (.*)\/>~iU', $this->_template, $matches)) {
		$template_tags_first = [];
		$template_tags_last = [];

		// Step through the jdocs in reverse order.
		for ($i = count($matches[0]) - 1; $i >= 0; $i--) {
		    $type = $matches[1][$i];
		    $attribs = empty($matches[2][$i]) ? array() : self::parseAttributes($matches[2][$i]);
		    $name = isset($attribs['name']) ? $attribs['name'] : null;

		    // Separate buffers to be executed first and last
		    if ($type == 'module' || $type == 'modules') {
			$template_tags_first[$matches[0][$i]] = array('type' => $type, 'name' => $name, 'attribs' => $attribs);
		    } else {
			$template_tags_last[$matches[0][$i]] = array('type' => $type, 'name' => $name, 'attribs' => $attribs);
		    }
		}
		// Reverse the last array so the jdocs are in forward order.
		$template_tags_last = array_reverse($template_tags_last);

		$this->_template_tags = $template_tags_first + $template_tags_last;
	    }

	    return $this;
	}

	protected function _cmp($a, $b) {
	    $type = [
		'a' => strtolower($a['type']),
		'b' => strtolower($b['type'])
	    ];
	    switch ($type['a']) {
		case 'modul': case 'module':
		    if (in_array($type['b'], ['component']))
			return 1;
		    if (in_array($type['b'], ['head', 'system']))
			return -1;
		    return 0;
		    break;
		case 'component':
		    if (in_array($type['b'], ['modul']))
			return 1;
		    if (in_array($type['b'], ['head', 'system']))
			return -1;
		    return 0;
		    break;
		case 'system':
		    if (in_array($type['b'], ['system']))
			return 0;
		    return 1;
		    break;
		case 'head':
		    if (in_array($type['b'], ['head']))
			return 0;
		    if (in_array($type['b'], ['system']))
			return -1;
		    return 1;
		    break;
		default:
		    break;
	    }
	}

	/**
	 * Render pre-parsed template
	 * @return string rendered template
	 * @from foomla   11.1
	 */
	protected function _renderTemplate() {
	    $replace = [];
	    $with = [];
	    uasort($this->_template_tags, [$this, '_cmp']);
	    foreach ($this->_template_tags as $jdoc => $args) {
		$replace[] = $jdoc;
		${'&content'} = &$with[];
		try {
		    if (strtolower($args['type']) == 'component') {
			${'&component'} = & ${'&content'};
		    }
		    if (!$this->isError() || ($args['type'] != 'modul' && $args['type'] != 'component')) {
			${'&content'} = $this->getBuffer($args['type'], $args['name'], $args['attribs']);
		    } elseif (strtolower($args['type']) != 'system') {
			${'&content'} = '';
		    }
		} catch (classes\Exception\RenderException $exc) {
		    ${'&content'} = '<pre>' . $exc->getMessage() . '</pre>';
		}
	    }

	    if ($this->isError()) {
		${'&component'} = $this->_renderErrors();
	    }
	    return str_replace($replace, $with, $this->_template);
	}

	protected function _renderErrors() {
	    $str = '';
	    if (classes\Router::isDebug()) {
		foreach ($this->getErrors() as $error) {
		    $str .= '<div class="alert alert-error"><h4>Error ' . $error['code'] . '</h4><p>' . $error['msg'] . '</p></div>';
		}
	    } else {
		$code = classes\Error::getInstance()->getCode();
		$str .= $this->getErrorTemplate($code);
	    }
	    return $str;
	}

	public function getErrorTemplate($code) {
	    $error_template = false;
	    $file = 'templates/error/template.default.php';
	    ob_start();
	    include $file;
	    $error_template = ob_get_contents();
	    ob_end_clean();
	    return $error_template;
	}

	protected function _renderHead($name, $attribs) {

	    classes\EventManager::raise('renderHead', new classes\EventParms([
		'type' => $name,
		'attr' => $attribs
	    ]));

	    switch ($name) {
		case 'script':
		    return $this->_renderHeadScript($attribs);
		    break;
		case 'style' :
		    return $this->_renderHeadStyle($attribs);
		default:
		    return '';
		    break;
	    }
	}

	protected function _renderHeadStyle($attribs) {
	    $style = '';
	    foreach ($this->style as $stylearr) {
		$parm = '';
		$text = false;
		if (isset($stylearr[self::STYLE_TEXT]) && isset($stylearr['rel'])) {
		    unset($stylearr['rel']);
		}
		foreach ($stylearr as $key => $value) {
		    if ($key !== self::STYLE_TEXT) {
			$parm .= $key . '="' . $value . '" ';
		    } else {
			$text = $value;
		    }
		}
		if ($text) {
		    $style .= sprintf('<style %s>%s</style>' . n, $parm, $text);
		} else {
		    $style .= sprintf('<link %s />' . n, $parm);
		}
	    }
	    return $style;
	}

	protected function _renderHeadScript($attribs) {
	    $script = '';
	    foreach ($this->script as $scriptarr) {
		$script .= '<script%s>%s</script>';
		$parm = '';
		$text = '';
		foreach ($scriptarr as $key => $value) {
		    if ($key !== self::SCRIPT_TEXT) {
			$parm .= ' ' . $key . '="' . $value . '"';
		    } else {
			$text = $value;
		    }
		}
		$script = sprintf($script, $parm, $text);
	    }
	    return $script;
	}

    }

}