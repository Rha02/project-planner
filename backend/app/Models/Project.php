<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description'];

    protected $with = ['members'];

    public function tasks()
    {
      return $this->hasMany(Task::class);
    }

    public function members()
    {
      return $this->belongsToMany(User::class);
    }
}
