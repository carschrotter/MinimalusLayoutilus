<?php

namespace mnhcc\ml\classes\records;

use mnhcc\ml\traits as traits;
use mnhcc\ml\classes as classes;
{

	/**
	 * Description of employeeRecord
	 *
	 * @author Michael Hegenbarth (carschrotter)
	 * @package Tasktus
	 */
	class EmployeeRecord extends classes\Record {

		use traits\Viewable;

		public function getSelectList($selected = false) {
			$str = '';
			foreach ($this as $row) {
				$str .= '<option';
				$name = 'no value';
				foreach ($row as $key => $value) {
					if ($key == 'id')
						$str .= ($selected == $key) ? ' selected="true"' : '';
					$str .= ' value="' . $value . '"';
					if ($key == 'name') {
						$name = $value;
					}
					$str .= ' data-' . $key . '="' . $value . '"';
				}
				$str .= '>' . $name . '</option>';
			}

			return $str;
		}

	}

}