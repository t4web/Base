<?php
/**
 * @todo Возможно ей будет лучше в Base\Object
 */
namespace Base\Domain\Factory;

interface ObjectFactoryInterface {
    
    public function create(array $data);
    
    public function createCollection(array $data);
    
}
