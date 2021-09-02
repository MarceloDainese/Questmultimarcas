Configuração do Sistema Quest Multimarcas

Crie uma base de dados com o nome questmultimarcas no MySql

Entre no diretório raiz do projeto e faça os seguintes comandos no Prompt de comando:
composer install
php artisan key:generate

Rode as Migrations e a Seed para gerar o primeiro usuário com email: admin@admin.com
senha: admin
Para executar as migrations :
php artisan migrate

Para executar o seed e criar o usuário admin@admin.com :
php artisan db:seed

Sistema desenvolvido com Laravel 8
Bootstrap 5.1
MySQL 5.7.31 e REGEX
