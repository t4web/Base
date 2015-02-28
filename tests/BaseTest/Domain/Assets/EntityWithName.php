<?php

namespace BaseTest\Domain\Assets;

use Base\Domain\Entity;

class EntityWithName extends Entity {
    
    protected $name;
    
    public function getName() {
        return $this->name;
    }
}
