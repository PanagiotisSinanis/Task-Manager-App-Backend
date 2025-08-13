<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Tasks;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function users()
{
    return $this->belongsToMany(User::class);
}
public function tasks()
{
    return $this->hasMany(Tasks::class);
}


}
// This model represents a project with a name, description, and the user who created it.
// It has a relationship with the User model to get the creator of the project.