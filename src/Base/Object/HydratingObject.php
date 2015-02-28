<?php

namespace Base\Object;

abstract class HydratingObject implements HydratingObjectInterface {

    public function extract(array $properties = array()) {
        
        $state = get_object_vars($this);
        
        if (empty($properties)) {
            return $state;
        }
        
        $rawArray = array_fill_keys($properties, null);
        
        return array_intersect_key($state, $rawArray);
    }

    public function populate(array $array = array()) {
        
        $state = get_object_vars($this);
        
        $stateIntersect = array_intersect_key($array, $state);
        
        foreach ($stateIntersect as $key => $value) {
            $this->$key = $value;
        }
        
        return $this;
    }
    
}