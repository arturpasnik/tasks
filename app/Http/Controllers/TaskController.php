<?php

namespace App\Http\Controllers;

use App\DTO\TaskDto;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
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

        return redirect()->back();
    }

    public function update(Task $task)
    {
        $task->update(['status'=> TaskStatus::Closed]);

        return redirect()->back();
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->back();
    }
}
