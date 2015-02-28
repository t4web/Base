<?php

namespace Base\Domain;

class Collection extends \ArrayObject {
    
    public function getFirst() {
        return $this->getIterator()->current();
    }
    
    public function getIds() {
        $result = array();
        
        foreach ($this as $entity) {
            $result[] = $entity->getId();
        }
        
        return $result;
    }
    
    public function getValuesByAttribute($attribute, $withEntityId = false) {
        if (!$this->count()) {
            return array();
        }
        
        $methodName = "get" . ucfirst($attribute);
        
        $entity = reset($this);
        if (!method_exists($entity, $methodName)) {
            throw new \RuntimeException("Entity " . get_class($entity) . " has no method $methodName");
        }
        
        $result = array();
        foreach ($this as $entity) {
            if ($withEntityId) {
                $result[$entity->getId()] = $entity->$methodName();
                continue;
            }
            
            $result[] = $entity->$methodName();
        }
        
        return $result;
    }

    public function getByAttributeValue($value, $attribute = 'id', $defaultEntity = '\Base\Domain\Entity') {
        if (!$this->count()) {
            return new $defaultEntity();
        }

        $methodName = "get" . ucfirst($attribute);

        $entity = reset($this);
        if (!method_exists($entity, $methodName)) {
            throw new \RuntimeException("Entity " . get_class($entity) . " has no method $methodName");
        }

        $result = new $defaultEntity();
        foreach ($this as $entity) {
            if ($this->getDecodedValue($entity->$methodName()) == $this->getDecodedValue($value)) {
                $result = $entity;
                break;
            }
        }

        return $result;
    }

    /**
     * @param array|int $value
     * @param string $attribute
     * @return Collection
     * @throws \RuntimeException
     */
    public function getAllByAttributeValue($value, $attribute = 'id') {
        if (!$this->count()) {
            return new self();
        }

        $methodName = "get" . ucfirst($attribute);

        $entity = reset($this);
        if (!method_exists($entity, $methodName)) {
            throw new \RuntimeException("Entity " . get_class($entity) . " has no method $methodName");
        }

        if (!is_array($value)) {
            $value = array($value);
        }
        $value = $this->getDecodedValue($value);

        $result = new self();
        foreach ($this as $entity) {
            if (in_array($this->getDecodedValue($entity->$methodName()), $value)) {
                $result->offsetSet($entity->getId(), $entity);
            }
        }

        return $result;
    }

    public function toArray() {
        if (!$this->count()) {
            return array();
        }
        
        $result = array();
        foreach ($this as $entity) {
            if ($entity->getId()) {
                $result[$entity->getId()] = $entity->extract();
                continue;
            }
            
            $result[] = $entity->extract();
        }
        
        return $result;
    }

    public function addCollection(\ArrayObject $collection, $mergeByKey = 'id', $mergeWithKey = false) {
        foreach ($collection as $value) {
            if ($mergeWithKey) {
                $methodName = "get" . ucfirst($mergeByKey);
                if (!method_exists($value, $methodName)) {
                    throw new \RuntimeException("Entity " . get_class($value) . " has no method $methodName");
                }

                $this->offsetSet($value->$methodName(), $value);
                continue;
            }

            $this->append($value);
        }
    }

    /**
     * @param string|int $page
     * @param string|int $limit
     * @return Collection
     */
    public function getAllByPages($page = 1, $limit = 20) {
        $result = new self();
        if (!$this->count()) {
            return $result;
        }

        $firstElement = ($page-1)*$limit;
        $iterator = $this->getIterator();
        $iterator->seek($firstElement);
        while ($iterator->current() && $result->count() < $limit) {
            $result->offsetSet($iterator->current()->getId(), $iterator->current());
            $iterator->next();
        }

        return $result;
    }

    private function getDecodedValue($value) {
        $result = $value;
        if (is_string($value)) {
            $result = html_entity_decode($value, ENT_QUOTES);
        }

        if (is_array($value)) {
            $result = array_map('html_entity_decode', $value, array_fill_keys(array_keys($value), ENT_QUOTES));
        }

        return $result;
    }
}
