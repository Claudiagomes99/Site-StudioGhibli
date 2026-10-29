<?php
include 'includes/funcoes.php';

$titulo = 'Linha do<br>tempo';
$subtitulo = 'Quatro décadas de filmes, em ordem de lançamento.';
$pagina_atual = 'tempo';

// Busca os filmes e ordena do mais antigo para o mais novo
$filmes = pegarLista('films');
if ($filmes != null) {
    usort($filmes, 'compararAno');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Linha do tempo - Álbum Ghibli</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@700&family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- CSS: o geral (base) e o desta página -->
  <link rel="stylesheet" href="css/base.css?v=1">
  <link rel="stylesheet" href="css/tempo.css?v=1">
</head>
<body>

<?php include 'includes/cabecalho.php'; ?>

<?php if ($filmes == null) { ?>
  <p class="aviso erro"><?= ERRO_API ?></p>

<?php } else { ?>
<ol class="tempo">
  <?php foreach ($filmes as $numero => $filme) { ?>
    <?php $nota = (int)pegar($filme, 'rt_score'); ?>
    <li class="marco" style="--i:<?= $numero ?>">
      <span class="ano"><?= proteger($filme['release_date']) ?></span>
      <a class="cartao-tempo" href="filme.php?id=<?= proteger($filme['id']) ?>">
        <div class="mini arco">
          <span class="runa" aria-hidden="true">☁</span>
          <?= mostrarImagem([pegar($filme, 'image'), pegar($filme, 'movie_banner')], '') ?>
        </div>
        <div>
          <h3><?= proteger(tituloEmPortugues($filme)) ?></h3>
          <p class="diretor"><?= proteger($filme['director']) ?> · <?= proteger($filme['running_time']) ?> min</p>
          <div class="barra" style="--n:<?= $nota ?>%"><span></span></div>
          <p class="nota">Nota <?= $nota ?>/100</p>
        </div>
      </a>
    </li>
  <?php } ?>
</ol>
<?php } ?>

<?php include 'includes/rodape.php'; ?>
