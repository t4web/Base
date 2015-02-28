<?php

namespace Base\Domain;

use Base\Object\HydratingObject;

class Entity extends HydratingObject implements EntityInterface {
    
    protected $id;
    
    public function __construct(array $data = array()) {
        $this->populate($data);
    }
    
    public function getId() {
        return $this->id;
    }
}
