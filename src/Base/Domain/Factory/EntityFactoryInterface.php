<?php

namespace Base\Domain\Factory;

interface EntityFactoryInterface {
    
    public function create(array $data);

    public function createCollection(array $data);
    
}
