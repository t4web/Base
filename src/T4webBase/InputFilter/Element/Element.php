<?php

namespace T4webBase\InputFilter\Element;

use Zend\InputFilter\Input;
use Zend\Validator;

class Element extends Input {

    protected $defaultValue;

    protected $ignoreValue;

    public function __construct($name = '') {
        parent::__construct($name);

        if ($this->isRequired()) {
            /** @var $notEmpty \Zend\Validator\NotEmpty */
            $notEmpty = $this->getValidatorChain()->plugin('NotEmpty', array());
            $notEmpty->setMessages(array(
                Validator\NotEmpty::INVALID => 'Неверный тип значения. Ожидается строка, массив или тип boolean.',
                Validator\NotEmpty::IS_EMPTY => 'Значение обязательное и не может быть пустым'
            ));
            $this->getValidatorChain()->prependValidator($notEmpty, true);
        }
    }

    public function getValue() {
        $value = parent::getValue();

        if (null === $value && null === $this->ignoreValue) {
            $value = $this->defaultValue;
        }

        return $value;
    }

    public function isValid($context = null) {
        if (null !== $this->ignoreValue
            && isset($context[$this->getName()])
            && $context[$this->getName()] == $this->ignoreValue) {

            $this->value = null;
            return true;
        }

        $result = parent::isValid($context);
        if (!$result) {
            $this->value = $this->defaultValue;
        }
        
        return $result;
    }
    
    public function isDefaultValue() {
        return $this->value === $this->defaultValue;
    }
    
    public function setDefaultValue($value) {
        $this->defaultValue = $value;
        return $this;
    }

    public function setIgnoreValue($ignoreValue) {
        $this->ignoreValue = $ignoreValue;
    }
}
