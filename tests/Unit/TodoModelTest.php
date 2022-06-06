<?php

namespace Tests\Unit;

use App\Enums\TodoStatusEnum;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TodoModelTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_todo_has_an_accessor_on_due_on_attribute()
    {
        $dueOn = now();
        $todo = Todo::factory()->create(['due_on' => $dueOn]);

        $this->assertEquals($todo->due_on, $dueOn->format('d-M-y, h:i:s'));
    }

    public function test_todo_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $todo = Todo::factory()->for($user)->create();

        $this->assertInstanceOf(User::class, $todo->user);
    }
}
