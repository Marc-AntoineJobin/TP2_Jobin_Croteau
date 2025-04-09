<?php
namespace App\Repository;
use Illuminate\Support\Facades\Route;

interface RepositoryInterface
{
    public function create(array $content);
    public function getAll(int $perPage = 0);
    public function getById(int $id);
    public function update(int $id, array $content);
    public function delete(int $id);
}