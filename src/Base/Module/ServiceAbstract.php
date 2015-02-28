<?php

namespace Base\Module;

abstract class ServiceAbstract {
    
    protected $error;
    
    public function getError($scope = null) {
        
        if (empty($scope)) {
            return $this->error;
        }
        
        $error = false;
        if ($this->error) {
            if (array_key_exists($scope, $this->error)) {
                $error = $this->error[$scope];
            }
        }

        return $error;
    }
    
    protected function setError($message) {
        $this->error = $message;
    }

}
