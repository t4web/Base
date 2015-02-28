<?php

namespace Base\Module;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class ModuleAbstract implements ConfigProviderInterface, AutoloaderProviderInterface {
    
    private $moduleName;
    
    public function getConfig() {
        
        $moduleName = $this->getModuleName();
        
        return include "module/$moduleName/config/module.config.php";
    }
    
    public function getAutoloaderConfig() {
        
        $moduleName = $this->getModuleName();
        
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    $moduleName => "module/$moduleName/src/" . $moduleName,
                ),
            ),
        );
    }
    
    private function getModuleName() {
        if (empty($this->moduleName)) {
            $class = get_called_class();
            
            list($this->moduleName, $moduleClass) = explode('\\', $class);
        }
        
        return $this->moduleName;
    }
    
}