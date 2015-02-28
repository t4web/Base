<?php

namespace BaseTest\Domain\Assets;

use Base\Domain\Status;

class EntityStatus2 extends Status {
    
    const ACTIVE    = 1;
    const INACTIVE  = 2;
    const DELETED   = 3;
    
    /**
     * @var array
     */
    protected static $constants = array(
        self::ACTIVE    => 'Active',
        self::INACTIVE  => 'Inactive',
        self::DELETED   => 'Deleted',
    );
    
}
