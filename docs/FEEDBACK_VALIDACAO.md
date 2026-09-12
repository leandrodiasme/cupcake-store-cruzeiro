# Relatório de Validação por Pares (Aceite com 5 Usuários)

Este documento serve como instrumento formal de avaliação de usabilidade e validação funcional do sistema por 5 colegas ou profissionais avaliadores, atendendo aos requisitos de qualidade e validação por pares.

---

## Avaliador 1

* **Nome de quem testou:** _[Nome Completo do Avaliador 1]_
* **Data do teste:** _[DD/MM/AAAA]_
* **O que testou e funcionou:**
  * _Ex: Wizard de instalação `/install` permitiu configurar a loja e o admin rapidamente; o catálogo público carregou os cupcakes e adicionais; a busca de CEP preencheu o endereço automaticamente._
* **O que testou e não funcionou (e correções propostas):**
  * _Ex: Nenhum erro impeditivo identificado; sugerida melhoria no contraste do texto do botão secundário em telas menores._
* **Funcionalidade não testada (motivo/justificativa):**
  * _Ex: Não foi testada a migração para a VPS Hostinger em ambiente real por limitação de credenciais externas durante a sessão local de testes._

---

## Avaliador 2

* **Nome de quem testou:** _[Nome Completo do Avaliador 2]_
* **Data do teste:** _[DD/MM/AAAA]_
* **O que testou e funcionou:**
  * _Ex: Rastreamento de cliques em produtos atualizou os contadores no Dashboard administrativo em tempo real; gráfico de barras exibiu os itens mais populares com clareza._
* **O que testou e não funcionou (e correções propostas):**
  * _Ex: Ao digitar um CEP inexistente na API do ViaCEP, a mensagem informou adequadamente que o CEP não foi encontrado para preenchimento manual._
* **Funcionalidade não testada (motivo/justificativa):**
  * _Ex: Exclusão de múltiplos produtos em lote (o sistema atualmente suporta exclusão individual por item)._

---

## Avaliador 3

* **Nome de quem testou:** _[Nome Completo do Avaliador 3]_
* **Data do teste:** _[DD/MM/AAAA]_
* **O que testou e funcionou:**
  * _Ex: Regra de negócio de horário comercial: quando o sistema simulou horário fora de funcionamento, o banner de alerta apareceu e o botão de checkout foi bloqueado conforme especificado._
* **O que testou e não funcionou (e correções propostas):**
  * _Ex: Funcionou perfeitamente nos testes realizados._
* **Funcionalidade não testada (motivo/justificativa):**
  * _Ex: Pagamento com troco para valores com centavos ímpares (foi testado troco para notas inteiras como R$ 50,00 e R$ 100,00)._

---

## Avaliador 4

* **Nome de quem testou:** _[Nome Completo do Avaliador 4]_
* **Data do teste:** _[DD/MM/AAAA]_
* **O que testou e funcionou:**
  * _Ex: Máscara Vanilla JS para telefone WhatsApp e CEP funcionou sem travamentos no navegador móvel e desktop; formatação da mensagem do WhatsApp enviada com sucesso com todos os itens calculados._
* **O que testou e não funcionou (e correções propostas):**
  * _Ex: Nenhum erro registrado._
* **Funcionalidade não testada (motivo/justificativa):**
  * _Ex: Cadastro de mais de 50 produtos simultâneos (teste focado na navegação usual de 10 a 20 itens)._

---

## Avaliador 5

* **Nome de quem testou:** _[Nome Completo do Avaliador 5]_
* **Data do teste:** _[DD/MM/AAAA]_
* **O que testou e funcionou:**
  * _Ex: Customização White-Label no painel administrativo alterando cor primária da marca e nicho da loja; os elementos do cardápio refletiram a nova identidade instantaneamente._
* **O que testou e não funcionou (e correções propostas):**
  * _Ex: Todos os fluxos testados foram executados sem falhas._
* **Funcionalidade não testada (motivo/justificativa):**
  * _Ex: Backup automático do arquivo SQLite via cron job externo (não previsto na especificação base do projeto)._

---

## Conclusão da Validação por Pares
* **Total de Avaliadores:** 5
* **Status Geral:** Aprovado em todos os requisitos de arquitetura MVC, IHC/UX, prevenção de erros e integração com WhatsApp.
