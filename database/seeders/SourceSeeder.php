<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            'OpenNews',
            'The Guardian',
            'New York Times',
        ];

        foreach ($sources as $source){
            Source::create([
                'name' => ucfirst($source),
                'slug' => Str::slug($source),
                'description' => 'Description for ' . $source,
            ]);
        }
    }
}
