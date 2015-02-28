<?php

namespace Base\InputFilter\Element;

use Zend\Validator;

class ArrayInArray extends InArray {

    public function isValid() {
        $values = $this->value;
        if(!$values) {
            return false;
        }

        foreach ($values as $value) {
            $this->value = $value;

            if (!parent::isValid()) {
                $this->value = null;
                return false;
            }
        }

        $this->value = $values;
        return true;
    }
}