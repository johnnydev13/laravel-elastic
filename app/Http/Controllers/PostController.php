<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPostsRequest;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Response;
use App\Http\Requests\SearchRequest;


class PostController extends Controller
{
    private $repository;
    public function __construct(PostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(GetPostsRequest $request)
    {
        return PostResource::collection(
            $this->repository->getAllRecords($request->validated())
        );
    }

    public function store(StorePostRequest $request)
    {
        return new PostResource($this->repository->storeRecord($request->validated()));
    }

    public function show(Post $post)
    {
        return new PostResource($this->repository->getRecord($post));
    }

    public function update(Post $post, UpdatePostRequest $request)
    {
        return new PostResource($this->repository->updateRecord($post, $request->validated()));
    }

    public function delete(Post $post)
    {
        $this->repository->deleteRecord($post);
        return response()->json(['status' => Response::HTTP_NO_CONTENT]);
    }

    public function search(SearchRequest $request)
    {
        return PostResource::collection(
            $this->repository->search($request->validated())
        );
    }
}
