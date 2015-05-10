<?php

namespace T4webBase\InputFilter\Element;

use Zend\Validator;

class Href extends Element {

    public function isValid($context = null) {
        if (!$this->hrefValidate()) {
            $this->setErrorMessage('Некорректный формат для ссылки');
            return false;
        }

        return parent::isValid($context);
    }

    private function hrefValidate() {
        $href = $this->getValue();
        if (preg_match('/[\'"]/', $href)) {
            return false;
        }

        $patterns = array('/^[\/]/', '/[_]/');
        $replacements = array('', '-');
        $href = preg_replace($patterns, $replacements, $href);
        if (!isset(parse_url($href)['scheme'])) {
            $href = 'http://' . $href;
        }

        return (bool)filter_var($href, FILTER_VALIDATE_URL);
    }
}
