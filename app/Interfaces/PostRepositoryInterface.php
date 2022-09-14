<?php

namespace App\Interfaces;

use App\Models\Post;

/**
 * Interface PostRepositoryInterface
 * @package App\Interfaces
 */
interface PostRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function save($date);
    public function update($date,$id);
    public function delete($id);
}
