<?php

namespace Base\Domain\Service;

interface DeleteInterface {
    
    public function delete($id, $attribyteName = 'Id');
    
    public function deleteAll($id, $attribyteName = 'Id');
}
