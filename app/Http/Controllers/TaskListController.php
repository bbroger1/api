<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskListRequest;
use App\Models\TaskList;
use App\Transformers\TaskList\TaskListResourceCollection;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Transformers\TaskList\TaskListResource;

class TaskListController extends Controller
{
    private $taskList;

    public function __construct(TaskList $taskList)
    {
        $this->taskList = $taskList;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new TaskListResourceCollection($this->taskList->index());
    }

    public function create()
    {
    }

    public function store(StoreTaskListRequest $request)
    {
        try {
            $data = $this
                ->tasklist
                ->create($request->all());
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasklist.store', null, $e);
        }

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

    public function edit(TaskList $taskList)
    {
        //
    }

    public function update(StoreTaskListRequest $request, $id)
    {
        try {
            $data = $this
                ->tasklist
                ->updateList($request->all(), $id);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasklist.update', $id, $e);
        }

        return new TaskListResource($data, array('type' => 'update', 'route' => 'tasklist.update'));
    }

    public function destroy($id)
    {
        try {
            $data = $this
                ->tasklist
                ->destroyList($id);
        } catch (\Throwable | \Exception $e) {
            return ResponseService::exception('tasklist.destroy', $id, $e);
        }
        return new TaskListResource($data, array('type' => 'destroy', 'route' => 'tasklist.destroy'));
    }
}