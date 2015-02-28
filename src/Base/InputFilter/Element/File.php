<?php

namespace Base\InputFilter\Element;

use Zend\Validator\File\Size;
use Zend\Validator\File\MimeType;
use Zend\Validator\File\Extension;
use Zend\InputFilter\FileInput;

class File extends FileInput {
    
    protected $defaultValue;
    
    public function __construct($name = null) {
        
        $this->getValidatorChain()
                ->attach(new Size(array('min' => '1kB', 'max' => '10MB')))
                ->attach(new Extension(array('jpg', 'jpeg', 'png', 'gif', )));
                //->attach(new MimeType('image'));

        parent::__construct($name);
    }

    public function isValid($context = null) {
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
}
