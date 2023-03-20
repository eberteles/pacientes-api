# API - Pacientes

## Funcionalidades:

- CRUD de Pacientes.
- Importação de Pacientes.
- Consulta CEP.

## Configuração Docker:

1. Clone o projeto
2. docker-compose up --build -d
3. docker-compose exec app bash

## Criação do Banco:
1. docker compose exec db bash
2. psql -U postgres
3. createdb -U postgres db_pacientes;
4. \q
5. exit

## Configuração da Aplicação:
1. docker-compose exec app bash
2. composer install
3. php artisan key:generate
4. php artisan migrate
5. php artisan db:seed

## Cobertura dos Testes:
php artisan test --coverage
