<?php

namespace T4webBaseTest\Domain\Assets;

use T4webBase\Domain\Entity;

class EntityWithName extends Entity {
    
    protected $name;
    
    public function getName() {
        return $this->name;
    }
}
