<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Content;
use App\Models\Source;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->pluck('id', 'slug');
        $sources = Source::query()->pluck('id', 'url');

        $contents = [
            [
                'category' => 'fundamentos-financeiros',
                'source' => SourceSeeder::BCB_CADERNO_URL,
                'title' => 'Educação financeira: por onde começar',
                'slug' => 'educacao-financeira-por-onde-comecar',
                'summary' => 'Entenda como conhecimento, escolhas e organização formam uma base para cuidar melhor do dinheiro.',
                'estimated_minutes' => 4,
                'sort_order' => 1,
                'body' => <<<'MARKDOWN'
## Uma habilidade para o cotidiano

Educação financeira não começa com a escolha de um produto financeiro. Ela começa pela compreensão de como o dinheiro participa das decisões do dia a dia. O Caderno de Educação Financeira do Banco Central apresenta esse aprendizado como um conjunto de conhecimentos e comportamentos que pode apoiar a gestão dos recursos pessoais, o planejamento e escolhas mais conscientes.

Todas as pessoas tomam decisões financeiras: receber uma renda, pagar contas, comprar à vista ou a prazo, guardar parte do que recebem e definir prioridades. Por isso, desenvolver essa habilidade não depende de possuir muito dinheiro. O ponto de partida é observar a própria realidade com clareza.

## Um começo possível

Uma sequência simples de aprendizado é:

- reconhecer de onde vêm os recursos disponíveis;
- identificar para onde o dinheiro está indo;
- distinguir necessidades de desejos;
- relacionar escolhas atuais a objetivos futuros;
- conhecer os custos e os riscos antes de assumir compromissos;
- buscar informações em fontes confiáveis antes de decidir.

Esse processo não exige resolver tudo de uma vez. Registrar receitas e despesas por um período já ajuda a substituir impressões por informações concretas. A partir desse registro, fica mais fácil perceber hábitos, conversar sobre prioridades com quem compartilha o orçamento e definir objetivos possíveis.

## Autonomia exige contexto

Uma informação isolada raramente é suficiente para orientar uma decisão. Prazo, custo, risco, necessidade e situação familiar podem mudar o significado de uma escolha. A educação financeira contribui quando amplia a capacidade de fazer perguntas, comparar condições e reconhecer limites — não quando oferece uma resposta igual para todas as pessoas.

Começar, portanto, significa criar uma rotina de observação e aprendizado. O objetivo não é alcançar perfeição, e sim compreender melhor as consequências das escolhas e decidir de forma mais consciente. Este conteúdo é introdutório e não substitui uma análise individual da sua situação financeira.
MARKDOWN,
            ],
            [
                'category' => 'fundamentos-financeiros',
                'source' => SourceSeeder::BCB_CADERNO_URL,
                'title' => 'Orçamento pessoal',
                'slug' => 'orcamento-pessoal',
                'summary' => 'Aprenda a organizar receitas e despesas para compreender hábitos e apoiar seus objetivos.',
                'estimated_minutes' => 5,
                'sort_order' => 2,
                'body' => <<<'MARKDOWN'
## O que é um orçamento

O Banco Central apresenta o orçamento pessoal ou familiar como uma ferramenta de planejamento. Ele reúne, de forma organizada, os recursos que entram e os valores que saem. Mais do que uma lista de contas, o orçamento ajuda a responder duas perguntas básicas: de onde vem o dinheiro e para onde ele está indo?

Para que o retrato seja útil, é importante considerar todas as receitas e despesas. Receitas podem incluir salários e outros recursos efetivamente recebidos. Despesas abrangem tanto compromissos regulares quanto gastos menores e valores que aparecem apenas em determinados períodos do ano. Esquecer esses itens pode produzir uma visão incompleta.

## Um processo contínuo

A elaboração pode ser dividida em etapas simples:

1. **Planejar:** estimar as receitas e despesas do período.
2. **Registrar:** anotar o que realmente entrou e saiu.
3. **Agrupar:** organizar os registros em categorias compreensíveis.
4. **Avaliar:** comparar o planejado com o realizado e ajustar o próximo período.

O método pode ser um caderno, uma planilha ou outra ferramenta com a qual a pessoa se sinta confortável. A utilidade está na consistência dos registros, não na complexidade do instrumento.

## O que o resultado mostra

Quando as receitas são maiores que as despesas, há um resultado superavitário. Quando as despesas superam as receitas, o resultado é deficitário. Essa comparação não serve para julgar escolhas isoladas, mas para indicar se a organização atual é compatível com os recursos disponíveis e com os objetivos definidos.

Também é importante prever despesas sazonais, como compromissos que não acontecem todos os meses. Dividir mentalmente um gasto anual entre os meses, por exemplo, pode ajudar a antecipar seu impacto, sem tratá-lo como totalmente inesperado quando chegar.

Um orçamento não precisa permanecer igual. Mudanças na renda, na família ou nos objetivos pedem revisão. Ao registrar, avaliar e ajustar com regularidade, a pessoa constrói informações melhores para suas próximas decisões. Este conteúdo apresenta um método geral e não determina quanto cada pessoa deve gastar ou guardar.
MARKDOWN,
            ],
            [
                'category' => 'fundamentos-financeiros',
                'source' => SourceSeeder::BCB_CADERNO_URL,
                'title' => 'Juros compostos',
                'slug' => 'juros-compostos',
                'summary' => 'Veja como os juros incorporados ao saldo passam a participar dos cálculos seguintes.',
                'estimated_minutes' => 4,
                'sort_order' => 3,
                'body' => <<<'MARKDOWN'
## Juros e o tempo

O Caderno de Educação Financeira do Banco Central trata os juros como o valor associado ao uso do dinheiro ao longo do tempo. Para quem toma recursos de terceiros, eles representam um custo. Para quem cede recursos, podem representar uma remuneração. Entender a forma de cálculo é importante porque o tempo altera o resultado.

Nos juros simples, cada período considera apenas o valor principal inicial. Nos juros compostos, os juros calculados ao fim de um período são incorporados ao saldo. No período seguinte, o cálculo passa a considerar esse novo total. É daí que vem a expressão “juros sobre juros”.

## Exemplo exclusivamente hipotético

Imagine um valor inicial de R$ 100 e uma taxa didática de 10% por período, sem tarifas, impostos ou qualquer outro custo. Este exemplo serve apenas para mostrar a matemática e **não representa uma condição de mercado ou uma promessa de retorno**.

- após o primeiro período, os juros seriam R$ 10 e o saldo seria R$ 110;
- no segundo período, os 10% incidiriam sobre R$ 110, gerando R$ 11;
- ao final do segundo período, o saldo seria R$ 121.

Com juros simples sob as mesmas hipóteses, cada período acrescentaria R$ 10 ao valor inicial, chegando a R$ 120. A diferença aparece porque, no cálculo composto, o saldo acumulado se torna a base do período seguinte.

## O efeito vale em direções diferentes

O mecanismo pode atuar tanto em valores guardados quanto em dívidas. Em uma dívida, juros incorporados ao saldo podem elevar os cálculos posteriores. Por isso, é necessário observar a taxa, a periodicidade, o prazo e todos os custos da operação. Comparar somente a taxa apresentada, sem compreender essas condições, pode levar a uma leitura incompleta.

O exemplo também simplifica situações reais. Produtos e contratos podem incluir impostos, tarifas, datas específicas e regras próprias. A ideia central é: quando há capitalização composta, cada novo cálculo usa o saldo formado anteriormente. Compreender esse mecanismo ajuda a interpretar propostas, mas não substitui a leitura das condições de cada contrato.
MARKDOWN,
            ],
            [
                'category' => 'investimentos',
                'source' => SourceSeeder::CVM_CARACTERISTICAS_URL,
                'title' => 'Renda fixa e renda variável',
                'slug' => 'renda-fixa-e-renda-variavel',
                'summary' => 'Conheça a diferença entre formas de remuneração sem confundir renda fixa com ausência de risco.',
                'estimated_minutes' => 5,
                'sort_order' => 1,
                'body' => <<<'MARKDOWN'
## Duas formas de classificar investimentos

O Portal do Investidor explica que renda fixa e renda variável distinguem investimentos principalmente pela forma como sua remuneração é definida. Essa classificação, sozinha, não informa se uma alternativa é adequada para alguém e não elimina a necessidade de analisar risco, liquidez, prazo e demais condições.

Na **renda fixa**, a remuneração ou sua forma de cálculo é conhecida no momento da aplicação. Ela pode ser prefixada, quando a regra permite conhecer antecipadamente o valor previsto para uma data definida; pós-fixada, quando depende de um índice de referência; ou combinar componentes diferentes. Ao adquirir um título de renda fixa, o investidor geralmente empresta recursos a um emissor sob condições estabelecidas.

Conhecer a regra não significa ter um resultado sem incerteza. Títulos de renda fixa possuem riscos. O emissor pode não cumprir suas obrigações, mudanças de mercado podem afetar o preço de negociação e a saída antecipada pode ocorrer em condições diferentes das esperadas. Portanto, “renda fixa” não é sinônimo de rentabilidade garantida nem de investimento sem risco.

## E a renda variável?

Na **renda variável**, as condições de remuneração não são conhecidas de antemão da mesma maneira. O resultado pode depender do desempenho de um negócio, de uma carteira ou das condições de negociação no mercado. Ações são um exemplo conhecido: o retorno pode envolver distribuições da companhia e variações no preço, que também podem ser negativas.

## Comparar além do nome

Antes de considerar uma alternativa, observe:

- qual é a regra de remuneração;
- quais riscos estão presentes;
- em quanto tempo os recursos podem ser convertidos em dinheiro;
- quais são o prazo e as condições de saída;
- quais custos e regras fazem parte da operação.

Renda fixa e renda variável abrangem instrumentos distintos entre si. Dois investimentos classificados no mesmo grupo podem apresentar riscos e condições diferentes. A classificação é um ponto de partida para o estudo, não uma recomendação. A decisão depende dos objetivos e das circunstâncias de cada pessoa e deve ser tomada após a compreensão das condições específicas.
MARKDOWN,
            ],
            [
                'category' => 'investimentos',
                'source' => SourceSeeder::CVM_CARACTERISTICAS_URL,
                'title' => 'Risco e retorno',
                'slug' => 'risco-e-retorno',
                'summary' => 'Entenda por que retorno esperado não é resultado garantido e deve ser analisado junto aos riscos.',
                'estimated_minutes' => 5,
                'sort_order' => 2,
                'body' => <<<'MARKDOWN'
## Expectativa não é resultado

Ao investir, existe uma expectativa de rentabilidade, chamada de retorno esperado. O resultado efetivamente obtido, porém, só será conhecido ao longo do tempo e nas condições de resgate ou venda. Ele pode ser diferente do esperado. O Portal do Investidor apresenta o risco como a incerteza ligada a essa diferença.

Isso impede uma conclusão comum, mas incorreta: assumir que mais risco sempre produz mais retorno. A relação discutida no mercado é entre risco e **retorno esperado**. Para aceitar uma incerteza maior, investidores tendem a exigir uma expectativa maior de retorno. Essa expectativa não é garantia; o resultado pode ser menor e pode haver perdas.

## Riscos têm origens diferentes

Entre os riscos explicados pela fonte oficial estão:

- **risco de crédito:** possibilidade de uma contraparte não cumprir as obrigações combinadas;
- **risco de mercado:** mudanças nas condições econômicas e nas expectativas podem alterar preços;
- **risco de liquidez:** dificuldade de vender ou resgatar um investimento rapidamente por um valor justo;
- **risco legal:** problemas jurídicos podem dificultar o exercício dos direitos previstos;
- **risco operacional:** falhas humanas, administrativas ou de sistemas podem afetar a operação.

Essas categorias podem aparecer tanto em renda fixa quanto em renda variável. Um título de renda fixa, por exemplo, pode apresentar risco de crédito e sofrer variações de preço antes do vencimento. O nome do investimento não substitui a análise de suas condições.

## Analisar em conjunto

Observar apenas uma taxa ou um retorno anunciado deixa de fora parte essencial da decisão. É preciso entender quais hipóteses sustentam a expectativa, quais eventos podem mudar o resultado e se o prazo e a liquidez são compatíveis com o objetivo.

Promessas de retornos muito elevados, apresentadas como certas ou sem riscos correspondentes, exigem especial cautela. Rentabilidade passada também não garante rentabilidade futura. Comparar risco e retorno esperado é uma etapa de análise, não uma fórmula que prevê o futuro nem uma recomendação individualizada.
MARKDOWN,
            ],
            [
                'category' => 'investimentos',
                'source' => SourceSeeder::CVM_CARACTERISTICAS_URL,
                'title' => 'Liquidez',
                'slug' => 'liquidez',
                'summary' => 'Compreenda a facilidade de converter um investimento em dinheiro e por que isso não define sua segurança.',
                'estimated_minutes' => 4,
                'sort_order' => 3,
                'body' => <<<'MARKDOWN'
## O que a liquidez descreve

Liquidez é a facilidade ou rapidez com que um investimento pode ser resgatado, vendido ou convertido em dinheiro por um valor justo. Ela ajuda a responder uma pergunta prática: se os recursos forem necessários, em quais condições será possível utilizá-los novamente?

Um ativo pode exigir tempo para encontrar um comprador. Se o proprietário precisar vendê-lo rapidamente, talvez tenha de aceitar um valor inferior ao que consideraria justo. Em mercados com poucos interessados, essa dificuldade também pode ampliar diferenças de preço. Já uma alternativa com maior liquidez tende a permitir a conversão em dinheiro com mais facilidade, conforme suas regras.

## Liquidez depende do objetivo

O prazo em que o dinheiro poderá ser necessário influencia a importância dessa característica. Recursos associados a necessidades próximas ou imprevistas pedem atenção especial às condições de acesso. Objetivos distantes podem permitir prazos diferentes, mas isso depende da realidade e do planejamento de cada pessoa.

Antes de decidir, procure entender:

- se existe prazo mínimo ou vencimento;
- se o resgate é permitido antes dessa data;
- quanto tempo leva para o dinheiro ficar disponível;
- se a venda depende de encontrar outro interessado;
- se a saída antecipada pode afetar o valor recebido;
- quais custos e regras acompanham o resgate.

## Liquidez não é sinônimo de segurança

Um investimento com alta liquidez ainda pode apresentar outros riscos. Ele pode sofrer oscilações de mercado ou envolver o risco de o emissor não cumprir uma obrigação, por exemplo. Maior facilidade de resgate reduz uma dificuldade específica, mas não transforma automaticamente o investimento em seguro.

Da mesma forma, baixa liquidez não descreve sozinha todas as demais características do ativo. Retorno, risco e liquidez precisam ser observados em conjunto e relacionados ao objetivo. A liquidez é uma dimensão da análise — importante, mas insuficiente para sustentar uma decisão isoladamente. Este conteúdo não indica um produto nem define uma escolha adequada para qualquer pessoa.
MARKDOWN,
            ],
        ];

        foreach ($contents as $data) {
            $content = Content::query()->updateOrCreate(
                ['slug' => $data['slug']],
                [
                    'category_id' => $categories[$data['category']],
                    'title' => $data['title'],
                    'summary' => $data['summary'],
                    'body' => $data['body'],
                    'difficulty' => 'beginner',
                    'estimated_minutes' => $data['estimated_minutes'],
                    'sort_order' => $data['sort_order'],
                    'is_published' => true,
                ],
            );

            $content->sources()->sync([$sources[$data['source']] => ['sort_order' => 1]]);
        }
    }
}
