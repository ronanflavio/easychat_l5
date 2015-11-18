# Easychat for Laravel 5.x

## Descrição

Chat baseado em jQuery para Laravel 5.x utilizando armazenamento em banco de dados MySQL e integração com a tabela de usuários do seu projeto.

## Instalação

## Laravel 4.x

Veja a instalação [clicando aqui] (https://github.com/kikonuy/easychat).

## Laravel 5.x

Para instalar o Easychat, você deve entrar com o seguinte comando via composer:

`composer require ronanflavio/easychatl5`

Em seguida, você deve adicionar a linha abaixo no fim da lista de `providers`, do arquivo `config/app.php` do seu projeto:

```
'providers' => [
  ...
  Ronanflavio\Easychat\EasychatServiceProvider::class,
];
```

É necessário publicar os assets, migrations e configurações do package em seu projeto, para isso execute os comandos abaixo:

`php artisan vendor:publish`

Dessa forma os arquivos de configuração ficarão em `config/packages/Ronanflavio/Easychat`.

Os arquivos de migrations estarão no basepath da aplicação, em `migrations/Ronanflavio/Easychat`.

Os assets estarão no diretório público, em `public/packages/Ronanflavio/Easychat`.

Existem tabelas que são necessárias para o funcionamento do chat, elas estão nomeadas com o prefixo `ec_`, com o intuito de diferenciá-las das tabelas do seu projeto. As migrations dessas tabelas estão dentro do package, para executá-las, utilize o comando abaixo:

`php artisan migrate --path=migrations/Ronanflavio/Easychat`

Por fim, deve ser adicionada a exceção do CSRF Token para a URI do easychat no arquivo `app\Http\Middleware\VerifyCsrfToken.php`, para isso insira a seguinte linha no array:

```
	protected $except = [
		 'easychat/*'
	];
```

## Configuração

Quando fizer a publicação do package, os arquivos de configuração estarão dentro do diretório:

`config\packages\Ronanflavio\Easychat`

É necessário informar qual o nome da tabela e da model, além dos principais campos: 'id' e 'name' respectivos à tabela de usuários dentro do arquivo `tables.php`. Veja abaixo o exemplo:

```
'users' => array(

        /**
         * Set the Model name:
         */

        'model' => 'App\User',

        /**
         * Set the Table name:
         */

        'table' => 'usuarios',

        /**
         * Set the Fields names:
         */

        'id'         => 'id',
        'name'       => 'nome',
        'photo'      => null,
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ),
```
