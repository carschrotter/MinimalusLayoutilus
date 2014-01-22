<?php

namespace mnhcc\ml\classes\template;

use mnhcc\ml\classes as classes; {

    /**
     * Description of Template
     *
     * @author Michael Hegenbarth (carschrotter)
     * @package MinimalusLayoutilus	 
     */
    class TemplateHeap extends \SplHeap  {

        public function compare($a, $b) {
            $type = [
                'a' => strtolower($a['type']),
                'b' => strtolower($b['type'])
            ];
            switch ($type['a']) {
                case 'modul':
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
        
        public function insert($value) {
            if(classes\Helper::isArray($value) || is_a($value, '\\ArrayObject')) {
                parent::insert($value);
            } else {
                throw new \InvalidArgumentException('Wrong parameter type! Array or ArrayObject is needed');
            }
        }
    }
}