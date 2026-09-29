# 📐 Modelagem UML - Plataforma de Pedidos White-Label (PIT II)

> **Documento de Engenharia de Software:** Especificação de requisitos e modelagem estrutural e comportamental da aplicação em conformidade com as diretrizes do Projeto Integrador Transdisciplinar em Engenharia de Software II (Cruzeiro do Sul Virtual).

---

## 1. Diagrama de Casos de Uso (Use Case Diagram)

O diagrama abaixo especifica os atores envolvidos e as interações funcionais suportadas pela solução, destacando relações de inclusão (`<<include>>`) e extensão (`<<extend>>`).

```mermaid
usecaseDiagram
```
### Visualização em Mermaid:

```mermaid
flowchart TD
    subgraph Atores
        Cliente(["👤 Cliente / Consumidor"])
        Admin(["👨‍💼 Administrador / Lojista"])
        ViaCEP[("🌐 API Externa ViaCEP")]
        WhatsApp[("📱 API Externa WhatsApp")]
    end

    subgraph "Casos de Uso - Área Pública do Cliente"
        UC01([UC01: Visualizar Cardápio por Categoria])
        UC02([UC02: Personalizar Produto com Opcionais & Observações])
        UC03([UC03: Gerenciar Sacola de Compras com Reversibilidade])
        UC04([UC04: Consultar Endereço por CEP])
        UC05([UC05: Validar Horário Comercial da Loja])
        UC06([UC06: Selecionar Pagamento e Validar Troco em Dinheiro])
        UC07([UC07: Fechar Pedido e Redirecionar ao WhatsApp])
    end

    subgraph "Casos de Uso - Área Administrativa & Instalação"
        UC08([UC08: Executar Wizard Dinâmico de Instalação])
        UC09([UC09: Autenticar Administrador])
        UC10([UC10: Analisar Métricas de Popularidade / Gráfico Chart.js])
        UC11([UC11: Gerenciar Categorias CRUD])
        UC12([UC12: Gerenciar Produtos e Opcionais CRUD])
        UC13([UC13: Parametrizar White-Label Cor, Nicho, Horários])
    end

    %% Relacionamentos do Cliente
    Cliente --> UC01
    Cliente --> UC02
    Cliente --> UC03
    Cliente --> UC06
    Cliente --> UC07

    UC02 -.->|<<extend>>| UC01
    UC07 -.->|<<include>>| UC05
    UC07 -.->|<<include>>| UC06
    UC06 -.->|<<include>>| UC04

    UC04 <--> ViaCEP
    UC07 --> WhatsApp

    %% Relacionamentos do Administrador
    Admin --> UC08
    Admin --> UC09
    Admin --> UC10
    Admin --> UC11
    Admin --> UC12
    Admin --> UC13
```

---

## 2. Diagrama de Classes de Domínio e Persistência (Class Diagram)

Representa a estrutura orientada a objetos da aplicação no padrão MVC, com seus atributos tipados, métodos principais e a integridade referencial com cardinalidades.

```mermaid
classDiagram
    direction TB

    class Setting {
        -string key
        -string value
        -datetime updated_at
        +getVal(key, default) string
        +setVal(key, value) bool
        +isInstalled() bool
        +getAllKeyValue() array
    }

    class User {
        -int id
        -string name
        -string email
        -string password
        -string role
        -datetime created_at
        -datetime updated_at
        +verifyPassword(plainPassword) bool
        +hashPassword(password) string
    }

    class Category {
        -int id
        -string name
        -string description
        -int display_order
        -bool active
        -datetime created_at
        -datetime updated_at
        +getActiveCategories() array
    }

    class Product {
        -int id
        -int category_id
        -string name
        -string description
        -float price
        -string image_url
        -bool active
        -datetime created_at
        -datetime updated_at
        +getOptions() array
        +calculateSubtotal(quantity, selectedOptions) float
    }

    class ProductOption {
        -int id
        -int product_id
        -string name
        -float price
        -bool active
    }

    class ProductClick {
        -int id
        -int product_id
        -string ip_address
        -string user_agent
        -datetime clicked_at
        +recordClick(productId, ip, userAgent) bool
    }

    class Order {
        -int id
        -string customer_name
        -string customer_phone
        -string cep
        -string street
        -string number
        -string neighborhood
        -string city
        -string complement
        -string payment_method
        -float change_for
        -float total_amount
        -string order_items_json
        -string status
        -datetime created_at
        +getItems() array
        +formatSummary() string
    }

    %% Relacionamentos
    Category "1" *-- "0..*" Product : agrupa
    Product "1" *-- "0..*" ProductOption : compõe
    Product "1" o-- "0..*" ProductClick : rastreia
    Order "1" o-- "1..*" Product : referencia itens
```

---

## 3. Diagrama de Sequência: Processo de Realização de Pedido

Mapeia o ciclo de vida completo de uma requisição de fechamento de pedido no padrão MVC, demonstrando a troca de mensagens síncronas/assíncronas entre os objetos, validações de negócio e integração com APIs externas.

```mermaid
sequenceDiagram
    autonumber
    actor Cliente as 👤 Cliente
    participant View as 🖥️ View (index.php)
    participant ViaCEP as 🌐 ViaCEP API
    participant Controller as ⚙️ Api (Controller)
    participant SettingModel as 🗄️ SettingModel
    participant OrderModel as 🗄️ OrderModel
    participant DB as 💾 Banco (SQLite/MySQL)
    participant WhatsApp as 📱 WhatsApp (wa.me)

    Cliente->>View: 1. Informa CEP no Checkout (ex: 16010-000)
    View->>ViaCEP: 2. GET https://viacep.com.br/ws/16010000/json/
    ViaCEP-->>View: 3. Retorna {logradouro, bairro, localidade}
    View->>View: 4. Autopreencha Rua, Bairro e Cidade

    Cliente->>View: 5. Seleciona "Dinheiro" e digita troco para R$ 50,00
    View->>View: 6. Valida se troco >= total e calcula valor do troco
    Cliente->>View: 7. Clica em "Finalizar no WhatsApp"

    View->>Controller: 8. POST /api/create-order (JSON do pedido)
    Controller->>SettingModel: 9. Obter horários comerciais (opening_time, closing_time)
    SettingModel-->>Controller: 10. Retorna horários configurados
    Controller->>Controller: 11. Valida se a loja está aberta (isStoreOpen)
    Controller->>Controller: 12. Valida se troco em dinheiro é consistente (isValidCashChange)

    alt Loja fechada ou troco menor que o total
        Controller-->>View: 13a. HTTP 403/422 {status: 'error', message: '...'}
        View-->>Cliente: 14a. Exibe Toast/Alerta de Erro de Validação
    else Dados válidos e loja em expediente
        Controller->>OrderModel: 13b. insert(dadosDoPedido, itens_json)
        OrderModel->>DB: 14b. INSERT INTO orders (...)
        DB-->>OrderModel: 15b. ID do Pedido gerado (#ID 42)
        OrderModel-->>Controller: 16b. Confirmação de persistência
        Controller->>Controller: 17b. formatWhatsAppMessage() + urlencode(message)
        Controller->>Controller: 18b. generateWhatsAppUrl(phone, encodedMessage)
        Controller-->>View: 19b. HTTP 200 {status: 'success', whatsapp_url: 'https://wa.me/...'}
        View->>WhatsApp: 20b. window.open(whatsapp_url, '_blank')
        WhatsApp-->>Cliente: 21b. Abre aplicativo/web do WhatsApp com texto estruturado
    end
```

---

## 4. Diagrama de Atividades: Fluxo de Checkout e Tolerância a Falhas

Ilustra as bifurcações de decisão e o fluxo comportamental de execução com base nas regras de negócio e de IHC.

```mermaid
flowchart TD
    Start([Início]) --> OpenCatalog[Cliente navega no cardápio]
    OpenCatalog --> SelectProduct[Clica em um item]
    SelectProduct --> TrackClick[Sistema registra clique em product_clicks via AJAX]
    TrackClick --> CustomizeItem[Seleciona opcionais e digita observação]
    CustomizeItem --> AddToCart[Adiciona item à sacola]

    AddToCart --> CheckUndo{Deseja remover item?}
    CheckUndo -- Sim --> RemoveItem[Remove item e ativa Toast de 5s]
    RemoveItem --> ClickUndo{Clicou em Desfazer antes de 5s?}
    ClickUndo -- Sim --> RestoreItem[Item é restaurado na sacola]
    RestoreItem --> CartReady[Sacola atualizada]
    ClickUndo -- Não --> CartReady
    CheckUndo -- Não --> CartReady

    CartReady --> OpenCheckout[Abre modal de Checkout]
    OpenCheckout --> InputCEP[Digita os 8 dígitos do CEP]
    InputCEP --> FetchViaCEP[Consulta assíncrona ViaCEP]
    FetchViaCEP --> AutoFill[Campos de Rua, Bairro e Cidade preenchidos]

    AutoFill --> SelectPayment[Seleciona Forma de Pagamento]
    SelectPayment --> IsCash{Pagamento em Dinheiro?}
    
    IsCash -- Sim --> InputChange[Digita valor para troco]
    InputChange --> ValidateChange{Troco >= Total?}
    ValidateChange -- Não --> ChangeAlert[Exibe aviso em vermelho e bloqueia envio]
    ChangeAlert --> InputChange
    ValidateChange -- Sim --> ShowGreenFeedback[Calcula troco a devolver e exibe em verde]
    ShowGreenFeedback --> SubmitOrder[Clica em Finalizar Pedido]

    IsCash -- Não --> SubmitOrder

    SubmitOrder --> CheckStoreHours{Loja está aberta?}
    CheckStoreHours -- Não --> ShowClosedAlert[Notifica que o estabelecimento está fechado]
    ShowClosedAlert --> EndFail([Pedido Bloqueado])

    CheckStoreHours -- Sim --> PersistOrder[Salva registro completo na tabela orders]
    PersistOrder --> EncodeURL[Formata mensagem e sanitiza via urlencode]
    EncodeURL --> RedirectWhatsApp[Redireciona para URL do wa.me]
    RedirectWhatsApp --> EndSuccess([Pedido Concluído com Sucesso])
```
