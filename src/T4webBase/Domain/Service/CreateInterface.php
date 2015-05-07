<?php

namespace T4webBase\Domain\Service;

interface CreateInterface {
    
    public function isValid(array $data);
    
    public function create(array $data);
    
    public function getValues();
}
