<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'type'];

    // A category belongs to many recipes
    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_category');
    }
}
