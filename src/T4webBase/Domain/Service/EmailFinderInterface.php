<?php

namespace T4webBase\Domain\Service;

interface EmailFinderInterface {
    
    public function findByEmail($email);
}
