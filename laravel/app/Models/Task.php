<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'formula', 'description', 'solution', 'image', 'points', 'setId'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_task')
            ->withPivot('state', 'points', 'solution')
            ->withTimestamps();
    }

}
