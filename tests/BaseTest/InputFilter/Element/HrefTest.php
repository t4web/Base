<?php

namespace BaseTest\InputFilter\Element;

use Base\InputFilter\Element\Href;
use Zend\Validator;

class HrefTest extends \PHPUnit_Framework_TestCase {
    
    /**
     * @dataProvider DataProvider
     */
    public function testIsValid_LocalHref($value, $result, $errorMessage) {
        $href = new Href();
        $href->setValue($value);

        $this->assertEquals($result, $href->isValid($value));
        $this->assertEquals($errorMessage, $href->getErrorMessage());
    }

    public function DataProvider() {
        return array(
            array('', false, 'Некорректный формат для ссылки'),
            array('/page/first_page sdfsdf', false, 'Некорректный формат для ссылки'),
            array('http://google.ru?<script>alert("ssdf")</script>', false, 'Некорректный формат для ссылки'),
            array('кирилица', false, 'Некорректный формат для ссылки'),
            array('http://кирилица.ru', false, 'Некорректный формат для ссылки'),
            array('href_with""quotes', false, 'Некорректный формат для ссылки'),
            array('page_number_one', true, ''),
            array('page222', true, ''),
            array('/page/first_page', true, ''),
            array('vk.com', true, ''),
            array('www.vk.com', true, ''),
            array('pageName', true, ''),
        );
    }
    
}
