<?php

namespace App\Http\Controllers;

use App\DTO\TaskDto;
use App\Enums\TaskStatus;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService)
    {

    }
    public function index()
    {
        return Inertia::render('Tasks/TaskIndex',
            [
                'tasks' => TaskResource::collection(Task::orderBy('status', 'ASC')->orderBy('id', 'DESC')->get())
            ]
        );
    }

    public function store(StoreTaskRequest $request)
    {
        $taskData = $request->safe()->only(['txt']);
        $this->taskService->store(new TaskDto(txt: $taskData['txt'], status: TaskStatus::Open));

        return redirect()->route('task.index');
    }

    public function update(Task $task, UpdateTaskRequest $request)
    {
        $taskData = $request->safe()->only(['txt']);
        return $this->taskService->update($task, $taskData);
    }

    public function setStatusComplete(Task $task)
    {
        $this->taskService->update($task, ['status'=> TaskStatus::Closed]);

        return redirect()->route('task.index');
    }

    public function destroy(Task $task)
    {
        $this->taskService->delete($task);

        return redirect()->route('task.index');
    }
}
