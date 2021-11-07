<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->hasMany(
            Tasks::class,
            'user_id',
            'list_id',
            'id'
        );
    }
}
