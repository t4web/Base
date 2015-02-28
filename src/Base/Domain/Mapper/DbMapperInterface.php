<?php

namespace Base\Domain\Mapper;

use Base\Object\HydratingObjectInterface;

interface DbMapperInterface {
    
    public function toTableRow(HydratingObjectInterface $hydratingObject);
    
    public function fromTableRow(array $row);
    
    public function fromTableRows(array $rows);
}
