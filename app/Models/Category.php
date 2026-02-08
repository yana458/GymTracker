<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
