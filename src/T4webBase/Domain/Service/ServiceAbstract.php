<?php
namespace T4webBase\Domain\Service;

abstract class ServiceAbstract implements ServiceInterface {
    
    protected $message;
    
    public function getError(){
        return $this->message;
    }
    
    protected function setError($error){
        $this->message = $error;
    }
}
