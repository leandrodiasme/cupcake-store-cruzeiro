# 🧁 Plataforma de Pedidos White-Label (CodeIgniter 4 + SQLite)

> Aplicação web completa de pedidos e cardápio digital white-label desenvolvida em **PHP (CodeIgniter 4)** seguindo rigorosamente o padrão arquitetural **MVC (Model-View-Controller)**, com banco de dados **SQLite 3** para desenvolvimento local ágil e total compatibilidade com **MySQL / MariaDB** para produção na VPS Hostinger.

---

## 📺 Vídeo de Demonstração (Até 5 minutos)

> Espaço reservado para inclusão do link do vídeo de apresentação da solução em funcionamento:
> 
> **Link do Vídeo Demonstrativo:** `[INSERIR_LINK_DO_VIDEO_AQUI - Ex: YouTube / Google Drive / Loom]`

---

## 🚀 Principais Funcionalidades

1. **Wizard de Instalação Dinâmico (`/install`):**
   - Assistente em 4 passos para reuso instantâneo em múltiplos nichos (ex: Cupcakes, Frango Frito, Hamburguerias, Pizzarias).
   - Coleta dados da loja, WhatsApp oficial, horários de funcionamento e credenciais do primeiro Administrador.
   - Execução automática e programática das *Migrations* do CodeIgniter 4.
   - População automática com itens demonstrativos do nicho escolhido.

2. **Interface de Usuário & IHC (Front-end):**
   - Desenvolvida em HTML5 semântico, CSS moderno e Vanilla JavaScript (sem dependências pesadas).
   - Princípios de UX: visual limpo, consistente, estético e com feedback imediato via Toasts.
   - Prevenção de erros e reversibilidade de ações (adicionar/remover/ajustar quantidades na sacola com opção "Desfazer").
   - Busca automática de endereço com integração à API **ViaCEP** (`https://viacep.com.br/ws/{cep}/json/`).
   - Máscaras em Vanilla JS para Telefone WhatsApp `(99) 99999-9999` e CEP `99999-999`.

3. **Regras de Negócio & Checkout:**
   - **Horário Comercial:** Validação em tempo real do horário de abertura e fechamento; fora do expediente, a loja alerta visualmente e bloqueia o botão de checkout.
   - **Formatação para WhatsApp:** Conversão automática dos dados de entrega, itens, opcionais, total e forma de pagamento em uma mensagem formatada e codificada na URL do WhatsApp (`wa.me`).

4. **Painel Administrativo & Métricas:**
   - Autenticação e sessão protegida para administradores.
   - **Gráfico de Popularidade (Chart.js):** Rastreamento de cliques em produtos computado em tempo real.
   - CRUD completo de Categorias e Produtos com suporte a múltiplos adicionais/opcionais.
   - Customização White-Label (nome, nicho, horário, WhatsApp e paleta de cores primárias).

5. **Documentação e Requisitos Acadêmicos:**
   - [Dicionário de Dados Completo](docs/DICIONARIO_DE_DADOS.md).
   - [Relatório de Validação por Pares (5 Usuários)](docs/FEEDBACK_VALIDACAO.md).

---

## 🛠️ Tecnologias e Arquitetura MVC

* **Linguagem:** PHP 8.2+ (Testado no PHP 8.5)
* **Framework:** CodeIgniter 4 (v4.7.4)
* **Padrão:** MVC puro (`app/Models`, `app/Views`, `app/Controllers`)
* **Banco Local:** SQLite 3 (`writable/database.db`)
* **Banco Produção:** MySQL 8 / MariaDB (via Database Forge migrations agnósticas)
* **Versionamento:** Git / GitHub

---

## 💻 Instruções para Execução Local

### 1. Clonar o Repositório e Instalar Dependências
```bash
git clone https://github.com/seu-usuario/cupcake-store.git
cd cupcake-store
composer install
```

### 2. Configurar o Ambiente
O arquivo `.env` já vem pré-configurado para SQLite local:
```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080/'
database.default.DBDriver = SQLite3
database.default.database = database.db
database.default.foreignKeys = true
```

### 3. Iniciar o Servidor de Desenvolvimento
```bash
php spark serve
```
Acesse no seu navegador: **`http://localhost:8080`**

Ao acessar pela primeira vez, o filtro do sistema redirecionará automaticamente para o **Wizard de Instalação** (`/install`).

---

## 🌐 Implantação em Produção (VPS Hostinger / MySQL)

1. Clone o repositório na sua VPS Hostinger ou envie os arquivos via Git / SCP.
2. Crie uma base de dados MySQL e usuário pelo painel da Hostinger (ou via terminal MySQL).
3. No arquivo `.env` da VPS, configure:
   ```ini
   CI_ENVIRONMENT = production
   app.baseURL = 'https://sualoja.com.br/'
   
   database.default.DBDriver = MySQLi
   database.default.hostname = localhost
   database.default.database = u123456_sualoja
   database.default.username = u123456_usuario
   database.default.password = SuaSenhaForte123!
   database.default.port = 3306
   ```
4. Execute as migrations no servidor:
   ```bash
   php spark migrate
   ```

---

## 🧪 Testes Automatizados (PHPUnit)

O projeto possui suíte de testes unitários para validar regras de negócio de horário comercial, formatação do WhatsApp e integridade das migrações:

```bash
php vendor/bin/phpunit
```
