<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    protected $fillable = ['name','description'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class)
            ->withPivot(['sequence','target_sets','target_reps','rest_seconds'])
            ->orderBy('exercise_routine.sequence');
    }
}
