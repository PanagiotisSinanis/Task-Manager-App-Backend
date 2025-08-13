<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Tasks;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'description',
        'start_time',
        'end_time',
        'duration',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function task()
    {
        return $this->belongsTo(Tasks::class);
    }
}
