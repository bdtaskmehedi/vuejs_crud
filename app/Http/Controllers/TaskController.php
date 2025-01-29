<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::paginate(3); // Adjust 10 to your desired number of items per page

        return response()->json($tasks); // Return paginated results as JSON
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $data = $request->all();
        $path = $request->file('image')->store('images', 'public');
        $data['image'] = $path;

        $task = Task::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Task created successfully!',
            'task' => $task
        ], 201);
    }

    public function show(Task $task)
    {
        return $task;
    }

    public function edit(Task $task)
    {
        return $task;
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $data = $request->all();
        if ($request->has('image')) {
            $path = $request->file('image')->store('images', 'public');
            $data['image'] = $path;
        }

        $task->update($data);
        return $task;
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
