<?php
include 'includes/funcoes.php';

$titulo = 'Álbum<br>Ghibli';
$subtitulo = 'Um caderno de viagem pelos filmes, personagens e lugares do Studio Ghibli.';
$pagina_atual = 'index';

$filmes = pegarLista('films');

// Filme em destaque: um por dia (ou sorteado ao clicar no botão)
$destaque = null;
if ($filmes != null) {
    $com_imagem = [];
    foreach ($filmes as $filme) {
        if (imagemDoFilme($filme) != '') {
            $com_imagem[] = $filme;
        }
    }
    if (count($com_imagem) > 0) {
        if (isset($_GET['sorteio'])) {
            $posicao = array_rand($com_imagem);
        } else {
            $posicao = crc32(date('Y-m-d')) % count($com_imagem);
        }
        $destaque = $com_imagem[$posicao];
    }
}

// Os quatro cartões de entrada. O campo "filme" é o filme cuja cena aparece na foto.
$portais = [
    ['link' => 'filmes.php', 'icone' => '☁', 'nome' => 'Filmes', 'texto' => 'Os longas, com sinopse, diretor e nota.', 'filme' => 'My Neighbor Totoro'],
    ['link' => 'personagens.php', 'icone' => '❀', 'nome' => 'Personagens', 'texto' => 'Quem aparece em cada história.', 'filme' => 'Spirited Away'],
    ['link' => 'locais.php', 'icone' => '⛰', 'nome' => 'Lugares', 'texto' => 'Clima e terreno de cada cenário.', 'filme' => "Howl's Moving Castle"],
    ['link' => 'veiculos.php', 'icone' => '✈', 'nome' => 'Máquinas', 'texto' => 'Aviões e engenhocas voadoras.', 'filme' => 'Castle in the Sky'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Início - Álbum Ghibli</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@700&family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- CSS: o geral (base) e o desta página -->
  <link rel="stylesheet" href="css/base.css?v=1">
  <link rel="stylesheet" href="css/index.css?v=1">
</head>
<body>

<?php include 'includes/cabecalho.php'; ?>

<?php if ($destaque != null) { ?>
<section class="destaque">
  <?= mostrarImagem([pegar($destaque, 'movie_banner'), pegar($destaque, 'image')], '') ?>
  <div class="velo"></div>
  <div class="texto-destaque">
    <p class="rotulo"><?php if (isset($_GET['sorteio'])) echo 'Sorteado para você'; else echo 'Filme do dia'; ?></p>
    <h2><?= proteger(tituloEmPortugues($destaque)) ?></h2>
    <p class="meta"><?= proteger($destaque['release_date']) ?> · <?= proteger($destaque['director']) ?> · Nota <?= proteger($destaque['rt_score']) ?></p>
    <p><?= proteger(mb_strimwidth(traduzirTexto($destaque['description']), 0, 260, '…')) ?></p>
    <p class="acoes">
      <a class="botao" href="filme.php?id=<?= proteger($destaque['id']) ?>">Ver o filme</a>
      <a class="botao claro" href="?sorteio=1">Sortear outro</a>
    </p>
  </div>
</section>
<?php } ?>

<section class="portais">
  <?php foreach ($portais as $numero => $portal) { ?>
    <?php $filme_da_foto = acharFilmePorTitulo($portal['filme'], $filmes); ?>
    <a class="portal" href="<?= $portal['link'] ?>" style="--i:<?= $numero ?>">
      <div class="foto">
        <span class="runa" aria-hidden="true"><?= $portal['icone'] ?></span>
        <?php
          if ($filme_da_foto != null) {
              echo mostrarImagem([pegar($filme_da_foto, 'movie_banner'), pegar($filme_da_foto, 'image')], '');
          }
        ?>
      </div>
      <h2><?= $portal['nome'] ?></h2>
      <p><?= $portal['texto'] ?></p>
    </a>
  <?php } ?>
</section>

<?php include 'includes/rodape.php'; ?>
