<?php

namespace T4webBase\Domain\Service;

interface UpdateInterface {

    public function isValid(array $data);

    public function update($id, array $data);

    public function activate($id);

    public function inactivate($id);

    public function delete($id);

    public function getValues();
}
