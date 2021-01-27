<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Sequence extends Model
{
    use HasFactory;

    protected $fillable = ['from_task_id', 'to_task_id'];

    public function fromTask()
    {
      return $this->hasOne(Task::class, 'from_task_id');
    }

    public function toTask()
    {
      return $this->hasOne(Task::class, 'to_task_id');
    }
}
