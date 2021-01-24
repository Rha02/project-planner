<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\Project;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'status', 'project_id'];

    public function tasks()
    {
      return $this->hasMany(Task::class);
    }

    public function project()
    {
      return $this->belongsTo(Project::class);
    }
}
