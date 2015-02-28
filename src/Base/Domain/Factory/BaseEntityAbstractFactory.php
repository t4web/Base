<?php

 namespace Base\Domain\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;

/**
 * Заменяет дублирование кода типа:
    'Pages\Page\EntityFactory' => function(ServiceManager $serviceLocator) {
        return new EntityFactory('Pages\Page\Page');
    },
 */
class BaseEntityAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return substr($requestedName, -strlen('EntityFactory')) == 'EntityFactory';
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $namespace = strstr($requestedName, 'EntityFactory', true);
        
        $explodedNamespace = explode('\\', $namespace);
        
        $moduleName = $explodedNamespace[0];
        $entityName = $explodedNamespace[1];
        
        $entityClass = "$moduleName\\$entityName\\$entityName";
        $entityCollectionClass = "$moduleName\\$entityName\\{$entityName}Collection";
        
        if (!class_exists($entityCollectionClass)) {
            $entityCollectionClass = 'Base\Domain\Collection';
        }
        
        return new BaseEntityFactory($entityClass, $entityCollectionClass);
    }

}
