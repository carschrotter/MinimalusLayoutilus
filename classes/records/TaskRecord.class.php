<?php
namespace mnhcc\ml\classes\records;
use mnhcc\ml as root;
use mnhcc\ml\classes as classes;

{
	/**
	 * Description of Record
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package Tasktus	 
	 */
	class TaskRecord extends classes\Record {

		public function getHtmlRows($prefix = 'HAED_', array $rows = [], array $options = []) {
			$config = classes\Config::getInstance();
			$prefix = ($prefix == null) ? 'HAED_' : $prefix;
			$output = '';
			foreach ($this as $row) {
				$class = ($row['selected'] && $row['checked']) 
						? $config->classes->checked->class
						: ( ($row['selected']) 
							? $config->classes->selected->class
							: $config->classes->nothing->class );

				$output .= '<tr data-task="'. $row['taskId'] . '" data-checked="' 
						. $row['checked'] . '" data-selected="' 
						. $row['selected'] . '" id="task'
						. $row['taskId'] . '" class="' 
						. $class . '">'.n;

				foreach ($row as $key => $value) {
					if ( array_key_exists($key, $options)) {
						if (is_callable($options[$key])) {
							$param_arr = [$key, $row, $options, & $output, & $value];
							call_user_func_array($options[$key], $param_arr);
						} 
					}
					if ( $this->isDisplayed($key, $prefix, $rows) ) {
						$output .= '	<td data-caption="'.$key.'">' . $value . '</td>'.n;
					}

				}

				$icon = ($row['selected'] && $row['checked']) 
					? $config->classes->checked->icon
					: ( ($row['selected']) 
						? $config->classes->selected->icon
						: $config->classes->nothing->icon );
				//$icon = 'icon-ok'; //icon-remove // icon-time
				$output .= '	<td><i id="icon'. $row['taskId'] . '" class="' . $icon . '"></i></td>'.n;
				$output .= '</tr>'.n;
			}
			return $output;
		}

	}
}