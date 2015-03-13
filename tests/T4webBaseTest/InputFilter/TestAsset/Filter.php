<?php

namespace T4webBaseTest\InputFilter\TestAsset;

use T4webBase\InputFilter\Element\Id;
use T4webBase\InputFilter\Filter as BaseFilter;

class Filter extends BaseFilter {
    
    public function __construct() {
        $this->add(new Id('id'));
    }
    
    public function getValuesByModules() {
        return array(
            'test' => array(
                'id' => $this->getValue('id'),
            ),
        );
    }
    
}
