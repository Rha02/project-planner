<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Goal;
use App\Models\Sequence;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['goal_id', 'user_id', 'body', 'status'];

    public function goal()
    {
      return $this->belongsTo(Goal::class);
    }

    public function assignedUser()
    {
      return $this->belongsTo(User::class);
    }

    public function sequence()
    {
      // pass
    }
}
