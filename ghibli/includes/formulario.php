<?php
// Formulário de pesquisa. Usa as variáveis: $termo, $placeholder e $sugestoes
?>
<form class="inscricao" method="get">
  <label for="q">O que você quer encontrar?</label>
  <div class="linha">
    <input id="q" name="q" type="text" value="<?= proteger($termo) ?>" placeholder="<?= proteger($placeholder) ?>" autocomplete="off">
    <button type="submit">Consultar</button>
  </div>
  <p class="sugestoes">
    <?php if (count($sugestoes) > 0) { ?>
      Experimente:
      <?php foreach ($sugestoes as $sugestao) { ?>
        <a href="?q=<?= urlencode($sugestao) ?>"><?= proteger($sugestao) ?></a>
      <?php } ?>
      ou
    <?php } ?>
    <a href="?q=">ver todos</a>
  </p>
</form>
