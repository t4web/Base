<?php

namespace Base\Domain;

use Base\Object\HydratingObjectInterface;

interface EntityInterface extends HydratingObjectInterface {
    
    public function getId();
    
}
