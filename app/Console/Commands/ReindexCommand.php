<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Todo;
use Elasticsearch\Client;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all posts to Elasticsearch';

    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        parent::__construct();

        $this->elasticsearch = $elasticsearch;
    }

    public function handle()
    {
        $this->info('Indexing all posts. This might take a while...');

        $modelsToBeIndexed = [Post::class, Todo::class];
        foreach ($modelsToBeIndexed as $model) {
            foreach ($model::cursor() as $post) {
                $this->elasticsearch->index([
                    'index' => $post->getSearchIndex(),
                    'type' => '_doc',
                    'id' => $post->getKey(),
                    'body' => $post->toSearchArray(),
                ]);
            }

            $this->output->write($model . " Model's indexing completed.\n");
        }

        $this->info("...\nDone!");
    }
}
