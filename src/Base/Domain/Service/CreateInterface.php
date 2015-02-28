<?php

namespace Base\Domain\Service;

interface CreateInterface {
    
    public function isValid(array $data);
    
    public function create($id = null);
    
    public function getMessages();
    
    public function getValues();
}
