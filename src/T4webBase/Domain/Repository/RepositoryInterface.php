<?php

namespace T4webBase\Domain\Repository;

use T4webBase\Domain\Entity;

interface RepositoryInterface {
    
    public function add(Entity $entity);
    
}
