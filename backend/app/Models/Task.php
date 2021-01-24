<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Goal;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'user_id', 'body', 'status', 'prev_task'];

    public function goal()
    {
      return $this->belongsTo(Goal::class);
    }

    public function assignedUser()
    {
      return $this->belongsTo(User::class);
    }
}
