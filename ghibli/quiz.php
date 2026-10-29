<?php
// A sessão guarda o placar e a pergunta atual enquanto o usuário joga
session_start();
include 'includes/funcoes.php';

// Só usamos filmes que têm imagem
$todos = pegarLista('films');
$filmes = [];
if ($todos != null) {
    foreach ($todos as $filme) {
        if (imagemDoFilme($filme) != '') {
            $filmes[] = $filme;
        }
    }
}

// 1) O usuário clicou em uma resposta (formulário POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resp']) && isset($_SESSION['atual'])) {
    $acertou = ($_POST['resp'] == $_SESSION['atual']);

    if (!isset($_SESSION['rodadas'])) {
        $_SESSION['rodadas'] = 0;
    }
    if (!isset($_SESSION['acertos'])) {
        $_SESSION['acertos'] = 0;
    }
    $_SESSION['rodadas'] = $_SESSION['rodadas'] + 1;
    if ($acertou) {
        $_SESSION['acertos'] = $_SESSION['acertos'] + 1;
    }

    $_SESSION['resultado'] = ['certo' => $_SESSION['atual'], 'acertou' => $acertou];
    unset($_SESSION['atual']);

    // Recarrega a página (assim, apertar F5 não soma o ponto de novo)
    header('Location: quiz.php');
    exit;
}

// 2) Botões "zerar placar" e "próxima cena"
if (isset($_GET['zerar'])) {
    unset($_SESSION['acertos'], $_SESSION['rodadas'], $_SESSION['atual'], $_SESSION['resultado']);
    header('Location: quiz.php');
    exit;
}
if (isset($_GET['novo'])) {
    unset($_SESSION['atual'], $_SESSION['resultado']);
    header('Location: quiz.php');
    exit;
}

// 3) Precisa sortear uma pergunta nova?
$precisa_nova = false;
if (!isset($_SESSION['resultado'])) {
    if (!isset($_SESSION['atual'])) {
        $precisa_nova = true;
    } elseif (acharFilmePorId($_SESSION['atual'], $filmes) == null) {
        $precisa_nova = true;
    }
}

if ($precisa_nova && count($filmes) >= 4) {
    $certo = $filmes[array_rand($filmes)];

    // 4 alternativas: o filme certo + 3 errados
    $opcoes = [$certo['id']];
    $embaralhados = $filmes;
    shuffle($embaralhados);
    foreach ($embaralhados as $filme) {
        if (count($opcoes) < 4 && $filme['id'] != $certo['id']) {
            $opcoes[] = $filme['id'];
        }
    }
    shuffle($opcoes);

    $_SESSION['atual'] = $certo['id'];
    $_SESSION['opcoes'] = $opcoes;
    $_SESSION['x'] = rand(15, 85); // ponto da imagem que será ampliado
    $_SESSION['y'] = rand(15, 85);
}

// 4) Dados que a página vai mostrar
$resultado = null;
if (isset($_SESSION['resultado'])) {
    $resultado = $_SESSION['resultado'];
}
$id_filme = '';
if ($resultado != null) {
    $id_filme = $resultado['certo'];
} elseif (isset($_SESSION['atual'])) {
    $id_filme = $_SESSION['atual'];
}
$filme = acharFilmePorId($id_filme, $filmes);

$acertos = 0;
if (isset($_SESSION['acertos'])) {
    $acertos = $_SESSION['acertos'];
}
$rodadas = 0;
if (isset($_SESSION['rodadas'])) {
    $rodadas = $_SESSION['rodadas'];
}
$x = 50;
$y = 50;
if (isset($_SESSION['x'])) {
    $x = (int)$_SESSION['x'];
    $y = (int)$_SESSION['y'];
}

$titulo = 'Quiz';
$subtitulo = 'Que filme é este? Adivinhe pelo pedacinho da cena.';
$pagina_atual = 'quiz';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quiz - Álbum Ghibli</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kaisei+Decol:wght@700&family=Zen+Maru+Gothic:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- CSS: o geral (base) e o desta página -->
  <link rel="stylesheet" href="css/base.css?v=1">
  <link rel="stylesheet" href="css/quiz.css?v=1">
</head>
<body>

<?php include 'includes/cabecalho.php'; ?>

<section class="quiz">
<?php if ($filme == null) { ?>
  <p class="aviso erro"><?= ERRO_API ?></p>

<?php } else { ?>
  <p class="placar">
    Acertos: <strong><?= $acertos ?></strong> de <strong><?= $rodadas ?></strong>
    · <a href="?zerar=1">zerar placar</a>
  </p>

  <!-- A imagem começa ampliada em um ponto (x, y). Ao responder, a classe "revelada" mostra ela inteira. -->
  <div class="recorte <?php if ($resultado != null) echo 'revelada'; ?>" style="--x:<?= $x ?>%;--y:<?= $y ?>%">
    <?php
      if ($resultado != null) {
          $alt = 'Cena de ' . tituloEmPortugues($filme);
      } else {
          $alt = 'Pedaço de uma cena misteriosa';
      }
      echo mostrarImagem([imagemDoFilme($filme), pegar($filme, 'image')], $alt);
    ?>
  </div>

  <?php if ($resultado != null) { ?>
    <p class="veredito <?php if ($resultado['acertou']) echo 'certa'; else echo 'errada'; ?>">
      <?php if ($resultado['acertou']) echo 'Acertou! ✨'; else echo 'Quase! Era:'; ?>
      <strong><?= proteger(tituloEmPortugues($filme)) ?></strong>
    </p>
    <p class="acoes">
      <a class="botao" href="?novo=1">Próxima cena</a>
      <a class="botao claro" href="filme.php?id=<?= proteger($filme['id']) ?>">Ver o filme</a>
    </p>

  <?php } else { ?>
    <form method="post" class="opcoes">
      <?php foreach ($_SESSION['opcoes'] as $id_opcao) { ?>
        <?php $filme_opcao = acharFilmePorId($id_opcao, $filmes); ?>
        <button type="submit" name="resp" value="<?= proteger($id_opcao) ?>"><?= proteger(tituloEmPortugues($filme_opcao)) ?></button>
      <?php } ?>
    </form>
  <?php } ?>
<?php } ?>
</section>

<?php include 'includes/rodape.php'; ?>
