<?php
include 'includes/funcoes.php';

// Pega o id do filme e a pesquisa de onde o usuário veio (pela URL)
$id = '';
if (isset($_GET['id'])) {
    $id = trim($_GET['id']);
}
$busca_anterior = '';
if (isset($_GET['q'])) {
    $busca_anterior = trim($_GET['q']);
}
$link_voltar = 'filmes.php?q=' . urlencode($busca_anterior);

// Busca o filme na API
$filmes = pegarLista('films');
$filme = acharFilmePorId($id, $filmes);

$erro = '';
if ($filmes == null) {
    $erro = ERRO_API;
} elseif ($filme == null) {
    $erro = 'Este filme não foi encontrado no álbum.';
}

// Quem aparece neste filme: procuramos em personagens, lugares e máquinas
$aparecem = [];
if ($filme != null) {
    $tipos = ['people' => 'Personagens', 'locations' => 'Lugares', 'vehicles' => 'Máquinas'];
    foreach ($tipos as $recurso => $rotulo) {
        $itens = pegarLista($recurso);
        $nomes = [];
        if ($itens != null) {
            foreach ($itens as $item) {
                foreach ($item['films'] as $link) {
                    if (idDoLink($link) == $id) {
                        $nomes[] = $item['name'];
                        break;
                    }
                }
            }
        }
        if (count($nomes) > 0) {
            $aparecem[$rotulo] = $nomes;
        }
    }
}

$pagina_atual = 'filmes';
if ($filme != null) {
    $titulo = proteger(tituloEmPortugues($filme));
    $subtitulo = $filme['title'] . ' · ' . pegar($filme, 'original_title_romanised');
} else {
    $titulo = 'Filmes';
    $subtitulo = '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= strip_tags($titulo) ?> - Álbum Ghibli</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@700&family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- CSS: o geral (base) e o desta página -->
  <link rel="stylesheet" href="css/base.css?v=1">
  <link rel="stylesheet" href="css/filme.css?v=1">
</head>
<body>

<?php include 'includes/cabecalho.php'; ?>

<article class="folha">
  <a class="voltar" href="<?= proteger($link_voltar) ?>">← Voltar aos resultados</a>

<?php if ($erro != '') { ?>
  <p class="aviso erro"><?= proteger($erro) ?></p>

<?php } else { ?>
  <?= mostrarImagem([pegar($filme, 'movie_banner'), pegar($filme, 'image')], 'Cena de ' . tituloEmPortugues($filme), 'banner') ?>

  <div class="ficha-filme">
    <div class="arco poster">
      <span class="runa" aria-hidden="true">☁</span>
      <?= mostrarImagem([pegar($filme, 'image'), pegar($filme, 'movie_banner')], 'Pôster de ' . tituloEmPortugues($filme)) ?>
    </div>
    <dl>
      <dt>Título original</dt><dd><?= proteger(valor(pegar($filme, 'original_title'))) ?></dd>
      <dt>Diretor</dt><dd><?= proteger(valor(pegar($filme, 'director'))) ?></dd>
      <dt>Produtor</dt><dd><?= proteger(valor(pegar($filme, 'producer'))) ?></dd>
      <dt>Ano</dt><dd><?= proteger(valor(pegar($filme, 'release_date'))) ?></dd>
      <dt>Duração</dt><dd><?= proteger(valor(pegar($filme, 'running_time'))) ?> minutos</dd>
      <dt>Nota (Rotten Tomatoes)</dt><dd><?= proteger(valor(pegar($filme, 'rt_score'))) ?>/100</dd>
    </dl>
  </div>

  <section class="verbete">
    <h3>Sinopse</h3>
    <p class="abertura"><?= proteger(traduzirTexto(pegar($filme, 'description'))) ?></p>
    <p class="nota-trad">Tradução automática.</p>
  </section>

  <?php foreach ($aparecem as $rotulo => $nomes) { ?>
    <section class="verbete">
      <h3><?= proteger($rotulo) ?></h3>
      <ul class="chips">
        <?php foreach ($nomes as $nome) { ?>
          <li><?= proteger($nome) ?></li>
        <?php } ?>
      </ul>
    </section>
  <?php } ?>
<?php } ?>
</article>

<?php include 'includes/rodape.php'; ?>
