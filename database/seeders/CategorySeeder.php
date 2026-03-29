<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing categories
        Category::truncate();

        $categories = [
            // Complexity
            ['name' => 'No Sweat', 'slug' => 'no-sweat', 'type' => 'complexity'],
            ['name' => 'Busy Hands', 'slug' => 'busy-hands', 'type' => 'complexity'],
            ['name' => 'Culinary Class Wars', 'slug' => 'culinary-class-wars', 'type' => 'complexity'],

            // Time
            ['name' => 'Fast and the Furious', 'slug' => 'fast-and-furious', 'type' => 'time'],
            ['name' => 'One Kdrama Episode', 'slug' => 'one-kdrama-episode', 'type' => 'time'],
            ['name' => '1 One Piece Arc', 'slug' => 'one-piece-arc', 'type' => 'time'],

            // Type
            ['name' => 'Vegetables', 'slug' => 'vegetables', 'type' => 'type'],
            ['name' => 'Dessert', 'slug' => 'dessert', 'type' => 'type'],
            ['name' => 'Soup', 'slug' => 'soup', 'type' => 'type'],
            ['name' => 'Bread', 'slug' => 'bread', 'type' => 'type'],
            ['name' => 'Breakfast', 'slug' => 'breakfast', 'type' => 'type'],
            ['name' => 'Dinner', 'slug' => 'dinner', 'type' => 'type'],
            ['name' => 'Lunch', 'slug' => 'lunch', 'type' => 'type'],

            // Protein
            ['name' => 'Chicken', 'slug' => 'chicken', 'type' => 'protein'],
            ['name' => 'Pork', 'slug' => 'pork', 'type' => 'protein'],
            ['name' => 'Beef', 'slug' => 'beef', 'type' => 'protein'],
            ['name' => 'Fish', 'slug' => 'fish', 'type' => 'protein'],
            ['name' => 'Egg', 'slug' => 'egg', 'type' => 'protein'],
            ['name' => 'Beans', 'slug' => 'beans', 'type' => 'protein'],
            ['name' => 'None', 'slug' => 'none', 'type' => 'protein'],

            // Recipe Status
            ['name' => 'First Time', 'slug' => 'first-time', 'type' => 'status'],
            ['name' => 'Classic', 'slug' => 'classic', 'type' => 'status'],
            ['name' => 'Mastered', 'slug' => 'mastered', 'type' => 'status'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
