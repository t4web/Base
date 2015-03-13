<?php

namespace T4webBaseTest\Domain\Criteria\TestAsset;

use T4webBase\Domain\Criteria\AbstractCriteria;

class CriteriaWithExceptionBuildMethod extends AbstractCriteria {
    
    protected $table = 'fooTable';
    protected $buildMethod = 'group';
}