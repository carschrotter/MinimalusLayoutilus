<?php

namespace mnhcc\ml\interfaces {

    /**
     * Interface for View
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	
     * @copyright (c) 2013, Michael Hegenbarth 
     */
    interface View {


	public static function getView($type, $class);

	public function getViewBaeName();

	/**
	 * 
	 * @param string $name the called method name
	 * @return string base name from template
	 */
	public function getMethodBaeName($name);

	/**
	 * 
	 * @param string $name the called method name
	 * @return string the pat to templatefile
	 */
	public function getTemplatePath($name);

	public function getTemplatePathAlias($method, $view);

	/**
	 * 
	 * @param string $name
	 * @param array $arguments
	 * @return string
	 * @throws Exception\ComponentRendererNotFoundException
	 * @throws Exception\ModulRendererNotFoundException
	 * @throws \Exception
	 */
	public function __call($name, $arguments);

	/**
	 * render a template for the called view element
	 * @param string $name called method name. Example: renderComponentIndex() or RenderModul()
	 * @param array $arguments
	 * @return mixed string on succes or false on failure
	 */
	public function renderTemplate($name, $arguments);
    }

}