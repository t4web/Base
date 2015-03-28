<?php

use Zend\ServiceManager\ServiceManager;
use Zend\Db\Sql\Select;
use T4webBase\Db\QueryBuilder;
use T4webBase\Db\Select as BaseSelect;

return array(

    'service_manager' => array(
        'factories' => array(
            'T4webBase\Db\QueryBuilder' => function(ServiceManager $serviceManager) {
                $serviceManager->setShared('T4webBase\Db\QueryBuilder', false);

                $zendSelect = new Select();
                $select = new BaseSelect($zendSelect);

                return new QueryBuilder($select);
            },
        ),
        'abstract_factories' => array(
            'T4webBase\Module\ConfigAbstractFactory',
            'T4webBase\Domain\Factory\EntityAbstractFactory',
            'T4webBase\Domain\Mapper\DbMapperAbstractFactory',
            'T4webBase\Domain\Repository\DbRepositoryAbstractFactory',
            'T4webBase\Db\TableGatewayAbstractFactory',
            'T4webBase\Db\TableAbstractFactory',
            'T4webBase\Domain\Criteria\CriteriaFactoryAbstractFactory',
        ),
        'invokables' => array(
            'T4webBase\Domain\Repository\IdentityMap' => 'T4webBase\Domain\Repository\IdentityMap',
        ),
    ),

);
