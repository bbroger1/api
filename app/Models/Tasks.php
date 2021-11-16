<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = ['list_id', 'user_id', 'title', 'status'];

    public function index()
    {
        return Tasks::with('user')
            ->where('user_id', auth()->user()->id)
            ->orderBy('status')
            ->get()
            ->all();
    }

    public function store($fields)
    {
        $fields['user_id'] = auth()->user()->id;

        $list = TaskList::with('user')
            ->where('id', $fields['list_id'])
            ->where('user_id', auth()->user()->id)->get();

        if (count($list) == 0) {
            throw new \Exception('Lista não Encontrada', -404);
        }

        return Tasks::create($fields);
    }

    public function show($id)
    {
        if (!$show = Tasks::with('user')
            ->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first()) {
            throw new \Exception('Nada Encontrado', -404);
        }

        return $show;
    }

    public function updateTask($fields, $id)
    {
        $task = Tasks::with('user')
            ->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();

        $task->update($fields);
        return $task;
    }

    public function destroyTask($id)
    {
        $task = Tasks::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$task) {
            return false;
        };

        if (!$task->delete()) {
            return false;
        }
        return true;
    }

    public function tasksByList($listId)
    {
        $tasks = Tasks::where('list_id', '=', $listId)
            ->where('user_id', auth()->user()->id)
            ->get();

        if (count($tasks) == 0) {
            return false;
        }
        return $tasks;
    }

    //1 = feito e 2 = à fazer
    public function closeTask($id)
    {
        $task = Tasks::where('id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$task) {
            return false;
        }

        if ($task['status'] == 1) {
            $task->update(['status' => 2]);
        } else {
            $task->update(['status' => 1]);
        }

        return $task;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tasklist()
    {
        return $this->belongsToMany(Tasks::class, 'id', 'list_id');
    }
}
