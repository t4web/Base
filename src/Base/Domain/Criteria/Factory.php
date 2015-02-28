<?php

namespace Base\Domain\Criteria;

class Factory {
    
    private $moduleName;
    private $entityName;
    private $dependencies = array();
    private $criteries = array();
    
    public function __construct($moduleName, $entityName, array $dependencies, array $criteries) {
        $this->moduleName = $moduleName;
        $this->entityName = $entityName;
        $this->dependencies = $dependencies;
        $this->criteries = $criteries;
    }
    
    public function create(array $params) {
        $composite = new CompositeCriteria();
        $composite->add($this->getEmptyCriteria());
        
        if (empty($params)) {
            return $composite;
        }
        
        foreach ($params as $moduleName => $entities) {
            foreach ($entities as $entityName => $criteries) {
                foreach ($criteries as $criteriaName => $value) {
                    
                    if (null === $value) {
                        $criteria = $this->getEmptyCriteria();
                    } else {
                        $criteria = $this->getCriteria($moduleName, $entityName, $criteriaName, $value);
                    }
                    
                    if ($moduleName.$entityName != $this->moduleName.$this->entityName) {
                        
                        if (empty($this->dependencies[$this->entityName][$moduleName][$entityName]['table'])) {
                            throw new \RuntimeException("Dependency table for module $moduleName entity $entityName not found");
                        }
                        
                        if (empty($this->dependencies[$this->entityName][$moduleName][$entityName]['rule'])) {
                            throw new \RuntimeException("Dependency rule for module $moduleName entity $entityName not found");
                        }
                        
                        $criteria->setAsForeign();
                        $criteria->setJoinTable($this->dependencies[$this->entityName][$moduleName][$entityName]['table']);
                        $criteria->setJoinRule($this->dependencies[$this->entityName][$moduleName][$entityName]['rule']);
                    }
                    
                    $composite->add($criteria);
                }
            }
        }
        
        return $composite;
    }
    
    public function getNativeCriteria($criteriaName, $value) {
        if (isset($this->criteries[$this->entityName][$criteriaName])) {
            $field = null;
            if (isset($this->criteries[$this->entityName][$criteriaName]['field'])) {
                $field = $this->criteries[$this->entityName][$criteriaName]['field'];
            }
            $table = $this->criteries[$this->entityName][$criteriaName]['table'];
            $buildMethod = $this->criteries[$this->entityName][$criteriaName]['buildMethod'];

            return new NewCriteria($value, $field, $table, $buildMethod);
        }

        $className = ucfirst($this->moduleName) . '\\' . ucfirst($this->entityName)
                . '\Criteria\\' . ucfirst($criteriaName);
        
        if (!class_exists($className)) {
            throw new \RuntimeException("Criteria class $className not found");
        }
        
        return new $className($value);
    }
    
    private function getEmptyCriteria() {
        if (isset($this->criteries[$this->entityName]['empty'])) {
            $table = $this->criteries[$this->entityName]['empty']['table'];
            
            return new EmptyCriteria(null, null, $table, null);
        }
        
        $className = ucfirst($this->moduleName) . '\\' . ucfirst($this->entityName)
                    . '\Criteria\EmptyCriteria';
        
        if (!class_exists($className)) {
            throw new \RuntimeException("Criteria class $className not found");
        }
        
        return new $className();
    }
    
    private function getCriteria($moduleName, $entityName, $criteriaName, $value) {
        if (isset($this->criteries[$entityName][$criteriaName])) {
            $field = null;
            if (isset($this->criteries[$entityName][$criteriaName]['field'])) {
                $field = $this->criteries[$entityName][$criteriaName]['field'];
            }
            $table = $this->criteries[$entityName][$criteriaName]['table'];
            $buildMethod = $this->criteries[$entityName][$criteriaName]['buildMethod'];
            
            return new NewCriteria($value, $field, $table, $buildMethod);
        }
        
        $className = ucfirst($moduleName) . '\\' . ucfirst($entityName)
                            . '\Criteria\\' . ucfirst($criteriaName);
        
        if (!class_exists($className)) {
            throw new \RuntimeException("Criteria class $className not found");
        }
        
        return new $className($value);
    }
}
