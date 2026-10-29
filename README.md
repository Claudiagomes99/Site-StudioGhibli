# Álbum Ghibli

Site feito em PHP que consulta uma API pública e mostra filmes, personagens, lugares e máquinas do Studio Ghibli.

Trabalho da disciplina de desenvolvimento web (PHP + API pública).

## O que o site faz

1. O usuário digita um nome no campo de pesquisa e clica em **Consultar**.
2. O PHP faz uma requisição `GET` para a API.
3. A API devolve os dados em **JSON**.
4. O PHP lê o JSON e mostra o resultado na tela, em cards.

## API usada

[Studio Ghibli API](https://ghibliapi.vercel.app) (pública, não precisa de chave).

Exemplo de requisição: `https://ghibliapi.vercel.app/films`

Como a API só tem textos em inglês, as sinopses são traduzidas com a API pública [MyMemory](https://mymemory.translated.net).

## Páginas

- **Início:** filme do dia e atalhos para as outras páginas
- **Filmes:** pesquisa de filmes e página com os detalhes de cada um
- **Personagens**, **Lugares** e **Máquinas:** pesquisa nos dados da API
- **Linha do tempo:** filmes em ordem de lançamento, com a nota de cada um
- **Quiz:** adivinhe o filme por um pedaço da cena

## Tecnologias

- PHP
- HTML
- CSS
- API em JSON

## Como rodar

1. Instale o [XAMPP](https://www.apachefriends.org) e inicie o **Apache**.
2. Copie a pasta do projeto para `C:\xampp\htdocs\`.
3. Abra no navegador: `http://localhost/ghibli/`



## Observações

- Os filmes e as imagens pertencem ao Studio Ghibli. Projeto escolar, sem fins comerciais.
- Personagens, lugares e máquinas não têm imagem na API, então aparece uma cena do filme em que eles aparecem.

## Imagens e video:
https://youtu.be/1_LEudkIkz4


