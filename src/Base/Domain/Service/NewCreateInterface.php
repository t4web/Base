<?php

namespace Base\Domain\Service;

interface NewCreateInterface {
    
    public function create(array $data);
    
    public function getMessages();
}
