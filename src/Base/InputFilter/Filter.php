<?php

namespace Base\InputFilter;

abstract class Filter extends InputFilter {
    
    abstract public function getValuesByModules();
    
    public function filter(array $data){
        $this->setData($data)
            ->isValid();
    }
    
    public function getOrderTable(){
         $values = $this->getValues();
         foreach ($values as $key => $table){
             if (array_key_exists('orderBy', $table) && !empty($table['orderBy'])){
                 return $key;
             }
         }

        return null;
    }
    
    public function getOrderBy(){
        $values = $this->getValues();
        
        foreach ($values as $table){
            if (array_key_exists('orderBy', $table) && !empty($table['orderBy'])){
                return $table['orderBy'];
            }
        }

        return null;
    }
}
