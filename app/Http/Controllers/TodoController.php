<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Resources\TodoResource;
use App\Http\Requests\GetTodosRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Interfaces\TodoRepositoryInterface;
use Illuminate\Http\Response;

class TodoController extends Controller
{
    private $repository;
    public function __construct(TodoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index(GetTodosRequest $request)
    {
        return TodoResource::collection(
            $this->repository->getAllRecords($request->validated())
        );
    }

    public function store(StoreTodoRequest $request)
    {
        return new TodoResource($this->repository->storeRecord($request->validated()));
    }

    public function show(Todo $todo)
    {
        return new TodoResource($this->repository->getRecord($todo));
    }

    public function update(Todo $todo, UpdateTodoRequest $request)
    {
        return new TodoResource($this->repository->updateRecord($todo, $request->validated()));
    }

    public function delete(Todo $todo)
    {
        $this->repository->deleteRecord($todo);
        return response()->json(['status' => Response::HTTP_NO_CONTENT]);
    }

    public function search(SearchRequest $request)
    {
        return TodoResource::collection(
            $this->repository->search($request->validated())
        );
    }
}
