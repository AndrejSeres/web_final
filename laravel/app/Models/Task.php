<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'formula', 'description', 'solution', 'image', 'points', 'setId', 'open', 'date_from', 'date_to'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_tasks')
            ->withPivot('state', 'points', 'solution')
            ->withTimestamps();
    }

}
