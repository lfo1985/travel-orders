# Travel Orders

**Versão:** 1.0.0  
Aplicação voltada para o gerenciamento de pedidos de viagens, oferecendo recursos como autenticação de usuários, controle de acesso e mecanismos de filtragem por status, destino e períodos de viagem.

---

## Instalação da Aplicação

1. Renomeie o arquivo `.env-example` para `.env`:

   ```bash
   mv .env-example .env
   ```

2. Instale as dependências do projeto:

   ```bash
   composer install
   ```

3. Construa e suba os containers com Docker (forçar o build e rodar em segundo plano):

   ```bash
   docker compose up --build -d
   ```

4. Acesse o container `php_app` para rodar as migrações e popular o banco de dados:

   ```bash
   docker exec -it php_app bash

   # Cria as tabelas
   php artisan migrate

   # Cria o primeiro usuário (senha: admin123456)
   php artisan db:seed --class=UserSeeder
   ```

### (Opcional) Criar dados de teste

Para popular a base com pedidos de viagem fictícios:

```bash
php artisan db:seed --class=OrderSeeder
```

> **IMPORTANTE:** Todos os comandos acima devem ser executados **dentro do container**, pois o sistema está configurado para rodar via Docker.

---

## Autenticação

### Login

**POST** `/login`

**Body:**

```json
{
  "email": "usuario@example.com",
  "password": "senha"
}
```

### Logout

**POST** `/logout`  
**Headers:** `Authorization: Bearer {token}`

---

## Endpoints de Usuários

### Listar todos os usuários

**GET** `/users`  
**Headers:** `Authorization: Bearer {token}`

### Buscar usuário por ID

**GET** `/users/{id}`  
**Headers:** `Authorization: Bearer {token}`

### Criar novo usuário

**POST** `/users`  
**Headers:** `Authorization: Bearer {token}`

**Body:**

```json
{
  "name": "Nome do Usuário",
  "email": "email@example.com",
  "password": "senha",
  "password_confirmation": "senha"
}
```

### Editar usuário

**PUT** `/users/{id}`  
**Headers:** `Authorization: Bearer {token}`

**Body:**

```json
{
  "name": "Novo Nome",
  "password": "nova_senha",
  "password_confirmation": "nova_senha"
}
```

### Deletar usuário

**DELETE** `/users/{id}`  
**Headers:** `Authorization: Bearer {token}`

---

## Endpoints de Pedidos (Orders)

### Listar todos os pedidos

**GET** `/orders`  
**Headers:** `Authorization: Bearer {token}`

### Filtros disponíveis

**GET** `/orders?{parametros}`  
**Headers:** `Authorization: Bearer {token}`

#### Por status

```bash
/orders?status=APPROVED
```

#### Por usuário

```bash
/orders?user_id=1
```

#### Por destino

```bash
/orders?destination_name=New+York
```

#### Por data de ida

```bash
/orders?departure_date_start=01/01/2025&departure_date_end=10/01/2025
```

#### Por data de retorno

```bash
/orders?return_date_start=01/01/2025&return_date_end=10/01/2025
```

#### Por intervalo de viagem

```bash
/orders?travel_departure_date=01/01/2025&travel_return_date=10/01/2025
```

---

### Criar novo pedido

**POST** `/order`  
**Headers:** `Authorization: Bearer {token}`

**Body:**

```json
{
  "user_id": 1,
  "costumer_name": "Nome do Cliente",
  "destination_name": "Destino",
  "departure_date": "01/01/2025",
  "return_date": "10/01/2025",
  "status": "REQUESTED"
}
```

### Editar pedido

**PUT** `/order/{id}`  
**Headers**

```
Authorization: Bearer {token}
auth_id: {id do usuário logado}
```

**Body:**

```json
{
  "costumer_name": "Novo Nome",
  "destination_name": "Novo Destino",
  "departure_date": "01/02/2025",
  "return_date": "10/02/2025",
  "status": "APPROVED"
}
```

### Deletar pedido

**DELETE** `/order/{id}`
**Headers**

```
Authorization: Bearer {token}
auth_id: {id do usuário logado}
```

---

### Atualizar status do pedido

**PATCH** `/orders/{id}/status`  
**Headers:**

```
Authorization: Bearer {token}
auth_id: {id do usuário logado}
```

**Body:**

```json
{
  "status": "APPROVED"
}
```

---

## Release Notes - v1.0.0

Full Changelog: https://github.com/lfo1985/travel-orders/commits/v1.0.0

## Licença

Este projeto está licenciado sob os termos da licença MIT.  
Veja o arquivo [LICENSE](LICENSE) para mais informações.