<?php

namespace T4webBase\Domain;

class CollectionWithName extends Collection {
    
    /**
     * 
     * @return array
     * @throws \RuntimeException
     */
    public function getNamesForSelect() {
        if(!$this->count()) {
            return array();
        }
        
        $entity = reset($this);
        if (!method_exists($entity, 'getName')) {
            throw new \RuntimeException("Entity " . get_class($entity) . " has no method getName");
        }
        
        $result = array();
        foreach ($this as $key => $entity) {
            $result[$key] = $entity->getName();
        }
        
        return $result;
    }
}
