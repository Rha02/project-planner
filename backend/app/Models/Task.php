<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'body', 'status'];

    public function project()
    {
      return $this->belongsTo(Project::class);
    }
}
