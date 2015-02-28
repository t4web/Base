<?php

namespace BaseTest\InputFilter;

use BaseTest\InputFilter\TestAsset\Filter;

class FilterTest extends \PHPUnit_Framework_TestCase {
    
    protected $filter;
    
    public function setUp() {
        $this->filter = new Filter();
    }
    
    public function testImplementFilterInterface() {
        $this->assertInstanceOf('Base\InputFilter\Filter', $this->filter);
    }
    
    public function testConstruct() {
        $this->filter->setData(array('id' => 1));
        
        $this->assertTrue($this->filter->has('id'));
        $this->assertInstanceOf('Base\InputFilter\Element\Id', $this->filter->get('id'));
        $this->assertSame(1, $this->filter->getValue('id'));
    }
    
    public function testFilterAndGetValuesByModules() {
        $data = array(
            'id' => 1,
        );
        $expected = array (
            'test' => array (
                'id' => 1,
            ),
        );
        
        $this->filter->filter($data);
        
        $this->assertSame($expected, $this->filter->getValuesByModules());
    }
}
