<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb combination of simplicity, elegance, and innovation give you tools you need to build any application with which you are tasked.

## Learning Laravel

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](http://patreon.com/taylorotwell):

- **[Vehikl](http://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Styde](https://styde.net)**
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).

## Versões

Composer version 2.2.25
PHP version 7.0.0
Laravel Framework 5.4.36

## Instalação

Clone o repositório:

```bash
git clone https://github.com/joel-estumano/saude-api.git
```

Navegue para o Diretório do Projeto:

```bash
cd saude-api
```

Instale as Dependências:

```bash
composer install
```

### Base de dados:

Você deve ter instalado o servidor SQL na versão 5.7.11
(mysql-installer-community-5.7.11.0.msi) -> https://downloads.mysql.com/archives/installer/

O seu banco de dados deve ter um eschema com esse nome: saude-db

### Configuração do Ambiente
Copie o arquivo `env.example` para criar o arquivo `.env` na raiz do projeto:

### Migrações e População do Banco de Dados

Execute as Migrações:

```bash
php artisan migrate
```

Popule o banco de dados com dados iniciais:

```bash
php artisan db:seed
```
Você terá então um usuário padrão: 
email: admin@mail.com
password: admin123

Utilize-o para fazer login no sistema cliente.

### Geração de Chaves e Configurações

Gere a chave de criptografia:

```bash
php artisan key:generate
```
Publique os recursos do sistema:

```bash
php artisan vendor:publish
```

Configure os segredos de autenticação via OAuth:

```bash
php artisan passport:install
```
Você terá então algo assim:

Encryption keys generated successfully. \
Personal access client created successfully. \
Client ID: 1 \
Client Secret: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx \
Password grant client created successfully. \
Client ID: 2 \
Client Secret: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx \

Guarde essas informações, pois elas serão necessárias para autorizar o sistema cliente.


### Execução do Projeto

Inicie o servidor de desenvolvimento
```bash
php artisan serve
```
Acesse a aplicação no navegador usando o endereço: http://127.0.0.1:8000


## Docker

Clone o repositório:

```bash
git clone https://github.com/joel-estumano/saude-api.git
```

Navegue para o Diretório do Projeto:

```bash
cd saude-api
```

Instale as Dependências:

```bash
composer install
```

Copie o arquivo env.example para criar o arquivo .env na raiz do projeto:

Execute:
```bash
docker-compose up --build
```

Acompanhe os logs para obter as informações dos segredos de autenticação via OAuth.

Em seguida pode ocorrer:

saude_api  |                                                                                                                                                  
saude_api  |
saude_api  |   [Illuminate\Database\QueryException]                                         
saude_api  |   SQLSTATE[HY000] [2002] Connection refused (SQL: select * from information_s                                                                    
saude_api  |   chema.tables where table_schema = saude-db and table_name = migrations)                                                                        
saude_api  |                                                                                                                                                  
saude_api  |                                                                                                                                                  
saude_api  |                                                                                                                                                  
saude_api  |   [PDOException]                             
saude_api  |   SQLSTATE[HY000] [2002] Connection refused                                                                                                      
saude_api  |                                                                                                                                                  
saude_api  | 
saude_api exited with code 1

Então tente executar: 
```bash
docker-compose up
```

E acompanhe os logs para obter as informações dos segredos de autenticação via OAuth.

Algo como:

... \
Migration table created successfully. \
Migrating: 2014_10_12_000000_create_users_table \
Migrated:  2014_10_12_000000_create_users_table \
Migrating: 2014_10_12_100000_create_password_resets_table \
Migrated:  2014_10_12_100000_create_password_resets_table \
Migrating: 2016_06_01_000001_create_oauth_auth_codes_table \
Migrated:  2016_06_01_000001_create_oauth_auth_codes_table \
Migrating: 2016_06_01_000002_create_oauth_access_tokens_table \
Migrated:  2016_06_01_000002_create_oauth_access_tokens_table \
Migrating: 2016_06_01_000003_create_oauth_refresh_tokens_table \
Migrated:  2016_06_01_000003_create_oauth_refresh_tokens_table \
Migrating: 2016_06_01_000004_create_oauth_clients_table \
Migrated:  2016_06_01_000004_create_oauth_clients_table \
Migrating: 2016_06_01_000005_create_oauth_personal_access_clients_table \
Migrated:  2016_06_01_000005_create_oauth_personal_access_clients_table \
Migrating: 2025_03_26_170129_create_table_regionais \
Migrated:  2025_03_26_170129_create_table_regionais \
Migrating: 2025_03_26_170525_create_table_entidades \
Migrated:  2025_03_26_170525_create_table_entidades \
Migrating: 2025_03_26_170803_create_table_especialidades \
Migrated:  2025_03_26_170803_create_table_especialidades \
Migrating: 2025_03_26_170917_create_table_entidade_especialidades \
Migrated:  2025_03_26_170917_create_table_entidade_especialidades \
Migrating: 2025_03_28_013918_alter_table_add_especialidades_to_entidades \
Migrated:  2025_03_28_013918_alter_table_add_especialidades_to_entidades \
Seeding: UserSeeder \
Seeding: RegionaisSeeder \
Seeding: EspecialidadesSeeder \
Passport ainda não configurado. Criando chaves...
Encryption keys generated successfully. \
Personal access client created successfully. \
Client ID: 1 \
Client Secret: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx \
Password grant client created successfully. \
Client ID: 2 \
Client Secret: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  \
...

Acesse a aplicação no navegador usando o endereço: http://127.0.0.1:8000
