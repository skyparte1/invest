<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    public const BCB_CADERNO_URL = 'https://www.bcb.gov.br/pre/pef/port/caderno_cidadania_financeira.pdf';

    public const CVM_CARACTERISTICAS_URL = 'https://www.gov.br/investidor/pt-br/investir/antes-de-investir/entenda-as-caracteristicas-dos-investimentos';

    public function run(): void
    {
        $sources = [
            [
                'institution' => 'Banco Central do Brasil',
                'title' => 'Caderno de Educação Financeira — Gestão de Finanças Pessoais',
                'url' => self::BCB_CADERNO_URL,
                'publication_date' => null,
                'accessed_at' => '2026-08-24',
            ],
            [
                'institution' => 'Comissão de Valores Mobiliários — Portal do Investidor',
                'title' => 'Entenda as características dos investimentos',
                'url' => self::CVM_CARACTERISTICAS_URL,
                'publication_date' => '2022-05-31',
                'accessed_at' => '2026-08-24',
            ],
        ];

        foreach ($sources as $source) {
            Source::query()->updateOrCreate(['url' => $source['url']], $source);
        }
    }
}
