# Referências de rentabilidade

O catálogo editorial em `config/return_references.php` relaciona o slug publicado aos indicadores permitidos. Cada observação registra nome, tipo, percentual, unidade, período, data-base, fonte HTTPS, explicação, limitações e intervalo de revisão em dias. Para fundos, a pessoa escolhe explicitamente qual comparação usar. Modalidades adicionais funcionam com taxa manual.

## Atualização das observações

Confira o período e a unidade no documento oficial indicado em cada registro. Para índices de retorno total, use a linha “Em um ano”, em vez da variação acumulada desde janeiro. Para poupança, use a remuneração total de um aniversário e anualize por capitalização, repetindo doze vezes o mesmo fator mensal. Para a taxa DI, use o percentual anualizado do dia, não o fator diário. Registre a data da observação. Se faltar valor verificado, use `null`.

Os prazos editoriais de revisão são 7 dias corridos para DI, 31 para poupança e índices, e 45 para a meta Selic. Não representam validade oficial. Revise antes se houver nova decisão do Copom. Observações vencidas ficam visíveis com aviso e sem botão de aplicação. Fontes aceitas estão na lista de hosts HTTPS de `ReturnReferences`. A navegação e o cálculo funcionam sem consultas externas.

## Persistência e implantação

O servidor valida modalidade, identificador da edição, taxa e prazo de revisão antes de aplicar uma referência. O cálculo usa juros mensais equivalentes sobre o saldo anterior, seguidos do aporte. O rendimento é a diferença entre saldo e total investido, inclusive quando negativa. Ao salvar, `simulations.annual_rate` registra a taxa utilizada. A coluna JSON opcional `rate_reference` guarda a observação daquele momento. Registros antigos e cenários manuais usam valor nulo. Alterações no catálogo e novas execuções de seeders preservam as simulações anteriores.

No Railway, mantenha o build Vite e aplique a migration incremental com `php artisan migrate --force` no pre-deploy. Recrie o cache de configuração caso seja utilizado. Verifique no simulador a seleção de indicador, a edição manual e a consulta do cenário salvo.
