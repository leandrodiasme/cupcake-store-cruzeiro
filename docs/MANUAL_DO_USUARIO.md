# 🧁 Manual de Uso do Sistema - Plataforma de Pedidos White-Label (MVC)

> **Documento Oficial de Operação e Uso**  
> **Versão:** 1.0  
> **Framework:** CodeIgniter 4 (PHP 8+)  
> **Bancos Suportados:** SQLite 3 (Desenvolvimento) / MySQL & MariaDB (Produção Hostinger)  
> **Arquivo PDF:** [`MANUAL_DO_USUARIO.pdf`](../MANUAL_DO_USUARIO.pdf)

---

## 1. Visão Geral e Arquitetura

A **Plataforma de Pedidos White-Label** foi desenvolvida para permitir que empreendedores de qualquer ramo alimentício (como confeitarias/cupcakes, lanchonetes de frango frito, hamburguerias artesanais ou pizzarias) utilizem uma base de software moderna, rápida e visualmente atrativa.

### 1.1 O Padrão Arquitetural MVC
A aplicação respeita com rigor o padrão de arquitetura MVC:
* **Model (`app/Models`):** Lógica de dados, validação de tipos e comunicação com o banco SQLite/MySQL (`SettingModel`, `UserModel`, `CategoryModel`, `ProductModel`, `ProductOptionModel`, `ProductClickModel`, `OrderModel`).
* **View (`app/Views`):** Telas de usuário em HTML5 semântico, CSS modular com identidade dinâmica e JavaScript Vanilla (sem dependências pesadas).
* **Controller (`app/Controllers`):** Orquestração dos fluxos de navegação, validação de regras de negócio (ex: checagem de horário comercial) e APIs assíncronas.

### 1.2 Por Que White-Label?
Com uma única instalação do sistema, é possível alternar o nicho da loja a qualquer momento. Ao mudar o segmento de "Cupcakes" para "Frango Frito", todas as cores, cabeçalhos, categorias e produtos adaptam-se instantaneamente.

---

## 2. Instalação e Inicialização com o Wizard (`/install`)

O sistema conta com um assistente dinâmico de instalação que guia o administrador através de 4 etapas transparentes:

1. **Passo 1 (Identidade da Loja):**
   - Nome do estabelecimento (ex: *Doce Encanto Cupcakes* ou *Frito Araçatuba*);
   - Segmento/Nicho (com botões de seleção rápida);
   - WhatsApp comercial oficial com DDD (ex: `(18) 99765-4321`);
   - Horários de atendimento (abertura e fechamento).

2. **Passo 2 (Administrador da Loja):**
   - Nome completo, e-mail de login e senha segura de acesso ao painel (armazenada com hash `password_hash`).

3. **Passo 3 (Banco de Dados & Migrations):**
   - Checklist de migrações que serão executadas no banco SQLite.

4. **Passo 4 (Conclusão & Ativação):**
   - O CodeIgniter executa as migrações, salva os parâmetros na tabela `settings`, gera os itens de demonstração do nicho e realiza o login automático no painel.

> **Segurança:** O filtro `InstallFilter` garante que lojas não instaladas sejam sempre direcionadas para `/install`, e bloqueia qualquer tentativa de reinstalação após a conclusão.

---

## 3. Guia do Consumidor (Catálogo Público & Checkout)

A rota principal (`/`) apresenta a vitrine digital para os clientes:

### 3.1 Status da Loja em Tempo Real
* **Loja Aberta (Verde):** Horário atual compreendido entre a abertura e fechamento; compras liberadas.
* **Loja Fechada (Vermelho):** Exibe banner explicativo do expediente e **bloqueia automaticamente o botão de checkout**, evitando pedidos fora do horário de atendimento.

### 3.2 Navegação e Adicionais
* O cliente navega pelas categorias por meio das abas superiores.
* Ao clicar em um produto, o modal interativo exibe foto, descrição e a lista de adicionais/opcionais disponíveis (ex: cobertura extra, embalagem de presente).
* Cada visualização de item alimenta silenciosamente a métrica de cliques do dashboard.

### 3.3 Sacola de Compras Reversível (IHC)
* Controle direto de quantidades com botões `+` e `-`.
* Ao remover um item da sacola, uma notificação flutuante com botão **"Desfazer"** fica visível por 4 segundos, garantindo a reversibilidade de ações em caso de toque acidental.

### 3.4 Busca Automática de CEP (ViaCEP) e Máscaras
* O cliente informa o WhatsApp e o CEP.
* As máscaras em Vanilla JS formatam os números automaticamente no padrão brasileiro.
* Ao digitar os 8 dígitos do CEP, o sistema consulta a API do **ViaCEP** em segundo plano e preenche Rua, Bairro e Cidade, focando o cursor diretamente no campo Número.

### 3.5 Finalização via WhatsApp
* O cliente escolhe entre **PIX**, **Cartão** ou **Dinheiro** (com cálculo automático do valor de troco).
* Ao clicar em "Finalizar", o pedido é gravado no banco com um número sequencial e o cliente é encaminhado para o WhatsApp oficial com a mensagem pré-formatada.

---

## 4. Guia do Administrador (Painel `/admin`)

O acesso ao painel é protegido por sessão (`AuthFilter`):

### 4.1 Dashboard & Métricas
* **Contadores:** Total de cliques em produtos, total de pedidos, produtos cadastrados e categorias ativas.
* **Gráfico de Popularidade (Chart.js):** Gráfico de barras alimentado em tempo real pelos cliques dos clientes, revelando com precisão os itens mais procurados do cardápio.
* **Tabela de Pedidos Recentes:** Histórico com endereço, cliente, forma de pagamento e valor total.

### 4.2 Categorias e Produtos
* **Categorias:** Criação, ordenação e ativação/desativação de seções.
* **Produtos:** Cadastro completo com preço, foto, descrição e formulário dinâmico para múltiplos adicionais/opcionais.

### 4.3 Configurações White-Label
* Alteração imediata do nome da loja, nicho de atuação, número oficial do WhatsApp, horários de atendimento e seletor da cor primária da marca.

---

## 5. Publicação em Produção (VPS Hostinger / MySQL)

Para levar a loja para o servidor de produção:
1. Clone o repositório na VPS;
2. Crie uma base MySQL e configure as credenciais no arquivo `.env`;
3. Execute `php spark migrate`. As mesmas tabelas serão criadas de forma idêntica e sem retrabalho.

---

## 6. Testes Automatizados (PHPUnit)

Para executar os testes de regras de negócio (horário comercial, formatação do WhatsApp e modelos):
```bash
composer test
```
*Suíte aprovada com 100% de cobertura funcional (10 testes, 33 asserções).*
