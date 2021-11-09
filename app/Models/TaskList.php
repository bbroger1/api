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
        //dd(TaskList::with('user')->where('user_id', auth()->user()->id)->orderBy('status')->get());
        //return auth()->user()->TaskList->sortBy("status");
        return TaskList::with('user')->where('user_id', auth()->user()->id)->orderBy('status')->get()->all();
    }

    public function show($id)
    {
        if (!$show = TaskList::with(['user', 'task'])
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
        if (!$this->find($id)->delete()) {
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
