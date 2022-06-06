<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Elasticsearch\Client;
use Illuminate\Support\Arr;

class PostRepository implements PostRepositoryInterface
{
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function getAllRecords(array $input)
    {
        return Post::with('user')
            ->when(
                isset($input['user_id']),
                fn ($query) => $query->whereRelation('user', 'id', $input['user_id'])
            )
            ->paginate(isset($input['limit']) ? $input['limit'] : limit());
    }

    public function storeRecord(array $postDetails)
    {
        return Post::create($postDetails);
    }

    public function getRecord(Post $post)
    {
        return $post->load('user');
    }

    public function updateRecord(Post $post, array $newDetails)
    {
        $post->update($newDetails);
        return $this->getRecord($post);
    }

    public function deleteRecord(Post $post)
    {
        return $post->delete();
    }

    /** @var \Elasticsearch\Client */
    public function search(array $input)
    {
        $items = $this->searchOnElasticsearch($input['search']);

        return $this->buildCollection($items, isset($input['limit']) ? $input['limit'] : limit());
    }

    private function searchOnElasticsearch(string $query = '')
    {
        $model = new Post;

        $items = $this->elasticsearch->search([
            'index' => $model->getSearchIndex(),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title', 'body'],
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

        $posts = Post::whereIn('id', $ids)
            ->with('user')
            ->paginate($limit);

        $posts->setCollection($posts->sortBy(
            function ($post) use ($ids) {
                return array_search($post->getKey(), $ids);
            }
        ));

        return $posts;
    }
}
