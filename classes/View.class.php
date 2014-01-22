<?php

namespace mnhcc\ml\classes {

	/**
	 * Description of View
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	
	 * @copyright (c) 2013, Michael Hegenbarth 
	 */
	abstract class View extends MNHcC{

		use \mnhcc\ml\traits\Instances;

		/**
		 *
		 * @var \Exception
		 */
		private static $Exception = false;
		
		/**
		 *
		 * @var array 
		 */
		protected $_templatePathAlias = [];

		/**
		 * get the selected View object
		 * @param string $type example HTML
		 * @param \mnhcc\ml\classes\ReflectionClass $class
		 * @return \mnhcc\ml\classes\View
		 * @throws \mnhcc\ml\classes\exception\ViewNotFoundException
		 * @throws \ReflectionException
		 */
		public static function getView($type, $class) {
			self::$Exception = false;
			$rvClass = new ReflectionClass($class);
			$cName = $rvClass->getReplacedMasterClass('View', true) . ucfirst($type);
			try {
				$rfc = new ReflectionClass($cName);
			} catch (\ReflectionException $exc) {
				if ($rvClass) {
					self::$Exception = new exception\ViewNotFoundException($cName, $exc);
				} else {
					self::$Exception = $exc;
				}
				throw self::$Exception;
			}
			return $rfc->callStatic('getInstance');
		}
		
		public function getGetViewLastException() {
			return self::$Exception;
		}

		public function getViewBaeName() {
			return str_replace(__CLASS__, '', get_class($this));
		}

		/**
		 * 
		 * @param string $name the called method name
		 * @return string base name from template
		 */
		public function getMethodBaeName($name) {
			return strtolower(str_replace('render', '', $name));
		}

		/**
		 * 
		 * @param string $name the called method name
		 * @return string the pat to templatefile
		 */
		public function getTemplatePath($name) {
			$view = $this->getViewBaeName();
			$method = $this->getMethodBaeName($name);
			$alias = $this->getTemplatePathAlias($method, $view);
			
			if($alias) {
				return $alias;
			} else {
				return Config::getInstance()->get('basepath', '') . 'template' . DS . 'view' . DS
					. $view . DS . $method . '.view.php';
			}
		}

		public function getTemplatePathAlias($method, $view) {
			return (isset($this->_templatePathAlias[$method])) ? $this->_templatePathAlias[$method] : false;
		}

		/**
		 * 
		 * @param string $name
		 * @param array $arguments
		 * @return string
		 * @throws exception\ComponentRendererNotFoundException
		 * @throws exception\ModulRendererNotFoundException
		 * @throws \Exception
		 */
		public function __call($name, $arguments) {
			$template = call_user_func_array([$this, 'renderTemplate'], [$name, $arguments]);
			// is template found returnd the template
			if($template !== false) {
				return $template;
			}
			// raise a ComponentRendererNotFoundException
			if (strpos($name, 'renderComponent') !== false) {
				throw new exception\ComponentRendererNotFoundException($name, get_class($this), -1);
			}
			// raise a ModulRendererNotFoundException
			if (strpos($name, 'renderModul') !== false) {
				throw new exception\ModulRendererNotFoundException($name, get_class($this), -1);
			}
			// default Exception
			throw new \Exception('No Method ' . $name . '() implement in ' . get_class($this), -1);
		}
		
		/**
		 * render a template for the called view element
		 * @param string $name called method name. Example: renderComponentIndex() or RenderModul()
		 * @param array $arguments
		 * @return mixed string on succes or false on failure
		 */
		public function renderTemplate($name, $arguments) {
			$template = $this->getTemplatePath($name);
			$answer = false;
			if(\file_exists($template)) {
				\ob_start();
				$parm = \array_shift($arguments);
				include $template;
				$answer = \ob_get_contents();
				\ob_end_clean();
			}
			return $answer;
		}

	}

}