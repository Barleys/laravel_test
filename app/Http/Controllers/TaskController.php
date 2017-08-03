<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Task;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    use Helpers;

    protected $tasks;

    public function __construct(TaskRepository $task)
    {
        $this->middleware('auth');

        $this->tasks = $task;
    }

    public function index(Request $request)
    {
        return view('tasks.index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    public function list()
    {
        $tasks = Task::orderBy('created_at', 'asc')->get();

        return view('tasks', [
            'tasks' => $tasks,
        ]);
    }

    public function task(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->withInput()
                ->withErrors($validator);
        }

        $task = new \App\Task();

        $task->name = $request->name;

        $task->save();

        return redirect('/');
    }

    public function delete($id)
    {
        Task::findOrFail($id)->delete();

        return redirect('/');
    }

}
