<?php
/**
 * @todo Возможно ей будет лучше в T4webBase\Object
 */
namespace T4webBase\Domain\Factory;

interface ObjectFactoryInterface {
    
    public function create(array $data);
    
    public function createCollection(array $data);
    
}
