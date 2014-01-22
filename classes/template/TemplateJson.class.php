<?php
namespace mnhcc\ml\classes\template;
use mnhcc\ml\classes as classes;
{
	/**
	 * Description of Template
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus	 
	 */
	class TemplateJson extends classes\Template{
		protected static $_defDocType = 'json';
		
		protected $_tags = [ ];

		public function __construct() {
			parent::__construct();
			classes\Error::getInstance()->isJson(true);
			
			$this->parm = classes\Parm::getInstance();
			$attribs = ['action' => $this->parm->get('action'), 'renderType' => static::$_defDocType];
			if( $this->parm->is('model') && $this->parm->is('control') && $this->parm->is('control', 'js')  ){
				$this->_tags[] = ['type' => 'modul', 'name' => $this->parm->get('model'), 'attribs' => ['action' => $this->parm->get('action'), 'renderType' => $this->type] ];
			} else {
				$this->_tags[] = ['type' => 'component', 'name' => 'content', 'attribs' => $attribs];
			}
			$this->_tags[] = [
				'type' => 'system', 
				'name' => 'message', 
				'attribs' => $attribs];
		}
		/**
		 * 
		 * @param array $params
		 * @return string
		 */
		public function render($params = array()) {
			return $this->_renderTemplate();
		}
		/**
		 * Render pre-parsed template
		 * @return string rendered template
		 */
		protected function _renderTemplate()
		{
			$buffer = [];
			if (!$this->isError()) {
				foreach ($this->_tags as $args)
				{
					try {
						$buffer[$args['type']][$args['name']] = $this->getBuffer($args['type'], $args['name'], $args['attribs']);
					} catch (classes\exception\RenderException $exc) {
						if (!$this->isError()) {
							$this->error(404, $exc->getMessage());
						}
					}
				}
			}
			$buffer['error'] = $this->_renderErrors();
			$buffer['action'] = $this->parm->get('action');
			return json_encode($buffer);
		}
		protected function _renderErrors() {
			return (array) $this->getErrors();
		}

		public function getBuffer($type, $name, $attribs)
		{
			static $programm;
			$programm = ($programm) ? $programm : classes\Programm::getInstance();
			return $programm->get($type, $name, $attribs);
		}
		
		/**
		 * Render the messages. Call from mnhcc\ml\classes\Programm
		 * @param array $msg [ ['msg' => string, 'type' => string, 'heading' => string], ... ]
		 * @return object
		 */
		public function renderMsg(array $msg) {
			return (object) $msg;
		}

	}
}