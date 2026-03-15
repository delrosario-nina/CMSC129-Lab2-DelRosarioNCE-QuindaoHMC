<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    // INDEX - Show all recipes
    public function index(Request $request)
{
    $query = Recipe::with('categories')
        ->withCount('ingredients', 'steps');

    // Search
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'ilike', '%' . $request->search . '%')
              ->orWhere('description', 'ilike', '%' . $request->search . '%');
        });
    }

    // Filter by difficulty
    if ($request->filled('difficulty')) {
        $query->where('difficulty', $request->difficulty);
    }

    // Filter by category
    if ($request->filled('category')) {
        $query->whereHas('categories', function($q) use ($request) {
            $q->where('categories.id', $request->category);
        });
    }

    $recipes = $query->latest()->paginate(9);
    return view('recipes.index', compact('recipes'));
}

    // CREATE - Show form to create a new recipe
    public function create()
    {
        $categories = Category::all();
        return view('recipes.create', compact('categories'));
    }

    // STORE - Save new recipe to database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'prep_time'       => 'required|integer|min:0',
            'cook_time'       => 'required|integer|min:0',
            'difficulty'      => 'required|in:easy,medium,hard',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categories'      => 'nullable|array',
            'categories.*'    => 'exists:categories,id',
            'ingredients'     => 'required|array|min:1',
            'ingredients.*.name'   => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:255',
            'steps'           => 'required|array|min:1',
            'steps.*.title'   => 'required|string|max:255',
            'steps.*.instruction' => 'required|string',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        // Create the recipe
        $recipe = Recipe::create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'prep_time'   => $validated['prep_time'],
            'cook_time'   => $validated['cook_time'],
            'difficulty'  => $validated['difficulty'],
            'image_path'  => $imagePath,
        ]);

        // Attach categories
        if (!empty($validated['categories'])) {
            $recipe->categories()->sync($validated['categories']);
        }

        // Create ingredients
        foreach ($validated['ingredients'] as $ingredient) {
            $recipe->ingredients()->create($ingredient);
        }

        // Create steps with order
        foreach ($validated['steps'] as $index => $step) {
            $recipe->steps()->create([
                'order'       => $index + 1,
                'title'       => $step['title'],
                'instruction' => $step['instruction'],
            ]);
        }

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'Recipe created successfully!');
    }

    // SHOW - Display a single recipe
    public function show(Recipe $recipe)
    {
        $recipe->load('ingredients', 'steps', 'categories');
        return view('recipes.show', compact('recipe'));
    }

    // EDIT - Show form to edit a recipe
    public function edit(Recipe $recipe)
    {
        $recipe->load('ingredients', 'steps', 'categories');
        $categories = Category::all();
        return view('recipes.edit', compact('recipe', 'categories'));
    }

    // UPDATE - Save edited recipe to database
    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'prep_time'       => 'required|integer|min:0',
            'cook_time'       => 'required|integer|min:0',
            'difficulty'      => 'required|in:easy,medium,hard',
            'image'           => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'categories'      => 'nullable|array',
            'categories.*'    => 'exists:categories,id',
            'ingredients'     => 'required|array|min:1',
            'ingredients.*.name'   => 'required|string|max:255',
            'ingredients.*.amount' => 'required|string|max:255',
            'steps'           => 'required|array|min:1',
            'steps.*.title'   => 'required|string|max:255',
            'steps.*.instruction' => 'required|string',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($recipe->image_path) {
                Storage::disk('public')->delete($recipe->image_path);
            }
            $recipe->image_path = $request->file('image')->store('recipes', 'public');
        }

        // Update recipe
        $recipe->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'prep_time'   => $validated['prep_time'],
            'cook_time'   => $validated['cook_time'],
            'difficulty'  => $validated['difficulty'],
            'image_path'  => $recipe->image_path,
        ]);

        // Sync categories
        $recipe->categories()->sync($validated['categories'] ?? []);

        // Rebuild ingredients
        $recipe->ingredients()->delete();
        foreach ($validated['ingredients'] as $ingredient) {
            $recipe->ingredients()->create($ingredient);
        }

        // Rebuild steps
        $recipe->steps()->delete();
        foreach ($validated['steps'] as $index => $step) {
            $recipe->steps()->create([
                'order'       => $index + 1,
                'title'       => $step['title'],
                'instruction' => $step['instruction'],
            ]);
        }

        return redirect()->route('recipes.show', $recipe)
            ->with('success', 'Recipe updated successfully!');
    }

    // DESTROY - Soft delete a recipe
    public function destroy(Recipe $recipe)
    {
        $recipe->delete(); // soft delete because of SoftDeletes trait
        return redirect()->route('recipes.index')
            ->with('success', 'Recipe moved to trash!');
    }

    // TRASH - Show soft-deleted recipes
    public function trash()
    {
        $recipes = Recipe::onlyTrashed()->latest()->paginate(9);
        return view('recipes.trash', compact('recipes'));
    }

    // RESTORE - Restore a soft-deleted recipe
    public function restore($id)
    {
        $recipe = Recipe::onlyTrashed()->findOrFail($id);
        $recipe->restore();
        return redirect()->route('recipes.trash')
            ->with('success', 'Recipe restored successfully!');
    }

    // FORCE DELETE - Permanently delete a recipe
    public function forceDelete($id)
    {
        $recipe = Recipe::onlyTrashed()->findOrFail($id);

        // Delete image from storage
        if ($recipe->image_path) {
            Storage::disk('public')->delete($recipe->image_path);
        }

        $recipe->forceDelete();
        return redirect()->route('recipes.trash')
            ->with('success', 'Recipe permanently deleted!');
    }
}
