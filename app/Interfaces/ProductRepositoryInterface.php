<?php

namespace App\Interfaces;

interface ProductRepositoryInterface 
{
    public function getAll();
    public function getById($itemId);
    public function delete($itemId);
    public function create(array $input);
    public function update($itemId, array $input);
    public function filter($input);
}
