<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\ResponseService;

class TaskList extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'status'];

    public function index()
    {
        //return auth()->user()->TaskList->sortBy("status");
        return TaskList::with('task')->where('user_id', auth()->user()->id)->orderBy('status')->get()->all();
    }

    public function createList($fields)
    {
        $fields['user_id'] = auth()->user()->id;

        if (!$taskList = TaskList::create($fields)) {
            return false;
        }
        return $taskList;
    }

    public function show($id)
    {
        if (!$show = TaskList::with(['task'])
            ->where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first()) {
            throw new \Exception('Nada Encontrado', -404);
        }
        return $show;
    }

    public function updateList($fields, $id)
    {
        $tasklist = $this->find($id)->update($fields);
        $tasklist = $this->find($id);
        return $tasklist;
    }

    public function destroyList($id)
    {
        $taskList = TaskList::where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first();

        if (!$taskList->delete()) {
            return false;
        };
        return true;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->hasMany(Tasks::class, 'list_id', 'id');
    }
}
