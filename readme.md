## Api Livros

### Documentação do Laravel 5.3

Era usado o laravel 5.1 por ser LTS, mas acabou que não deu.
Foi decidido usar a 5.3 por causa do suporte mais simples para multi-auth. Documentação lá no [site do laravel](http://laravel.com/docs/5.3).
O pacote que criou o multi-auth foi [este](https://github.com/Hesto/multi-auth), que já pode ser removido do composer.json, mas continua instalado para referência futura e possíveis alterações das regras de negócio.
Laravel 5.4 lançou/vai lançar em jan/2017, vou ficar de olho para fazer uma atualização se necessário.

### Uso da api

Primeiramente, para o login via Oauth, é necessário que tanto a Api quando o app android (ou quaisquer outros clitentes que venham a usar a api) possuam o mesmo *app_id/secret* tanto na google quanto no facebook, etc. O termo usado nesta api para *google, facebook, ou outro app de terceiros* será **driver**. Aqui, um passo a passo de como acontecerá a autenticação de um usuário:

 - App *cliente* usa oauth e pega token de acesso com *driver*
 - App *cliente* envia um request POST para *api* com esta token
 - Api gerará uma token própria e retornará a mesma em json para *cliente*
 - Cliente deve guardar esta token por 24h (que será o tempo de expiração da token)
 - Os requests seguintes em que são necessários autenticação de usuário devem ter um header `"Authorization: <token>"` além do header `"Accepts: application/json"`, por exemplo.

#### Tabela dos requests

**Obs.:** *Se não constar nada na coluna header, enviar somente `Accept: application/json`. Nas outras, enviar o header de json em conjunto com o que for pedido.*

| URL           | Header                |   Parâmetros      | Resposta              |
|:-------------:|:---------------------:|:-----------------:|:---------------------:|
| /api/         | -                     | -                 | "Hello world"         |
| /api/login    | -                     | driver, token     | `{"token": "aaaaa"}`    |
| /api/profile  | Authorization: `token`| -                 | objeto json com informações sobre o usuário    |
| /api/book  | Authorization: `token`| -                 | objeto json com uma lista paginada de todos os livros    |
| /api/book?q=query-de-busca  | Authorization: `token`| -                 | objeto json com uma lista paginada de livros com a query de busca no título, nome do autor ou em sua descrição.    |

### Sobre usar o Open Library para adicionar livros

Para isto, está sendo usado o mecanismo de *queued jobs* do laravel. Recomendo instalar o [supervisor](https://laravel.com/docs/5.3/queues#supervisor-configuration) no servidor para não ter problemas.

### Observações

Pretendo documentar o software neste documento, de acordo com o que for acontecendo.
