<?php

namespace App\Repositories;

use App\Enums\TodoStatusEnum;
use App\Interfaces\TodoRepositoryInterface;
use App\Models\Todo;
use App\Traits\EnumTrait;
use Elasticsearch\Client;
use Illuminate\Support\Arr;

class TodoRepository implements TodoRepositoryInterface
{
    use EnumTrait;

    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function getAllRecords(array $input)
    {
        $statusNumericValue = $this->getValuesByKeys(TodoStatusEnum::class);

        return Todo::with('user')
            ->when(
                isset($input['user_id']),
                fn ($query) => $query->whereRelation('user', 'id', $input['user_id'])
            )
            ->when(
                isset($input['status']),
                fn ($query) => $query->where('status', $statusNumericValue[$input['status']])
            )
            ->when(
                isset($input['date_from']) && isset($input['date_to']),
                fn ($query) => $query->whereDate('due_on', '>=', $input['date_from'])
                    ->whereDate('due_on', '<=', $input['date_to'])
            )
            ->paginate(isset($input['limit']) ? $input['limit'] : limit());
    }

    public function storeRecord(array $todoDetails)
    {
        $statusNumericValue = $this->getValuesByKeys(TodoStatusEnum::class);
        $todoDetails['status'] = $statusNumericValue[$todoDetails['status']];

        return Todo::create($todoDetails);
    }

    public function getRecord(Todo $todo)
    {
        return $todo->load('user');
    }

    public function updateRecord(Todo $todo, array $newDetails)
    {
        if (isset($newDetails['status'])) {
            $statusNumericValue = $this->getValuesByKeys(TodoStatusEnum::class);
            $newDetails['status'] = $statusNumericValue[$newDetails['status']];
        }

        $todo->update($newDetails);
        return $this->getRecord($todo);
    }

    public function deleteRecord(Todo $todo)
    {
        return $todo->delete();
    }

    /** @var \Elasticsearch\Client */
    public function search(array $input)
    {
        $items = $this->searchOnElasticsearch($input['search']);

        return $this->buildCollection($items, isset($input['limit']) ? $input['limit'] : limit());
    }

    private function searchOnElasticsearch(string $query = '')
    {
        $model = new Todo;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'due_on'],
                        'query' => $query,
                    ],
                ],
            ],
        ]);

        return $items;
    }

    private function buildCollection(array $items, $limit)
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        $todos = Todo::whereIn('id', $ids)
            ->with('user')
            ->paginate($limit);

        $todos->setCollection($todos->sortBy(
            function ($post) use ($ids) {
                return array_search($post->getKey(), $ids);
            }
        ));

        return $todos;
    }
}
