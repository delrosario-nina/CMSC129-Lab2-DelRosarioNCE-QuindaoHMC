<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('recipes.index');
});

// Trash, restore, force delete routes (must be before resource so 'trash' isn't interpreted as recipe id)
Route::get('recipes/trash', [RecipeController::class, 'trash'])->name('recipes.trash');
Route::patch('recipes/{id}/restore', [RecipeController::class, 'restore'])->name('recipes.restore');
Route::delete('recipes/{id}/force-delete', [RecipeController::class, 'forceDelete'])->name('recipes.forceDelete');

// Main resource routes (index, create, store, show, edit, update, destroy)
Route::resource('recipes', RecipeController::class);
