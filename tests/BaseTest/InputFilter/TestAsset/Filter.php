<?php

namespace BaseTest\InputFilter\TestAsset;

use Base\InputFilter\Element\Id;
use Base\InputFilter\Filter as BaseFilter;

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
