# Relatório de Testes de Aceite e Validação

Este documento contém os resultados dos testes de validação realizados por profissionais e colegas de área para a Plataforma de Pedidos White-Label, documentando as melhorias implementadas no projeto.

## 1. Feedback de Teste - Beatriz
* **Perfil:** Tech Recruiter
* **Data do teste:** 08/09/2026
* **O que testou e funcionou:** Testou o Painel Administrativo (`/admin`), especificamente a criação de produtos e as configurações de personalização (cores e nome da loja). A interface respondeu bem e a troca de cores refletiu no front-end na mesma hora.
* **O que testou e não funcionou (bugs):** O gráfico de "Produtos mais clicados" no Dashboard (Chart.js) ficou desconfigurado ao abrir o painel administrativo pelo celular, ultrapassando o limite da tela. *(Solução aplicada: Adicionado `max-width: 100%` no CSS do container do gráfico).*
* **Funcionalidade não testada:** Não testou o fluxo de compra do cliente.

## 2. Feedback de Teste - Felipe Ramos
* **Data do teste:** 09/09/2026
* **O que testou e funcionou:** Testou a navegação do cliente, a visualização do cardápio e a adição de itens com variações (ex: Adicional de Granulado). O cálculo do subtotal dinâmico funcionou perfeitamente.
* **O que testou e não funcionou (bugs):** O botão de "Desfazer" (Toast) ao excluir um item do carrinho desaparecia muito rápido (estava configurado para 2 segundos), não dando tempo de clicar. *(Solução aplicada: Aumentado o tempo de exibição do Toast para 5 segundos conforme boas práticas de IHC).*
* **Funcionalidade não testada:** Não testou o Wizard de instalação inicial.

## 3. Feedback de Teste - Renan Santos
* **Data do teste:** 09/09/2026
* **O que testou e funcionou:** Testou o Wizard de Instalação Dinâmico (`/install`). A execução das *migrations* pelo navegador rodou lisa, criando o banco SQLite e populando os dados de demonstração.
* **O que testou e não funcionou (bugs):** No Passo 1 do Wizard, foi possível avançar deixando o campo "WhatsApp Oficial" em branco, o que quebrava o envio de pedidos depois. *(Solução aplicada: Adicionada validação de campo obrigatório `required` no HTML e no Controller `InstallFilter`).*
* **Funcionalidade não testada:** Não testou o painel de métricas do administrador.

## 4. Feedback de Teste - Erasmo Cossatto
* **Data do teste:** 10/09/2026
* **O que testou e funcionou:** Testou a tela de checkout e a integração com a API do ViaCEP. A máscara de input formatou o CEP corretamente e puxou Rua e Bairro de forma automática.
* **O que testou e não funcionou (bugs):** Se o cliente selecionasse a forma de pagamento "Dinheiro" e digitasse um valor de troco menor que o valor total da compra, o sistema aceitava. *(Solução aplicada: Adicionada uma validação via JavaScript impedindo o fechamento do pedido se o troco for inferior ao total).*
* **Funcionalidade não testada:** Não testou a edição de categorias no painel admin.

## 5. Feedback de Teste - William Teodoro
* **Data do teste:** 11/09/2026
* **O que testou e funcionou:** Fechamento completo do pedido e o redirecionamento final para o aplicativo do WhatsApp com a string codificada.
* **O que testou e não funcionou (bugs):** Quando o cliente digitava caracteres especiais (como `&` ou `#`) no campo de "Observações do Produto", a URL do WhatsApp quebrava e cortava a mensagem pela metade. *(Solução aplicada: Implementada a função nativa `urlencode()` do PHP na string de observações antes de gerar o link `wa.me`).*
* **Funcionalidade não testada:** Não testou o acesso ao sistema fora do horário comercial (badge de loja fechada).
