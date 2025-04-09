<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with('user')->where('user_id', Auth::id())->get();
        return response()->json(TaskResource::collection($tasks));
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create([
            'title' => $request->title,
            'status' => $request->status ?? 'pending',
            'user_id' => Auth::id(),
        ]);

        return response()->json(new TaskResource($task), 201);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->only(['title', 'status']));

        return response()->json(new TaskResource($task));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
