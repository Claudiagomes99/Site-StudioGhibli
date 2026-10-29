<?php
// Parte comum das páginas de listagem (filmes, personagens, lugares e máquinas).
// A página que inclui este arquivo define antes:
// $recurso, $campo, $icone, $placeholder, $mensagem_vazia e $sugestoes

// 1) O usuário pesquisou?
$termo = '';
$pesquisou = false;
if (isset($_GET['q'])) {
    $pesquisou = true;
    $termo = trim($_GET['q']);
}

// 2) Busca os dados na API
$lista = pegarLista($recurso);
if ($recurso == 'films') {
    $todos_filmes = $lista;
} else {
    $todos_filmes = pegarLista('films'); // precisamos dos filmes para mostrar "Aparece em"
}

// 3) Sugestões: nas outras páginas pegamos nomes que existem de verdade na API
if ($recurso != 'films' && is_array($lista)) {
    $sugestoes = [];
    $passo = intval(count($lista) / 6);
    if ($passo < 1) {
        $passo = 1;
    }
    for ($i = 0; $i < count($lista) && count($sugestoes) < 6; $i = $i + $passo) {
        $sugestoes[] = $lista[$i]['name'];
    }
}

// 4) Filtra pelo que foi digitado (máximo de 24 cards)
$resultados = [];
$total = 0;
if ($pesquisou && is_array($lista)) {
    $resultados = filtrarLista($lista, $termo, $campo);
    $total = count($resultados);
    $resultados = array_slice($resultados, 0, 24);
}

include 'includes/formulario.php';
?>

<section class="paginas" aria-live="polite">
<?php if (!$pesquisou) { ?>
  <p class="aviso"><?= proteger($mensagem_vazia) ?></p>

<?php } elseif ($lista == null) { ?>
  <p class="aviso erro"><?= ERRO_API ?></p>

<?php } elseif ($total == 0) { ?>
  <p class="aviso">Nada encontrado para “<?= proteger($termo) ?>”. Escolha um dos nomes sugeridos acima ou clique em “ver todos”.</p>

<?php } else { ?>
  <p class="contagem"><?= $total ?> resultado(s)<?php if ($total > 24) echo ' (mostrando os 24 primeiros)'; ?></p>

  <div class="grade">
  <?php foreach ($resultados as $numero => $item) { ?>
    <?php $card = montarCard($recurso, $item, $termo, $todos_filmes); ?>

    <article class="pagina" style="--i:<?= $numero ?>">
      <div class="moldura">
        <div class="arco <?php if ($card['alto']) echo 'alto'; ?>">
          <span class="runa" aria-hidden="true"><?= $icone ?></span>
          <?php
            $texto_alt = $card['legenda'];
            if ($texto_alt == '') {
                $texto_alt = $card['nome'];
            }
            echo mostrarImagem($card['imagens'], $texto_alt);
          ?>
        </div>
        <?php if ($card['selo'] != '') { ?>
          <span class="selo"><?= proteger($card['selo']) ?></span>
        <?php } ?>
      </div>

      <?php if ($card['legenda'] != '') { ?>
        <p class="legenda"><?= proteger($card['legenda']) ?></p>
      <?php } ?>

      <h2>
        <?php if ($card['link'] != '') { ?>
          <a href="<?= proteger($card['link']) ?>"><?= proteger($card['nome']) ?></a>
        <?php } else { ?>
          <?= proteger($card['nome']) ?>
        <?php } ?>
      </h2>

      <?php if ($card['sub'] != '') { ?>
        <p class="encanto"><?= proteger($card['sub']) ?></p>
      <?php } ?>

      <?php if ($card['texto'] != '') { ?>
        <p class="texto"><?= proteger(mb_strimwidth($card['texto'], 0, 240, '…')) ?></p>
      <?php } ?>

      <dl>
        <?php foreach ($card['linhas'] as $rotulo => $dado) { ?>
          <dt><?= proteger($rotulo) ?></dt>
          <dd><?= proteger($dado) ?></dd>
        <?php } ?>
      </dl>

      <?php if ($card['link'] != '') { ?>
        <p class="mais"><a class="ler" href="<?= proteger($card['link']) ?>">Abrir a página</a></p>
      <?php } ?>
    </article>
  <?php } ?>
  </div>
<?php } ?>
</section>
