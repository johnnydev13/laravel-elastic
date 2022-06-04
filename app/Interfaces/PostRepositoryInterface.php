<?php

namespace App\Interfaces;

use App\Models\Post;

interface PostRepositoryInterface
{
    public function getAllRecords(array $input);
    public function storeRecord(array $postDetails);
    public function getRecord(Post $post);
    public function updateRecord(Post $post, array $newDetails);
    public function deleteRecord(Post $post);
    public function search(array $input);
}
