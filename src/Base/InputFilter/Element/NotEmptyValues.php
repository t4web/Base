<?php

namespace Base\InputFilter\Element;

class NotEmptyValues extends Element {
    
    public function isValid($context = null) {
        $name = $this->getName();

        if (!isset($context[$name]) || empty($context[$name])) {
            $this->setErrorMessage("Значение не может быть пустым.");
            return false;
        }

        $result = false;
        foreach ($context[$name] as $value) {
            if (!empty($value)) {
                $result = true;
                break;
            }
        }
        
        if (!$result) {
            $this->setErrorMessage("Значение не может быть пустым.");
        }
        
        return $result;
    }
}
