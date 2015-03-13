<?php

namespace T4webBase\Domain;

use T4webBase\Object\HydratingObjectInterface;

interface EntityInterface extends HydratingObjectInterface {
    
    public function getId();
    
}
