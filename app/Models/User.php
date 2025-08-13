<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; 
use App\Models\Project;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; 

  public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id');
    }
    protected $table = "users";

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function timeEntries()
{
    return $this->hasMany(\App\Models\TimeEntry::class);
}

}
