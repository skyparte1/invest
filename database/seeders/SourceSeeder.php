<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    public const BCB_CADERNO_URL = 'https://www.bcb.gov.br/pre/pef/port/caderno_cidadania_financeira.pdf';

    public const CVM_CARACTERISTICAS_URL = 'https://www.gov.br/investidor/pt-br/investir/antes-de-investir/entenda-as-caracteristicas-dos-investimentos';

    public const BCB_POUPANCA_URL = 'https://www.bcb.gov.br/estatisticas/remuneradepositospoupanca';

    public const BCB_RISCOS_URL = 'https://www.bcb.gov.br/meubc/faqs/p/existem-riscos-nessas-aplicacoes-financeiras';

    public const FGC_GARANTIA_URL = 'https://fgc.org.br/sobre-garantia-fgc';

    public const TESOURO_SELIC_URL = 'https://www.tesourodireto.com.br/produtos/titulos/selic';

    public const TESOURO_SELIC_CALCULO_URL = 'https://www.tesourodireto.com.br/documents/d/guest/tesouro_selic';

    public const CVM_CDB_URL = 'https://www.gov.br/investidor/pt-br/investir/tipos-de-investimentos/titulos-bancarios/certificado-de-deposito-bancario-cdb';

    public const CVM_FUNDOS_URL = 'https://www.gov.br/investidor/pt-br/investir/tipos-de-investimentos/fundos-de-investimentos';

    public const CVM_FII_URL = 'https://www.gov.br/investidor/pt-br/investir/tipos-de-investimentos/fundos-de-investimentos-imobiliarios-fii';

    public const CVM_FII_RISCOS_URL = 'https://www.gov.br/investidor/pt-br/investir/tipos-de-investimentos/fundos-de-investimentos-imobiliarios-fii/principais-riscos';

    public const CVM_ACOES_URL = 'https://www.gov.br/investidor/pt-br/investir/tipos-de-investimentos/acoes';

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
            ['institution' => 'Banco Central do Brasil', 'title' => 'Remuneração dos Depósitos de Poupança', 'url' => self::BCB_POUPANCA_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
            ['institution' => 'Banco Central do Brasil', 'title' => 'FAQ — Existem riscos nessas aplicações financeiras?', 'url' => self::BCB_RISCOS_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
            ['institution' => 'Fundo Garantidor de Créditos', 'title' => 'Sobre a garantia FGC', 'url' => self::FGC_GARANTIA_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
            ['institution' => 'Tesouro Direto', 'title' => 'Tesouro Selic', 'url' => self::TESOURO_SELIC_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
            ['institution' => 'Tesouro Direto', 'title' => 'Cálculo da Rentabilidade dos Títulos Públicos ofertados via Tesouro Direto — Tesouro Selic (LFT)', 'url' => self::TESOURO_SELIC_CALCULO_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
            ['institution' => 'Comissão de Valores Mobiliários — Portal do Investidor', 'title' => 'Certificado de Depósito Bancário — CDB', 'url' => self::CVM_CDB_URL, 'publication_date' => '2022-10-27', 'accessed_at' => '2026-08-24'],
            ['institution' => 'Comissão de Valores Mobiliários — Portal do Investidor', 'title' => 'Fundos de Investimentos', 'url' => self::CVM_FUNDOS_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
            ['institution' => 'Comissão de Valores Mobiliários — Portal do Investidor', 'title' => 'Fundos de Investimentos Imobiliários — FII', 'url' => self::CVM_FII_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
            ['institution' => 'Comissão de Valores Mobiliários — Portal do Investidor', 'title' => 'Principais riscos dos Fundos de Investimentos Imobiliários', 'url' => self::CVM_FII_RISCOS_URL, 'publication_date' => '2022-11-03', 'accessed_at' => '2026-08-24'],
            ['institution' => 'Comissão de Valores Mobiliários — Portal do Investidor', 'title' => 'Ações', 'url' => self::CVM_ACOES_URL, 'publication_date' => null, 'accessed_at' => '2026-08-24'],
        ];

        foreach ($sources as $source) {
            Source::query()->updateOrCreate(['url' => $source['url']], $source);
        }
    }
}
