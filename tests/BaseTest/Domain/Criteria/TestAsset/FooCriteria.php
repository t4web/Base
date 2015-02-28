<?php

namespace BaseTest\Domain\Criteria\TestAsset;

use Base\Domain\Criteria\AbstractCriteria;

class FooCriteria extends AbstractCriteria {
    
    protected $field = 'foo';
    protected $table = 'fooTable';
    protected $buildMethod = 'addFilterMoreOrEqual';
    
}