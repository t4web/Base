<?php

namespace T4webBase\Domain\Mapper;

use T4webBase\Object\HydratingObjectInterface;

interface DbMapperInterface {
    
    public function toTableRow(HydratingObjectInterface $hydratingObject);
    
    public function fromTableRow(array $row);
    
    public function fromTableRows(array $rows);
}
