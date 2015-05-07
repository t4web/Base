<?php

namespace T4webBase\InputFilter;

trait ErrorAwareTrait {

    /**
     * @var InvalidInputError
     */
    private $errors;

    /**
     * @param array $errors
     */
    public function setErrors(array $errors) {
        $this->errors = new InvalidInputError($errors);
    }

    /**
     * @return InvalidInputError
     */
    public function getErrors() {
        return $this->errors;
    }

}