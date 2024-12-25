<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'business',
            'general',
            'health',
            'sports',
            'technology'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => ucfirst($category),
                'slug' => Str::slug($category),
                'description' => 'Description for ' . $category,
                'user_id' => null,
            ]);
        }
    }
}
