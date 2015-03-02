<?php

namespace Base\Domain\Service;

interface CreateInterface {
    
    public function isValid(array $data);
    
    public function create(array $data);
    
    public function getErrors();
    
    public function getValues();
}
