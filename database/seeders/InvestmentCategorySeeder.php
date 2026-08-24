<?php

namespace Database\Seeders;

use App\Models\InvestmentCategory;
use Illuminate\Database\Seeder;

class InvestmentCategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Renda fixa', 'slug' => 'renda-fixa', 'description' => 'Modalidades cuja regra de remuneração é definida no momento da aplicação.', 'sort_order' => 1],
            ['name' => 'Fundos', 'slug' => 'fundos', 'description' => 'Estruturas coletivas em que investidores adquirem cotas de um patrimônio.', 'sort_order' => 2],
            ['name' => 'Renda variável', 'slug' => 'renda-variavel', 'description' => 'Modalidades cujo resultado não é conhecido antecipadamente.', 'sort_order' => 3],
        ] as $category) {
            InvestmentCategory::query()->updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
