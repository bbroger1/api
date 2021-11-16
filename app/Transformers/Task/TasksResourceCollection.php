<?php

namespace App\Transformers\Task;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\ResponseService;

class TasksResourceCollection extends JsonResource
{
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function toArray($request)
    {
        $response = [];
        foreach ($this->resource as $task) {
            $task['status'] = $task['status'] == 1 ? 'Feito' : 'À Fazer';
            array_push($response, $task);
        };

        return [
            'data' => $response,
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'status' => true,
            'message'    => 'Listando dados',
            'url'    => route('tasks.index')
        ];
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(200);
    }
}
