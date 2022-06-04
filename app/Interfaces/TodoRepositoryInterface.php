<?php

namespace App\Interfaces;

use App\Models\Todo;

interface TodoRepositoryInterface
{
    public function getAllRecords(array $input);
    public function storeRecord(array $todoDetails);
    public function getRecord(Todo $todo);
    public function updateRecord(Todo $todo, array $newDetails);
    public function deleteRecord(Todo $todo);
    public function search(array $input);
}
