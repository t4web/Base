<?php

namespace BaseTest\Domain\Criteria\TestAsset;

use Base\Domain\Criteria\AbstractCriteria;

class CriteriaWithExceptionBuildMethod extends AbstractCriteria {
    
    protected $table = 'fooTable';
    protected $buildMethod = 'group';
}