<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'prep_time',
        'cook_time',
        'difficulty',
        'image_path',
    ];

    // a recipe has many ingredients
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    // a recipe has many steps
    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('order');
    }

    // a recipe belongs to many categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'recipe_category');
    }
}
