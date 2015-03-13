<?php

namespace T4webBase\Filter;

use Zend\Filter\HtmlEntities;

class String extends HtmlEntities {

    public function filter($value) {
        if (!$value) {
            return null;
        }

        return parent::filter($value);
    }

}