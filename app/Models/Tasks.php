<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tasks extends Model
{
    use HasFactory;

    protected $table = "tasks";

    protected $fillable = [
        'title',
        'description',
        'deadline',
        'status',
        'project_id',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'deadline' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
