<?php
include 'includes/funcoes.php';

// Dados desta página
$titulo = 'Filmes';
$subtitulo = 'Os longas do Studio Ghibli, um por página do álbum.';
$pagina_atual = 'filmes';

// Configuração da listagem (usada em includes/lista.php)
$recurso = 'films';
$campo = 'title';
$icone = '☁';
$placeholder = 'totoro, spirited, castle…';
$mensagem_vazia = 'O álbum está fechado. Escreva o nome de um filme ou clique em “ver todos”.';
$sugestoes = ['Totoro', 'Spirited', 'Castle', 'Kiki', 'Ponyo', 'Mononoke'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Filmes - Álbum Ghibli</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@700&family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- CSS: o geral (base), o dos cards e o desta página. Mudou o CSS? Aumente o número do ?v= -->
  <link rel="stylesheet" href="css/base.css?v=1">
  <link rel="stylesheet" href="css/cards.css?v=1">
  <link rel="stylesheet" href="css/filmes.css?v=1">
</head>
<body>

<?php include 'includes/cabecalho.php'; ?>
<?php include 'includes/lista.php'; ?>
<?php include 'includes/rodape.php'; ?>
