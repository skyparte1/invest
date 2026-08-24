<?php

namespace Database\Seeders;

use App\Models\Investment;
use App\Models\InvestmentCategory;
use App\Models\Source;
use Illuminate\Database\Seeder;

class InvestmentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = InvestmentCategory::query()->pluck('id', 'slug');
        $sources = Source::query()->pluck('id', 'url');

        $items = [
            [
                'category' => 'renda-fixa', 'name' => 'Poupança', 'slug' => 'poupanca', 'risk_level' => 'variable', 'sort_order' => 1,
                'short_description' => 'Conta de depósito com remuneração definida por regra legal e crédito periódico na data de aniversário.',
                'description' => "## O que é?\n\nA poupança é uma conta de depósito voltada à guarda de recursos, que passam a gerar rendimentos conforme regras legais.\n\n## Como funciona?\n\nCada depósito possui uma data de aniversário. Para pessoas físicas, a remuneração é apurada por períodos mensais sobre o menor saldo do período e creditada ao final dele. Valores retirados antes de completar o período correspondente não recebem aquela remuneração.",
                'risk_description' => 'A modalidade envolve risco da instituição depositária. A cobertura do FGC reduz parte desse risco apenas para créditos elegíveis e dentro das condições e limites do regulamento. Por isso, a classificação didática é variável: o risco efetivo depende da instituição, do valor mantido e da elegibilidade à cobertura.',
                'liquidity_description' => 'Os recursos podem ser movimentados conforme as regras da conta. Liquidez e remuneração são características distintas: retirar antes da data de aniversário pode fazer o depósito não receber a remuneração daquele período, ainda que o dinheiro esteja disponível.',
                'profitability_description' => 'A remuneração combina a Taxa Referencial com uma parcela adicional definida pela legislação e condicionada à meta da taxa Selic. Não há taxa atual exibida aqui. O resultado depende da regra vigente em cada período e da permanência do depósito até a data de crédito.',
                'protection_description' => 'Depósitos de poupança estão entre os produtos cobertos pela garantia ordinária do FGC quando elegíveis. Na data de acesso, o FGC informa limites por CPF ou CNPJ e por instituição ou conglomerado, além de um teto global em determinado período. A cobertura não elimina a necessidade de verificar as regras e os valores aplicáveis.',
                'advantages' => "- Regra de remuneração pública e padronizada.\n- Movimentação simples dos recursos.\n- Possível cobertura do FGC dentro das condições aplicáveis.",
                'points_of_attention' => "- A remuneração depende da data de aniversário de cada depósito.\n- Retiradas antes do fim do período podem não receber rendimento.\n- A cobertura do FGC possui condições e limites; não é irrestrita.",
                'sources' => [SourceSeeder::BCB_POUPANCA_URL, SourceSeeder::BCB_RISCOS_URL, SourceSeeder::FGC_GARANTIA_URL],
            ],
            [
                'category' => 'renda-fixa', 'name' => 'Tesouro Selic', 'slug' => 'tesouro-selic', 'risk_level' => 'low', 'sort_order' => 2,
                'short_description' => 'Título público pós-fixado cuja remuneração acompanha a variação diária da taxa Selic, com possíveis efeitos de ágio ou deságio.',
                'description' => "## O que é?\n\nTesouro Selic é o nome comercial da Letra Financeira do Tesouro (LFT), título público pós-fixado oferecido pelo Tesouro Direto.\n\n## Como funciona?\n\nO investidor compra uma fração do título. No vencimento, recebe o principal e a remuneração acumulada segundo a regra do título. Se vender antes, a recompra ocorre pelo preço de mercado daquele momento.",
                'risk_description' => 'A classificação didática é baixa em relação às demais modalidades deste catálogo porque o material oficial o apresenta como o título de menor risco entre os títulos públicos para oscilações de juros. Isso não significa ausência de risco: há risco de crédito do emissor soberano e o preço de venda antecipada pode diferir do esperado.',
                'liquidity_description' => 'O Tesouro Direto oferece recompra antecipada conforme suas regras operacionais. O valor recebido numa venda antes do vencimento é calculado pelo preço de mercado, portanto disponibilidade para venda não equivale a estabilidade absoluta do preço.',
                'profitability_description' => 'A rentabilidade acompanha a variação da Selic diária entre a liquidação da compra e o vencimento, acrescida do efeito de eventual ágio ou deságio da aquisição. Nenhuma taxa atual ou projeção é apresentada no catálogo.',
                'protection_description' => 'Trata-se de título público emitido pelo Tesouro Nacional. Ele não é um depósito bancário e não utiliza a cobertura do FGC. A natureza do emissor não elimina os riscos de crédito soberano nem os efeitos de preço em uma venda antecipada.',
                'advantages' => "- Regra de remuneração vinculada a um indexador público.\n- Recompra antecipada oferecida pelo programa segundo suas regras.\n- Menor sensibilidade a variações de juros entre os títulos públicos, conforme material oficial.",
                'points_of_attention' => "- Venda antecipada ocorre a preço de mercado.\n- Há custos e tributação que devem ser verificados nas regras vigentes.\n- Baixo risco didático não significa risco zero.",
                'sources' => [SourceSeeder::TESOURO_SELIC_URL, SourceSeeder::TESOURO_SELIC_CALCULO_URL],
            ],
            [
                'category' => 'renda-fixa', 'name' => 'CDB', 'slug' => 'cdb', 'risk_level' => 'variable', 'sort_order' => 3,
                'short_description' => 'Título de renda fixa emitido por instituição financeira para captar recursos, com condições definidas na contratação.',
                'description' => "## O que é?\n\nO Certificado de Depósito Bancário é um título de renda fixa representativo de depósito a prazo emitido por bancos e outras instituições autorizadas.\n\n## Como funciona?\n\nAo adquirir o título, o investidor entrega recursos ao emissor e passa a ter uma promessa de pagamento futuro do principal acrescido da remuneração pactuada. Prazo, carência e forma de remuneração variam entre emissões.",
                'risk_description' => 'O principal risco destacado pela fonte oficial é o risco de crédito da instituição emissora. Como emissores, prazos, valores e condições de cobertura diferem, a modalidade recebe risco variável; pertencer à renda fixa não significa ausência de risco.',
                'liquidity_description' => 'Depende do contrato. Alguns CDBs permitem resgate em prazo curto, enquanto outros possuem carência ou vencimento mais distante. Um resgate antecipado pode não ser permitido ou resultar em remuneração menor, conforme as condições contratadas.',
                'profitability_description' => 'A remuneração pode ser prefixada, pós-fixada ou híbrida. A regra é pactuada na emissão e pode usar um índice de referência. Conhecer a fórmula não torna o resultado livre de risco, especialmente se houver saída antecipada ou inadimplência do emissor.',
                'protection_description' => 'CDBs elegíveis emitidos por instituições associadas estão entre os produtos cobertos pelo FGC, dentro dos limites e condições do regulamento. A cobertura possui teto por titular e instituição ou conglomerado e não deve ser tratada como proteção ilimitada.',
                'advantages' => "- Condições de prazo e remuneração conhecidas na contratação.\n- Diversidade de prazos e regras de liquidez.\n- Possível cobertura do FGC para créditos elegíveis.",
                'points_of_attention' => "- Avaliar o risco de crédito do emissor.\n- Conferir carência, vencimento e regras de resgate.\n- Verificar custos, tributação e elegibilidade ao FGC nas condições vigentes.",
                'sources' => [SourceSeeder::CVM_CDB_URL, SourceSeeder::FGC_GARANTIA_URL, SourceSeeder::CVM_CARACTERISTICAS_URL],
            ],
            [
                'category' => 'fundos', 'name' => 'Fundos de investimento', 'slug' => 'fundos-de-investimento', 'risk_level' => 'variable', 'sort_order' => 1,
                'short_description' => 'Estrutura coletiva em que recursos de vários cotistas são reunidos e aplicados conforme uma política definida.',
                'description' => "## O que é?\n\nUm fundo de investimento reúne recursos de diversos investidores em um patrimônio coletivo. Cada investidor possui cotas, que representam frações desse patrimônio.\n\n## Como funciona?\n\nA gestão aplica os recursos nos ativos permitidos pela política e pelo regulamento. Existem fundos com estratégias muito diferentes, incluindo renda fixa, ações, moedas e combinações de ativos.",
                'risk_description' => 'O risco depende dos ativos, da estratégia, dos prestadores e das regras de cada classe. Pode incluir risco de mercado, crédito e liquidez. Como a modalidade abrange estruturas muito diferentes, qualquer rótulo único de baixo, moderado ou alto seria inadequado; por isso, o risco é variável.',
                'liquidity_description' => 'Nas classes abertas, pedidos de resgate seguem prazos definidos no regulamento. Classes fechadas normalmente não permitem resgate antes do encerramento, podendo exigir venda das cotas no mercado secundário. O prazo de conversão e pagamento deve ser verificado em cada fundo.',
                'profitability_description' => 'O valor da cota varia conforme o desempenho dos ativos e os custos do fundo. Não há promessa de rendimento predeterminado. Estratégia, condições de mercado, taxas, eventos de crédito e decisões de gestão podem afetar o resultado.',
                'advantages' => "- Acesso coletivo a uma carteira administrada segundo política definida.\n- Possibilidade de estratégias e classes de ativos distintas.\n- Documentos formais descrevem regras, riscos e responsabilidades.",
                'points_of_attention' => "- Ler regulamento e termo de ciência de risco.\n- Entender composição da carteira, taxas e prazos de resgate.\n- Verificar se a responsabilidade da classe é limitada ou ilimitada.",
                'sources' => [SourceSeeder::CVM_FUNDOS_URL, SourceSeeder::CVM_CARACTERISTICAS_URL],
            ],
            [
                'category' => 'fundos', 'name' => 'Fundos imobiliários', 'slug' => 'fundos-imobiliarios', 'risk_level' => 'variable', 'sort_order' => 2,
                'short_description' => 'Fundos fechados que reúnem recursos para investir em imóveis ou ativos relacionados ao mercado imobiliário.',
                'description' => "## O que é?\n\nFundos de Investimentos Imobiliários reúnem recursos de cotistas para investir em empreendimentos imobiliários e em títulos ou valores mobiliários ligados ao setor, conforme a política do fundo.\n\n## Como funciona?\n\nSão constituídos como fundos fechados. O investidor adquire cotas e, em geral, precisa negociá-las no mercado secundário para sair antes do encerramento.",
                'risk_description' => 'Há riscos de mercado, do setor imobiliário, de vacância, de desvalorização, regulatórios e de liquidez. O impacto varia conforme carteira, gestão, imóveis e condições de negociação; por isso, a classificação da modalidade é variável. Pode haver perda patrimonial.',
                'liquidity_description' => 'As cotas não são resgatadas livremente como em uma classe aberta. A saída depende de venda no mercado secundário e da existência de compradores, podendo exigir prazo maior ou preço inferior ao esperado.',
                'profitability_description' => 'O resultado pode vir de distribuições feitas pelo fundo e da variação do preço das cotas. Rendimentos não são garantidos: vacância, inadimplência, despesas, gestão, juros e condições de mercado podem afetar distribuições e preços.',
                'advantages' => "- Acesso indireto a uma carteira ligada ao mercado imobiliário.\n- Estrutura coletiva e política de investimento formal.\n- Possibilidade de negociar cotas no mercado secundário quando houver liquidez.",
                'points_of_attention' => "- Fundos imobiliários são renda variável e podem gerar perdas.\n- A liquidez depende do volume de negociação das cotas.\n- Avaliar carteira, concentração, gestão, despesas e documentos do fundo.",
                'sources' => [SourceSeeder::CVM_FII_URL, SourceSeeder::CVM_FII_RISCOS_URL],
            ],
            [
                'category' => 'renda-variavel', 'name' => 'Ações', 'slug' => 'acoes', 'risk_level' => 'high', 'sort_order' => 1,
                'short_description' => 'Frações do capital de companhias que tornam o investidor acionista e expõem seu resultado ao negócio e ao mercado.',
                'description' => "## O que é?\n\nUma ação representa uma parcela do capital social de uma companhia. Quem compra torna-se acionista e participa economicamente dos resultados e riscos daquele negócio.\n\n## Como funciona?\n\nAções podem ser adquiridas e vendidas no mercado. Direitos variam conforme a espécie e as regras da companhia. O preço muda de acordo com resultados, expectativas e condições de mercado.",
                'risk_description' => 'A classificação didática é alta porque o retorno não é garantido, os preços podem oscilar de forma relevante e uma empresa pode ter prejuízo ou falir, levando a perdas substanciais ou totais do capital aplicado. O risco efetivo ainda varia entre companhias, setores e condições de mercado.',
                'liquidity_description' => 'A liquidez depende do volume de compradores e vendedores de cada ação. Papéis mais negociados podem ser vendidos com maior facilidade; outros podem exigir espera ou mudança de preço. Alta negociação não elimina risco de mercado.',
                'profitability_description' => 'O resultado pode vir da valorização do preço e de valores distribuídos pela companhia, quando existirem. Lucros, expectativas, setor, economia e decisões empresariais afetam o retorno. Não existe remuneração predeterminada nem promessa de ganho.',
                'advantages' => "- Participação econômica em uma companhia.\n- Possibilidade de negociação no mercado secundário.\n- Acesso a informações e documentos divulgados por companhias abertas.",
                'points_of_attention' => "- Preços podem oscilar e o capital pode ser perdido.\n- Analisar a companhia, o setor, os direitos da ação e os riscos da oferta.\n- Liquidez e desempenho passado não garantem resultados futuros.",
                'sources' => [SourceSeeder::CVM_ACOES_URL, SourceSeeder::CVM_CARACTERISTICAS_URL],
            ],
        ];

        foreach ($items as $data) {
            $investment = Investment::query()->updateOrCreate(
                ['slug' => $data['slug']],
                collect($data)->except(['category', 'sources'])->merge([
                    'investment_category_id' => $categories[$data['category']],
                    'taxation_description' => null,
                    'is_published' => true,
                ])->all(),
            );

            $investment->sources()->sync(
                collect($data['sources'])->mapWithKeys(fn (string $url, int $index) => [
                    $sources[$url] => ['sort_order' => $index + 1],
                ])->all(),
            );
        }
    }
}
