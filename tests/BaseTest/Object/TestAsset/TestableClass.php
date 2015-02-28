<?php

namespace BaseTest\Object\TestAsset;

use Base\Object\HydratingObject;

class TestableClass extends HydratingObject {
    
    public $publicProperty;
    
    protected $protectedProperty;
    
    private $privateProperty;
    
}
