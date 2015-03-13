<?php

namespace T4webBase\Domain\Criteria;

class NewCriteria extends AbstractCriteria {
    
    public function __construct($value = null, $field = null, $table = null, $buildMethod = null) {
        $this->value = $value;
        $this->field = $field;
        $this->table = $table;
        $this->buildMethod = $buildMethod;
    }
}
