# TODO List API - Desafio Softpar

## Descrição do Projeto

Este projeto é uma aplicação de gerenciamento de tarefas, desenvolvida como parte do desafio de programação da Softpar 2025. A aplicação permite criar, visualizar, atualizar e excluir tarefas, além de oferecer funcionalidades extras, como categorização e documentação interativa da API.

---
## Índice

1. [Introdução](#1-introdução)
2. [Funcionalidades](#2-funcionalidades)
3. [Tecnologias Utilizadas](#3-tecnologias-utilizadas)
4. [Pré-requisitos](#4-pré-requisitos)
5. [Configuração do Ambiente](#5-configuração-do-ambiente)
    - [Backend](#backend)
    - [Frontend](#frontend)
6. [Endpoints da API](#6-endpoints-da-api)
7. [Testando a Aplicação](#7-testando-a-aplicação)
8. [Funcionalidades Extras e Como Testá-las](#8-funcionalidades-extras-e-como-testá-las)
9. [Organização do Código](#9-organização-do-código)
10. [Melhorias Futuras](#10-melhorias-futuras)


## Funcionalidades

### Funcionalidades Básicas
- Criar tarefas com:
  - **Título**
  - **Descrição**
  - **Categoria** (Casa, Estudos, Lazer, Mercado, Contas, Outros)
  - **Data e hora de criação** (gerado automaticamente)
  - **Status** (Pendente, Em andamento, Concluído)
- Atualizar tarefas (incluindo status e categoria)
- Excluir tarefas
- Filtrar tarefas por status ou data
- Ordenar tarefas por data

### Funcionalidades Extras
- **Categorias**: Cada tarefa pode ser associada a uma categoria.
- **Documentação Interativa da API**: Utilizando Swagger.

---

## Tecnologias Utilizadas

- **Frontend**: Vue.js 3, Quasar Framework
- **Backend**: Laravel 9.x, PHP 8.1
- **Banco de Dados**: PostgreSQL
- **Documentação**: Swagger (via L5-Swagger)

---

## Pré-requisitos

Certifique-se de que seu ambiente atende aos seguintes requisitos:
- PHP >= 8.1
- Composer
- Node.js >= 16.0
- NPM ou Yarn
- PostgreSQL

---

## Configuração do Ambiente

### 1. Clone o Repositório
```bash
git clone https://github.com/Pedro-Balestra/desafio-softpar.git
cd desafio-softpar
```
### 2. Backend
#### 2.1 Instale as dependências:
```bash
cd todo-api
composer install
```
#### 2.2 Configure o arquivo .env:
- Copie o arquivo exemplo:
```bash
cp .env.example .env
```
- Configure as variáveis de ambiente:
```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=todo_app
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```
#### 2.3 Execute as migrations:
```bash
php artisan migrate
```
#### 2.4 Popule o banco com dados fictícios (Opcional):
```bash
php artisan db:seed
```
#### 2.5 Inicie o servidor:
```bash
php artisan serve
```
---
### 3. Frontend
#### 3.1 Instale as dependências:
```bash
cd ../todo-frontend
npm install
```
#### 3.2 Inicie o servidor:
```bash
quasar dev
```
---
### 4. Endpoints da API
- Necessário executar o passo 2.5 para utilizar a [Documentação Interativa (Swagger)](http://127.0.0.1:8000/api/documentation) da API.

| Método | Endpoint             | Descrição                      | Exemplo de Resposta                                                                                  |
|--------|----------------------|--------------------------------|-----------------------------------------------------------------------------------------------------|
| GET    | /api/tarefas         | Retorna todas as tarefas       | `[{"id":1,"titulo":"Minha Tarefa","status":"Pendente","created_at":"2025-01-07T12:34:56Z"}]`        |
| POST   | /api/tarefas         | Cria uma nova tarefa           | `{"id":2,"titulo":"Nova Tarefa","status":"Em andamento","created_at":"2025-01-07T12:35:56Z"}`       |
| GET    | /api/tarefas/{id}    | Retorna os detalhes de uma tarefa | `{"id":1,"titulo":"Minha Tarefa","descricao":"Detalhes","status":"Pendente","categoria":"Casa"}`   |
| PUT    | /api/tarefas/{id}    | Atualiza uma tarefa            | `{"id":1,"titulo":"Tarefa Atualizada","status":"Concluído","updated_at":"2025-01-08T10:20:30Z"}`    |
| DELETE | /api/tarefas/{id}    | Deleta uma tarefa              | `204 No Content`                                                                                   |


Exemplo de requisição para criar uma nova tarefa via terminal:
```bash
curl -X POST http://127.0.0.1:8000/api/tarefas \
-H "Content-Type: application/json" \
-d '{"titulo": "Minha Tarefa", "descricao": "Detalhes da tarefa", "categoria": "Casa", "status": "Pendente"}'
```
---
### 5. Testando a Aplicação
#### 5.1 Acesse o frontend em:
```bash
http://localhost:9000
```
#### 5.2 Use o Swagger para testar os endpoints da API:
```bash
http://127.0.0.1:8000/api/documentation
```
---
### 6. Funcionalidades Extras e Como Testá-las
#### 6.1 Categorias:
- Adicione ou altere categorias no momento de criar ou editar tarefas. Exemplos:
  - Casa, Estudos, Lazer, Mercado, Contas, Outros.

#### 6.2 Documentação Interativa:
- O Swagger oferece uma interface amigável para explorar os endpoints. Para acessá-lo, inicie o backend e abra: [Swagger](http://127.0.0.1:8000/api/documentation)

#### 6.3 Filtrar e Ordenar Tarefas:
- **Filtrar por Status:** Escolha entre "Todas", "Em andamento", "Concluído" ou "Pendente".
- **Ordenar por Data:** Selecione "Mais recente" ou "Mais antiga".

---
### 7. Organização do Código

#### 7.1 Backend
- **app/Models**: Modelos do Eloquent usados para manipular o banco de dados.
- **app/Http/Controllers**: Controladores responsáveis pelos endpoints da API.
- **routes/api.php**: Arquivo onde as rotas da API estão definidas.
- **database/migrations**: Arquivos de migração para criar ou alterar as tabelas do banco de dados.

#### 7.2 Frontend
- **src/pages**: Contém as páginas principais da aplicação, como a lista de tarefas.
- **src/components**: Componentes reutilizáveis, como formulários e listas.
- **src/services**: Serviço de comunicação com a API, utilizando Axios para enviar requisições HTTP.

---
### 8. Melhorias Futuras
- **Autenticação de Usuários**: Adicionar autenticação JWT para proteger os endpoints e associar tarefas a usuários específicos.
- **Notificações por E-mail**: Implementar um sistema para enviar lembretes automáticos para tarefas pendentes.
- **Exportação de Dados**: Adicionar a funcionalidade de exportar tarefas em formatos como CSV ou Excel.
- **Relatórios**: Criar gráficos e tabelas analíticas para visualizar o progresso das tarefas.
- **Interface Responsiva**: Melhorar a experiência do usuário em dispositivos móveis, adaptando o layout.


## 👥 Autor

<div align="center">
    <img src="https://avatars.githubusercontent.com/pedro-balestra" width="150px" alt="Pedro Balestra">
    <h3>Pedro Balestra</h3>
    <p>
        Desenvolvedor apaixonado por tecnologia e desafios.<br>
        Entre em contato:
    </p>
    <a href="https://www.linkedin.com/in/pedro-balestra/">
        <img src="https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white" alt="LinkedIn">
    </a>
    <a href="mailto:pedro.balestra@outlook.com">
        <img src="https://img.shields.io/badge/Outlook-0078D4?style=for-the-badge&logo=microsoft-outlook&logoColor=white" alt="Outlook">
    </a>
</div>

---
## 📝 Licença
[![License](https://img.shields.io/github/license/Pedro-Balestra/desafio-softpar)](https://opensource.org/licenses/MIT)

- **[MIT license](https://opensource.org/licenses/MIT)**