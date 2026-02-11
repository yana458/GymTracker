<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;
    protected $fillable = ['name','description'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(\App\Models\Exercise::class, 'exercise_routine', 'routine_id', 'exercise_id')
            ->withPivot(['target_sets', 'target_reps', 'rest_seconds']);
    }

}
