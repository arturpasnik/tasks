<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    protected $user;
    public function setUp(): void {

        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_index(): void
    {

        $response = $this->actingAs($this->user)->get('/tasks');
        $response->assertStatus(200);
    }

    public function test_store(): void
    {
        $task = Task::factory()->make()->toArray();
        $response = $this->actingAs($this->user)->post(route('task.store'),$task);
        $response->assertStatus(302);
        $response->assertRedirect(route('task.index'));
    }

    public function test_store_invalid_txt_required(): void
    {
        $task = Task::factory()->make()->toArray();
        $task['txt'] = null;
        $response = $this->actingAs($this->user)->post(route('task.store'),$task);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['txt'=> 'Task field can not be empty']);
    }

    public function test_store_invalid_txt_max_191(): void
    {
        $task = Task::factory()->make()->toArray();
        $task['txt'] = Str::random(200);
        $response = $this->actingAs($this->user)->post(route('task.store'),$task);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['txt'=> 'Text for new task exceeded allowance of 191 characters']);
    }

    public function test_update(): void
    {

        $task = Task::factory()->create();
        $response = $this->actingAs($this->user)->put(route('task.update',['task'=>$task->id]));
        $response->assertStatus(302);
        $response->assertRedirect(route('task.index'));
    }

    public function test_delete(): void
    {
        $task = Task::factory()->create();
        $response = $this->actingAs($this->user)->delete(route('task.destroy',['task'=>$task->id]));
        $response->assertStatus(302);
        $response->assertRedirect(route('task.index'));
    }
}
