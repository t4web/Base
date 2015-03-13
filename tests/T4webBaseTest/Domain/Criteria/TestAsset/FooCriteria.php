<?php

namespace T4webBaseTest\Domain\Criteria\TestAsset;

use T4webBase\Domain\Criteria\AbstractCriteria;

class FooCriteria extends AbstractCriteria {
    
    protected $field = 'foo';
    protected $table = 'fooTable';
    protected $buildMethod = 'addFilterMoreOrEqual';
    
}