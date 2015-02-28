<?php

namespace Base\InputFilter\Element;

class Price extends Element {
    
    public function isValid($context = null) {
        parent::isValid();
        
        if (empty($this->value)
            || !preg_match('/^\b\d+\b$/', $this->value)) {
            $this->value = $this->defaultValue;
            return false;
        }
        
        return true;
    }
    
}
