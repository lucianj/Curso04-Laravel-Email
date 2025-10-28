# Curso 04 - Laravel - Gerenciador de séries de Tv

Sistema de gerenciamento de séries de TV do curso de formação Laravel da Alura.

## Requisitos

- PHP >= 8.1  
- Composer 2.8.1
- SQLite  
- Node.js 16

## Instalação

#### 1. Clone o repositório:

   ```bash
   git clone https://github.com/lucianj/Curso04-Laravel-Email.git
   cd Curso04-Laravel-Email
   ```

#### 2. Instale as dependências do PHP:

    ```bash
    composer install
    ```

#### 3. Copie o arquivo de ambiente e configure as variáveis:

    ```bash
    cp .env.example .env
    ```

#### 5. Crie o arquivo database.sqlite no diretório database

#### 6. Rode as migrations para criar as tabelas do banco:

    ```bash
    php artisan migrate
    ```

#### 7. Instale demais dependências (se houver):

    ```bash
    npm install
    npm run dev
    ```

## Rodando o projeto

    php artisan serve

