<?php

namespace Base\Db;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\Feature\FeatureSet;
use Zend\Db\TableGateway\Feature\GlobalAdapterFeature;

class TableGateway extends AbstractTableGateway {
    
    public function __construct($table) {
        $this->table = $table;
        
        $this->featureSet = new FeatureSet();
        
        $this->featureSet->addFeature(new GlobalAdapterFeature());
        
        $this->initialize();
    }
    
}
