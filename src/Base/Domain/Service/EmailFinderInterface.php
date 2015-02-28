<?php

namespace Base\Domain\Service;

interface EmailFinderInterface {
    
    public function findByEmail($email);
}
