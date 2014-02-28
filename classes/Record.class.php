<?php

namespace mnhcc\ml\classes;

use \mnhcc\ml\classes\Exception as exception;
use \mnhcc\ml\interfaces as interfaces;
use \mnhcc\ml\traits as traits;
{

	/**
	 * Description of Record
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package MinimalusLayoutilus
	 * @copyright (c) 2013, Michael Hegenbarth
	 */
	class Record extends \ArrayObject implements interfaces\Viewable, interfaces\MNHcC {

		use traits\MNHcC;
		use traits\Viewable;

		/**
		 * Constant: set the value not
		 */
		const NOREPLACEVALUE = null;
		
		/**
		 *
		 * @var type 
		 */
		protected $prefixes = [];
		
		protected $options = [
			'head' => ['HAED_', '_', []],
			'rows' => ['HAED_'],
		];

		public function setOptions($name, $options, $merge = true) {
			if ($merge)
				$options = array_merge($this->options[$name], $options);
			$this->options[$name] = $options;
		}

		public function getPrefixes() {
			return $this->prefixes;
		}

		public function setPrefixes($prefixes, $merge = true, $overite = true) {
			if (!is_array($prefixes))
				$prefixes = [$prefixes];
			if ($merge)
				$prefixes = ($overite) ?
						array_merge($this->prefixes, $prefixes) :
						array_merge($prefixes, $this->prefixes);
			$this->prefixes = $prefixes;
		}

		public function argsMerge() {

			$args = func_get_args();
			$array = array_shift($args);
			foreach ($args as $args_array) {
				foreach ($args_array as $key => $item) {
					if ($item !== self::NOREPLACEVALUE) {
						$array[$key] = $item;
					} else
						$array[$key] = ($array[$key]) ? $array[$key] : null;
				}
			}
			return $array;
		}

		/**
		 * function that returns a formatted html table.<br>
		 * <b>Example:<b>
		 * <code>
		 * <?php
		 * echo $obj->getHtmlTable(
		 *	['class' => "table-striped"],
		 *	[ 1 => ['-'] ], //Handing over of the second parameter. <b>Note 1 as the key!</b>
		 *	//So you can omit parameters, it is important for the merging of the parameters
		 *	[['HAED_customer' => 'View::do_something'], Record::NOREPLACEVALUE, ['row']]
		 *	//with the constant "NOREPLACEVALUE" you can omit parameters, again, 
		 *  //this is important for the merging of the parameters
		 * );
		 * ?>
		 * </code>
		 * <b>Example output:<b>
		 * <code>
		 * <table class="table-striped"><tbody>
		 * <thead><tr><th>Customer</th><th>Task</th><th>Description</th><th>Employee</th><th>From</th></tr></thead>
		 * <tr>
		 * 	<td>Beispielkunde</td>
		 * 	<td>Test the script</td>
		 * 	<td>testing this script</td>
		 * 	<td>Michael Hegenbarth</td>
		 * 	<td>2013-03-04 14:20:16</td>
		 * </tr>
		 * <tr>
		 * 	<td>Max Musterman</td>
		 * 	<td>Test the script</td>
		 * 	<td>testing this script</td>
		 * 	<td>Claudia Cloudison</td>
		 * 	<td>2013-03-04 14:20:16</td>
		 * </tr>
		 * </tbody></table>
		 * </code>
		 * @param array $Options
		 * @param array $headArgs an array with the arguments, to call the function "getHtmlHead"
		 * @param array $rowsArgs an array with the arguments, to call the function "getHtmlRows"
		 * @return string an HTML Table
		 */
		public function getHtmlTable(array $Options = [], array $headArgs = [], array $rowsArgs = []) {
			$class = (isset($Options['class'])) ? $Options['class'] : 'table';
			
			$rv = '<table' . ( ($class == '') ? '' : ' class="'.$class.'"' ) . '><tbody>'.n;
			
			if (isset($Options['prefix'])) {
				$this->options['rows'][0] = $Options['prefix'];
				$this->options['head'][0] = $Options['prefix'];
			}
			if (isset($Options['columns'])) {
				$this->options['rows'][1] = $Options['columns'];
				$this->options['head'][2] = $Options['columns'];
			}


			$haedArgs = ($headArgs) ? $headArgs : [];
			$haedArgs = $this->argsMerge($this->options['head'], $haedArgs);
			//Programm::getInstance()->msg(Helper::dump($haedArgs), 'info' , '$haedArgs :');
			//call $this->getHtmlHead
			$rv .= call_user_func_array([$this, 'getHtmlHead'], $haedArgs);

			$rowsArgs = ($rowsArgs) ? $rowsArgs : [];
			$rowsArgs = $this->argsMerge($this->options['rows'], $rowsArgs);
			//Programm::getInstance()->msg(Helper::dump($rowsArgs), 'info' , '$rowsArgs :');
			//call $this->getHtmlRows
			$rv .= call_user_func_array([$this, 'getHtmlRows'], $rowsArgs);

			$rv .= '</tbody></table>';
			return $rv;
		}

		protected function renderCaption($key, $prefix = 'HAED_', $blank = '_') {
			return ucfirst(str_replace($blank, ' ', str_replace($prefix, '', $key)));
		}

		/**
		 * 
		 * @param string $prefix the Prefix: All keys are used to start
		 * @param string $blank sign to be replaced to blank. by default "_"
		 * @param array $columns if necessary, add additional columns added
		 * @return string
		 */
		public function getHtmlHead($prefix = 'HAED_', $blank = '_', array $columns = [], array $options = []) {
			$columns = (Helper::isArray($columns)) ? $columns : [];
			$prefix = ($prefix === null) ? 'HAED_' : $prefix;
			$output = '<thead><tr>';
			foreach ($this[0] as $key => $value) {
				if ($this->isDisplayed($key, $prefix, $columns)) {
					if (in_array($key, $columns)) {
						unset($columns[array_search($key, $columns)]);
					}
					$output .= '<th>' . $this->renderCaption($key, $prefix, $blank) . '</th>';
				}
			}
			foreach ($columns as $caption) {
				$output .= '<th>' . $this->renderCaption($caption, $prefix, $blank) . '</th>';
			}

			$output .= '</tr></thead>';
			return $output;
		}

		/**
		 * this column should be displayed?
		 * @param string $key
		 * @param array $prefixes
		 * @param array $columns
		 * @return boolean
		 */
		public function isDisplayed($key, $prefixes, array $columns = []) {
			//wrapped the key in array
			$prefixes = (Helper::isArray($prefixes)) ? $prefixes : [$prefixes];
			$prefixes = array_merge($prefixes, $this->prefixes);
			if (key_exists($key, $columns)) {
				if(!is_string($columns[$key]) ) {
					return (bool) $columns[$key];
				}
			} 
			foreach ($prefixes as $prefix) {
				if (($prefix === '') || ($key != '' && strpos($key, $prefix) !== false)) {
					return true;
				}
			}
			return in_array($key, $columns);
		}

		public function getHtmlRows($prefix = 'HAED_', array $columns = [], array $options = []) {
			$prefix = (string) ($prefix === null) ? 'HAED_' : $prefix;
			$rv = '';
			foreach ($this as $row) {
				$rv .= '<tr>';
				foreach ($row as $key => $value) {
					$value = ($value) ? $value : '&nbsp';
					if ($this->isDisplayed($key, $prefix, $columns)) {
						$rv .= '<td>' . $value . '</td>';
					}
				}
				$rv .= '</tr>';
			}
			return $rv;
		}

	}

}