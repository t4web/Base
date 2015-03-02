<?php

namespace Base\InputFilter;

class InvalidInputError {

    /**
     * @var array
     */
    private $errors;

    public function __construct(array $errors) {
        $this->errors = $errors;
    }

    /**
     * @param string $fieldName
     * @return bool
     */
    public function hasError($fieldName) {

        if (!array_key_exists($fieldName, $this->errors)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $fieldName
     * @return array
     */
    public function getError($fieldName) {

        if (!array_key_exists($fieldName, $this->errors)) {
            return array();
        }

        return $this->errors[$fieldName];
    }

}