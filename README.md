<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## Sobre o projeto

Pequeno projeto desenvolvido a pedido de uma empresa para avaliação.
Consiste em uma API para cadastro de filmes e tags associadas a eles (n:n)

O projeto inclui um container docker pré-configurado.
Execute o comando `docker-compose up -d` na raiz do projeto e aguarde os containers subirem.

_Caso aconteça algum problema de conexão mysql, execute o comando novamente e certifique-se de que o container do mysql esteja rodando_

## Para rodar
- Rode o composer
- Acesse o container do php-fpm, execute o comando `docker-compose exec php-fpm bash`
  - Rode as migrations (e as seeds caso julgue necessário) 

A pasta **resources** possui um arquivo com collections do postman
