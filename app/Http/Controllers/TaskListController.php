<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskListRequest;
use App\Models\TaskList;
use App\Transformers\TaskList\TaskListResourceCollection;
use App\Services\ResponseService;
use App\Transformers\TaskList\TaskListResource;

class TaskListController extends Controller
{
    private $taskList;

    public function __construct(TaskList $taskList)
    {
        $this->tasklist = $taskList;
    }

    public function index()
    {
        return new TaskListResourceCollection($this->tasklist->index());
    }

    public function store(StoreTaskListRequest $request)
    {
        if (!$data = $this->tasklist->createList($request->all())) {
            return ResponseService::customMessage('tasklist.index', $id = null, 'Não foi possível criar a lista');
        };

        return new TaskListResource($data, array('type' => 'store', 'route' => 'tasklist.store'));
    }

    public function show($id)
    {
        try {
            $data = $this
                ->tasklist
                ->show($id);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasklist.show', $id, $e);
        }
        return new TaskListResource($data, array('type' => 'show', 'route' => 'tasklist.show'));
    }

    public function update(StoreTaskListRequest $request, $id)
    {
        try {
            $data = $this
                ->tasklist
                ->updateList($request->validated(), $id);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasklist.update', $id, $e);
        }

        return new TaskListResource($data, array('type' => 'update', 'route' => 'tasklist.update'));
    }

    public function destroy($id)
    {
        if (!$list = $this->tasklist->destroyList($id)) {
            return ResponseService::customMessage('tasklist.show', $id, 'Lista não localizada');
        };

        return ResponseService::customMessage('tasklist.index', $id = null, 'Lista excluída com sucesso');
    }
}
