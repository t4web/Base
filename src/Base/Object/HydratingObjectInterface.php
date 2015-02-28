<?php

namespace Base\Object;

interface HydratingObjectInterface {
    
    public function extract(array $properties = array());
    
    public function populate(array $array = array());
    
}
