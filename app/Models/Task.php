<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['list_id', 'user_id', 'title', 'status'];

    public function tasklist()
    {
        return $this->belongsTo(User::class);
    }
}
