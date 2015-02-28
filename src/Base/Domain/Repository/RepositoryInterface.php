<?php

namespace Base\Domain\Repository;

use Base\Domain\Entity;

interface RepositoryInterface {
    
    public function add(Entity $entity);
    
}
