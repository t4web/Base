<?php

use Zend\ServiceManager\ServiceManager;
use Zend\Db\Sql\Select;
use Base\Db\QueryBuilder;
use Base\Db\Select as BaseSelect;

return array(

    'service_manager' => array(
        'factories' => array(
            'Base\Db\QueryBuilder' => function(ServiceManager $serviceManager) {
                $serviceManager->setShared('Base\Db\QueryBuilder', false);

                $zendSelect = new Select();
                $select = new BaseSelect($zendSelect);

                return new QueryBuilder($select);
            },
        ),
        'abstract_factories' => array(
            'Base\Module\ConfigAbstractFactory',
            'Base\Domain\Factory\EntityAbstractFactory',
            'Base\Domain\Mapper\DbMapperAbstractFactory',
            'Base\Domain\Repository\DbRepositoryAbstractFactory',
            'Base\Db\TableGatewayAbstractFactory',
            'Base\Db\TableAbstractFactory',
            'Base\Domain\Criteria\CriteriaFactoryAbstractFactory',
        ),
    ),

);
