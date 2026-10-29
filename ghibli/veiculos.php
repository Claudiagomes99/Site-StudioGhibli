<?php
include 'includes/funcoes.php';

// Dados desta página
$titulo = 'Máquinas';
$subtitulo = 'Aviões, barcos e engenhocas voadoras.';
$pagina_atual = 'veiculos';

// Configuração da listagem (usada em includes/lista.php)
$recurso = 'vehicles';
$campo = 'name';
$icone = '✈';
$placeholder = 'nome da máquina…';
$mensagem_vazia = 'A oficina está vazia. Escreva um nome ou clique em “ver todos”.';
$sugestoes = [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Máquinas - Álbum Ghibli</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@700&family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- CSS: o geral (base), o dos cards e o desta página. Mudou o CSS? Aumente o número do ?v= -->
  <link rel="stylesheet" href="css/base.css?v=1">
  <link rel="stylesheet" href="css/cards.css?v=1">
  <link rel="stylesheet" href="css/veiculos.css?v=1">
</head>
<body>

<?php include 'includes/cabecalho.php'; ?>
<?php include 'includes/lista.php'; ?>
<?php include 'includes/rodape.php'; ?>
