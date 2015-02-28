<?php

namespace Base\InputFilter\Element;

class Float extends Element {
    
    public function isValid($context = null) {
        parent::isValid();
        
        if (empty($this->value) 
            || !preg_match('/^\d+((\.|\,)\d\d?)?$/', $this->value)) {
            $this->value = $this->defaultValue;
            return false;
        }
        
        return true;
    }
    
}
