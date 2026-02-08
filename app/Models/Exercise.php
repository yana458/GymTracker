<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['category_id','name','instruction'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function routines()
    {
        return $this->belongsToMany(Routine::class)
            ->withPivot(['sequence','target_sets','target_reps','rest_seconds']);
    }
}
