<?php
// Menu e capa (título da página). Cada página define antes:
// $titulo, $subtitulo e $pagina_atual
?>
<!-- Desenho do bichinho de fuligem, usado em vários lugares -->
<svg width="0" height="0" style="position:absolute" aria-hidden="true">
  <symbol id="fuligem" viewBox="0 0 40 40">
    <path d="M20 4v6M20 30v6M4 20h6M30 20h6M9 9l4 4M27 27l4 4M31 9l-4 4M13 27l-4 4" stroke="#2b2320" stroke-width="3" stroke-linecap="round"/>
    <circle cx="20" cy="20" r="12" fill="#2b2320"/>
    <circle cx="16" cy="18" r="4" fill="#fff"/><circle cx="25" cy="18" r="4" fill="#fff"/>
    <circle cx="16.5" cy="18.5" r="1.8" fill="#2b2320"/><circle cx="25.5" cy="18.5" r="1.8" fill="#2b2320"/>
  </symbol>
</svg>

<nav class="indice" aria-label="Seções do álbum">
  <a href="index.php" <?php if ($pagina_atual == 'index') echo 'aria-current="page"'; ?>>Início</a>
  <a href="filmes.php" <?php if ($pagina_atual == 'filmes') echo 'aria-current="page"'; ?>>Filmes</a>
  <a href="personagens.php" <?php if ($pagina_atual == 'personagens') echo 'aria-current="page"'; ?>>Personagens</a>
  <a href="locais.php" <?php if ($pagina_atual == 'locais') echo 'aria-current="page"'; ?>>Lugares</a>
  <a href="veiculos.php" <?php if ($pagina_atual == 'veiculos') echo 'aria-current="page"'; ?>>Máquinas</a>
  <a href="tempo.php" <?php if ($pagina_atual == 'tempo') echo 'aria-current="page"'; ?>>Linha do tempo</a>
  <a href="quiz.php" <?php if ($pagina_atual == 'quiz') echo 'aria-current="page"'; ?>>Quiz</a>
</nav>

<main>
  <header class="capa">
    <svg class="nuvens" viewBox="0 0 600 200" aria-hidden="true">
      <g fill="#fff">
        <g class="n1"><ellipse cx="130" cy="90" rx="70" ry="26"/><ellipse cx="95" cy="76" rx="38" ry="26"/><ellipse cx="160" cy="72" rx="44" ry="30"/></g>
        <g class="n2"><ellipse cx="470" cy="60" rx="80" ry="24"/><ellipse cx="440" cy="46" rx="34" ry="24"/><ellipse cx="505" cy="44" rx="42" ry="28"/></g>
        <g class="n3"><ellipse cx="330" cy="150" rx="55" ry="18"/><ellipse cx="310" cy="140" rx="28" ry="18"/><ellipse cx="355" cy="138" rx="30" ry="20"/></g>
      </g>
    </svg>
    <h1><?= $titulo ?></h1>
    <p class="sub"><?= proteger($subtitulo) ?></p>
    <svg class="colinas" viewBox="0 0 1200 140" preserveAspectRatio="none" aria-hidden="true">
      <path d="M0 90C200 40 400 40 600 80S1000 120 1200 60V140H0Z" fill="#a9d6a4"/>
      <path d="M0 110C250 70 450 100 700 90S1050 60 1200 100V140H0Z" fill="#74b182"/>
      <path d="M0 130C300 100 500 125 800 115S1100 105 1200 125V140H0Z" fill="#4f7a5a"/>
    </svg>
    <svg class="fuligem f1" aria-hidden="true"><use href="#fuligem"/></svg>
    <svg class="fuligem f2" aria-hidden="true"><use href="#fuligem"/></svg>
  </header>
