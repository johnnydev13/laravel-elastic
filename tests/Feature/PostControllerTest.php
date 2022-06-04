<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

class PostControllerTest extends TestCase
{
    /**
     * It only refreshes the database when called.
     */
    use LazilyRefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_returns_all_posts_paginated()
    {
        Post::factory(3)->create();
        $response = $this->get('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    public function test_it_returns_posts_for_a_user_paginated()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        Post::factory(2)->for($user1)->create();
        Post::factory(3)->for($user2)->create();

        $response = $this->get("/api/posts?" . http_build_query([
            'user_id' => $user2->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
        $this->assertNotNull($response->json('data'));
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    public function test_it_stores_the_post_data()
    {
        $title = "Test post title";
        $body = "Test post body";
        $user = User::factory()->create();

        $response = $this->postJson('/api/posts', [
            'title' => $title,
            'body' => $body,
            'user_id' => $user->id,
        ]);

        $response->assertCreated()
            ->assertStatus(201)
            ->assertJsonPath('data.user.0.id', $user->id)
            ->assertJsonPath('data.title', $title)
            ->assertJsonPath('data.body', $body);
    }

    public function test_it_updates_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create(['title' => 'old title', 'body' => 'old body']);

        $newUser =  User::factory()->create();
        $newTitle = "Updated title";
        $newBody = "Updated body";

        $response = $this->putJson("/api/posts/{$post->id}", [
            'title' => $newTitle,
            'body' => $newBody,
            'user_id' => $newUser->id,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.user.0.id', $newUser->id)
            ->assertJsonPath('data.title', $newTitle)
            ->assertJsonPath('data.body', $newBody);
    }

    public function test_it_shows_a_post()
    {
        $title = "Post Title";
        $body = "Post Body";
        $user = User::factory()->create();
        $post = Post::factory()->for($user)->create(['title' => $title, 'body' => $body]);

        $response = $this->get("/api/posts/{$post->id}");

        $response->assertOk()
            ->assertJsonPath('data.user.0.id', $user->id)
            ->assertJsonPath('data.title', $title)
            ->assertJsonPath('data.body', $body);
    }

    public function test_it_can_delete_a_post()
    {
        $post = Post::factory()->create();
        $this->delete("/api/posts/{$post->id}");

        $this->assertSoftDeleted($post);
    }

    public function test_it_can_search_find_posts_using_search_query()
    {
        Post::factory()->create(['title' => 'Rida in the Title of post 1', 'body' => 'Plain text']);
        Post::factory()->create(['title' => 'Post 2 title', 'body' => 'Rida Noor in the body']);
        Post::factory()->create(['title' => 'Search Random in the Title of post 3', 'body' => 'Plain text']);

        $response = $this->get("/api/posts-search?" . http_build_query([
            'search' => "Rida",
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
}
