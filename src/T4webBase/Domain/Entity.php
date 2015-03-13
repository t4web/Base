<?php

namespace T4webBase\Domain;

use T4webBase\Object\HydratingObject;

class Entity extends HydratingObject implements EntityInterface {
    
    protected $id;
    
    public function __construct(array $data = array()) {
        $this->populate($data);
    }
    
    public function getId() {
        return $this->id;
    }
}
