<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Models\Tasks;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Transformers\Task\TasksResource;
use App\Transformers\Task\TasksResourceCollection;

class TasksController extends Controller
{
    private $tasks;

    public function __construct(Tasks $tasks)
    {
        $this->tasks = $tasks;
    }

    public function index()
    {
        return new TasksResourceCollection($this->tasks->index());
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            $data = $this
                ->tasks
                ->store($request->validated());
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasks.store', null, $e);
        }
        return new TasksResource($data, array('type' => 'store', 'route' => 'tasks.store'));
    }

    public function show($id)
    {
        try {
            $data = $this
                ->tasks
                ->show($id);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasks.show', $id, $e);
        }

        return new TasksResource($data, array('type' => 'show', 'route' => 'tasks.show'));
    }

    public function update(StoreTaskRequest $request, $id)
    {
        try {
            $data = $this
                ->tasks
                ->updateTask($request->validated(), $id);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasks.update', $id, $e);
        }

        return new TasksResource($data, array('type' => 'update', 'route' => 'tasks.update'));
    }

    public function destroy($id)
    {
        if (!$this
            ->tasks
            ->destroyTask($id)) {
            return ResponseService::customMessage('tasks.destroy', $id, 'Tarefa não localizada');
        };

        return ResponseService::customMessage('tasklist.index', $id = null, 'Tarefa excluída com sucesso');
    }

    public function tasksByList($id)
    {
        if (!$data = $this
            ->tasks
            ->tasksByList($id)) {
            return ResponseService::customMessage('tasks.tasksByList', $id, 'Lista não possui tarefas');
        };

        return new TasksResourceCollection($data);
    }

    public function closeTask($id)
    {
        if (!$data = $this
            ->tasks
            ->closeTask($id)) {
            return ResponseService::customMessage('tasklist.index', $id, 'Tarefa não localizada');
        };

        return new TasksResource($data, array('type' => 'update', 'route' => 'tasks.closeTask'));
    }
}
