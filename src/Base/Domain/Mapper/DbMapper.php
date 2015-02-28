<?php

namespace Base\Domain\Mapper;

use Base\Object\HydratingObjectInterface;
use Base\Domain\Factory\EntityFactoryInterface;

class DbMapper implements DbMapperInterface {
    
    protected $columnsAsAttributesMap;
    protected $factory;

    public function __construct(array $columnsAsAttributesMap, EntityFactoryInterface $factory) {
        $this->columnsAsAttributesMap = $columnsAsAttributesMap;
        $this->factory = $factory;
    }

    public function toTableRow(HydratingObjectInterface $hydratingObject) {
        $objectState = $hydratingObject->extract(array_values($this->columnsAsAttributesMap));
        
        /**
         * @tdo тут нужно из конфигов брать название поля которое autoIncrement.
         * Обычно это id.
         */
        if (array_key_exists('id', $objectState)) {
            unset($objectState['id']);
        }
        
        return $this->getIntersectValuesAsKeys($this->columnsAsAttributesMap, $objectState);
    }
    
    public function fromTableRow(array $row) {
        $attributesValues = $this->getIntersectValuesAsKeys(array_flip($this->columnsAsAttributesMap), $row);
        
        return $this->factory->create($attributesValues);
    }
    
    public function fromTableRows(array $rows) {
        $attributesValues = array();
        foreach ($rows as $row) {
            $attributesValues[] = $this->getIntersectValuesAsKeys(array_flip($this->columnsAsAttributesMap), $row);
        }
        
        return $this->factory->createCollection($attributesValues);
    }
    
    private function getIntersectValuesAsKeys($array1, $array2) {
        /**
         * @todo Возможно получиться сделать это нативными функциями
         * если нет то перенести в Base\Utils\ArrayUtils
         */
        $result = array();
        
        foreach ($array1 as $key => $value) {
            if (array_key_exists($value, $array2)) {
                $result[$key] = $array2[$value];
            }
        }
        
        return $result;
    }
}
