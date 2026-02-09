<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'icon_path',
    ];

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
