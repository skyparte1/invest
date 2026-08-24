<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fundamentos financeiros', 'slug' => 'fundamentos-financeiros', 'description' => 'Conceitos essenciais para compreender e organizar a vida financeira.', 'sort_order' => 1],
            ['name' => 'Planejamento financeiro', 'slug' => 'planejamento-financeiro', 'description' => 'Organização de recursos e objetivos ao longo do tempo.', 'sort_order' => 2],
            ['name' => 'Investimentos', 'slug' => 'investimentos', 'description' => 'Características fundamentais para estudar investimentos com consciência.', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
