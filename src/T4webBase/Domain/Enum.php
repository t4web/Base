<?php

namespace T4webBase\Domain;

class Enum implements EnumInterface {
    
    protected $name;
    protected $code;
    
    protected static $constants = array();
    
    protected static $values = array();
    
    private function __construct($code, $name) {
        $this->code = $code;
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getCode() {
        return $this->code;
    }
    
    public static function create($code) {
        
        $hash = self::generateHash($code);
        
        if (!isset(self::$values[$hash])) {
            self::$values[$hash] = new static($code, self::getNameByCode($code));
        }
        
        return self::$values[$hash];
    }
    
    private static function generateHash($code) {
        return get_called_class() . $code;
    }
    
    private static function getNameByCode($code) {
        
        if (!isset(static::$constants[$code])) {
            throw new \RuntimeException("Unknown status code '$code'");
        }
        
        return static::$constants[$code];
    }
    
    public static function getAll() {
        return static::$constants;
    }
    
    public static function getValues() {
        return self::$values;
    }
    
    public function __toString() {
        return (string)$this->code;
    }
    
}