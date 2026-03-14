<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Step extends Model
{
    use HasFactory;

    protected $fillable = ['recipe_id', 'order', 'title', 'instruction'];

    // A step belongs to a recipe
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
