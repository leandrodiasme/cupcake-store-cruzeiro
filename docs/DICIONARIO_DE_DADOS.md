# Dicionário de Dados - Plataforma de Pedidos White-Label (MVC)

Este documento descreve detalhadamente a estrutura do banco de dados relacional da aplicação, projetado com total compatibilidade entre **SQLite 3** (ambiente de desenvolvimento local) e **MySQL / MariaDB** (ambiente de produção na VPS Hostinger) através das *Migrations* do CodeIgniter 4 (`Database Forge`).

---

## 1. Visão Geral das Tabelas

| Tabela | Finalidade Principal |
| :--- | :--- |
| [`settings`](#tabela-settings) | Armazenamento de parâmetros white-label da loja (nome, nicho, WhatsApp, horários, cor). |
| [`users`](#tabela-users) | Usuários administradores do sistema com senha criptografada via hash. |
| [`categories`](#tabela-categories) | Categorias de produtos do cardápio e controle de ordem de exibição. |
| [`products`](#tabela-products) | Produtos e itens comercializados no cardápio online. |
| [`product_options`](#tabela-product_options) | Adicionais e opcionais vinculados aos produtos (ex: coberturas extras, molhos). |
| [`product_clicks`](#tabela-product_clicks) | Registro analítico de cliques/interações para o gráfico de itens mais populares. |
| [`orders`](#tabela-orders) | Histórico de pedidos enviados para o WhatsApp oficial com dados de entrega e pagamento. |

---

## 2. Detalhamento dos Campos por Tabela

### Tabela: `settings`
Armazena pares chave-valor para customização White-Label e controle de estado de instalação do sistema.

| Coluna | Tipo | Nulo | Chave | Padrão | Descrição |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `key` | VARCHAR(100) | Não | PK | - | Identificador único da configuração (ex: `store_name`, `whatsapp_number`, `is_installed`). |
| `value` | TEXT | Sim | - | NULL | Conteúdo ou valor associado à configuração. |
| `updated_at` | DATETIME | Sim | - | NULL | Data e hora da última alteração. |

---

### Tabela: `users`
Controle de acesso à área restrita do Painel Administrativo.

| Coluna | Tipo | Nulo | Chave | Padrão | Descrição |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | INT(11) UNSIGNED | Não | PK | AUTO_INCREMENT | Identificador numérico do usuário. |
| `name` | VARCHAR(100) | Não | - | - | Nome completo do administrador. |
| `email` | VARCHAR(191) | Não | UNIQUE | - | E-mail de login exclusivo. |
| `password` | VARCHAR(255) | Não | - | - | Hash seguro da senha (`password_hash` BCRYPT). |
| `role` | VARCHAR(20) | Não | - | 'admin' | Nível de privilégio no painel. |
| `created_at` | DATETIME | Sim | - | NULL | Data de cadastro. |
| `updated_at` | DATETIME | Sim | - | NULL | Data da última atualização. |

---

### Tabela: `categories`
Classificação dos itens do catálogo em seções navegáveis.

| Coluna | Tipo | Nulo | Chave | Padrão | Descrição |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | INT(11) UNSIGNED | Não | PK | AUTO_INCREMENT | Identificador numérico da categoria. |
| `name` | VARCHAR(100) | Não | - | - | Nome da categoria (ex: Cupcakes Tradicionais). |
| `description` | TEXT | Sim | - | NULL | Breve descrição da seção. |
| `display_order` | INT(11) | Não | - | 0 | Prioridade de exibição na interface pública. |
| `active` | TINYINT(1) | Não | - | 1 | Flag de visibilidade (1 = Ativo, 0 = Oculto). |
| `created_at` | DATETIME | Sim | - | NULL | Data de criação. |
| `updated_at` | DATETIME | Sim | - | NULL | Data de atualização. |

---

### Tabela: `products`
Catálogo de itens comercializados.

| Coluna | Tipo | Nulo | Chave | Padrão | Descrição |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | INT(11) UNSIGNED | Não | PK | AUTO_INCREMENT | Identificador numérico do produto. |
| `category_id` | INT(11) UNSIGNED | Não | FK | - | Chave estrangeira referenciando `categories(id)`. |
| `name` | VARCHAR(150) | Não | - | - | Título do produto (ex: Cupcake Red Velvet). |
| `description` | TEXT | Sim | - | NULL | Ingredientes e detalhes descritivos. |
| `price` | DECIMAL(10,2) | Não | - | 0.00 | Preço unitário base em Reais (BRL). |
| `image_url` | VARCHAR(255) | Sim | - | NULL | URL da imagem representativa do item. |
| `active` | TINYINT(1) | Não | - | 1 | Flag de disponibilidade para pedidos (1 = Ativo, 0 = Inativo). |
| `created_at` | DATETIME | Sim | - | NULL | Data de cadastro. |
| `updated_at` | DATETIME | Sim | - | NULL | Data de edição. |

*Restrição de Integridade:* `ON DELETE CASCADE` com `categories(id)`.

---

### Tabela: `product_options`
Complementos selecionáveis que podem incrementar o valor do produto.

| Coluna | Tipo | Nulo | Chave | Padrão | Descrição |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | INT(11) UNSIGNED | Não | PK | AUTO_INCREMENT | Identificador numérico do opcional. |
| `product_id` | INT(11) UNSIGNED | Não | FK | - | Chave estrangeira referenciando `products(id)`. |
| `name` | VARCHAR(100) | Não | - | - | Nome do adicional (ex: Cobertura Extra de Nutella). |
| `price` | DECIMAL(10,2) | Não | - | 0.00 | Valor adicional cobrado por unidade. |
| `active` | TINYINT(1) | Não | - | 1 | Flag de disponibilidade do opcional. |

*Restrição de Integridade:* `ON DELETE CASCADE` com `products(id)`.

---

### Tabela: `product_clicks`
Métricas de IHC e rastreamento de interesse de consumidores para alimentação do gráfico de popularidade no Dashboard.

| Coluna | Tipo | Nulo | Chave | Padrão | Descrição |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | INT(11) UNSIGNED | Não | PK | AUTO_INCREMENT | Identificador sequencial do clique. |
| `product_id` | INT(11) UNSIGNED | Não | FK | - | Chave estrangeira referenciando `products(id)`. |
| `ip_address` | VARCHAR(45) | Sim | - | NULL | Endereço IP do cliente (compatível com IPv4 e IPv6). |
| `user_agent` | VARCHAR(255) | Sim | - | NULL | Informações do navegador/dispositivo do visitante. |
| `clicked_at` | DATETIME | Sim | - | NULL | Data e hora exatas do evento de clique. |

---

### Tabela: `orders`
Registro de controle dos pedidos gerados e convertidos para o link de WhatsApp.

| Coluna | Tipo | Nulo | Chave | Padrão | Descrição |
| :--- | :--- | :---: | :---: | :---: | :--- |
| `id` | INT(11) UNSIGNED | Não | PK | AUTO_INCREMENT | Número sequencial do pedido (#ID). |
| `customer_name` | VARCHAR(100) | Não | - | - | Nome informado pelo cliente. |
| `customer_phone` | VARCHAR(30) | Não | - | - | Telefone com WhatsApp do cliente. |
| `cep` | VARCHAR(10) | Não | - | - | CEP do endereço (consultado via ViaCEP). |
| `street` | VARCHAR(150) | Não | - | - | Nome do logradouro/rua. |
| `number` | VARCHAR(30) | Não | - | - | Número residencial ou comercial. |
| `neighborhood` | VARCHAR(100) | Não | - | - | Bairro de entrega. |
| `city` | VARCHAR(100) | Não | - | - | Cidade de entrega. |
| `complement` | VARCHAR(100) | Sim | - | NULL | Complemento (apto, bloco, ponto de referência). |
| `payment_method` | VARCHAR(50) | Não | - | - | Opção de pagamento: `PIX`, `Cartão` ou `Dinheiro`. |
| `change_for` | DECIMAL(10,2) | Sim | - | NULL | Valor informado para troco em dinheiro. |
| `total_amount` | DECIMAL(10,2) | Não | - | 0.00 | Valor total final do pedido somando itens e opcionais. |
| `order_items_json` | TEXT | Não | - | - | Snapshot em JSON estruturado de todos os itens e adicionais. |
| `status` | VARCHAR(30) | Não | - | 'sent_whatsapp' | Situação atual do pedido. |
| `created_at` | DATETIME | Sim | - | NULL | Data e hora de finalização do pedido. |

---

## 3. Compatibilidade e Migração para VPS (MySQL/MariaDB)

Todas as definições de esquema acima foram criadas através de classes `CodeIgniter\Database\Migration` utilizando a API agnóstica `$this->forge`.
Para migrar da base local SQLite para a base MySQL na Hostinger VPS:
1. Altere o driver no arquivo `.env` para `DBDriver = MySQLi`.
2. Configure credenciais da VPS (`hostname`, `database`, `username`, `password`).
3. Execute `php spark migrate`. O banco na VPS será gerado de forma 100% idêntica e consistente.
