<?php
// =====================================================
// FUNÇÕES DO SITE - Álbum Ghibli
// Este arquivo é incluído em todas as páginas.
// =====================================================

const URL_API  = 'https://ghibliapi.vercel.app/';
const ERRO_API = 'A floresta não respondeu. Tente de novo em instantes.';

// Conta quantas traduções já foram pedidas nesta página (para não travar o site)
$chamadas_traducao = 0;

// Títulos dos filmes em português
const TITULOS_PT = [
    'Castle in the Sky' => 'O Castelo no Céu',
    'Grave of the Fireflies' => 'O Túmulo dos Vagalumes',
    'My Neighbor Totoro' => 'Meu Amigo Totoro',
    "Kiki's Delivery Service" => 'O Serviço de Entregas da Kiki',
    'Only Yesterday' => 'Memórias de Ontem',
    'Porco Rosso' => 'Porco Rosso: O Último Herói Romântico',
    'Pom Poko' => 'Pom Poko',
    'Whisper of the Heart' => 'Sussurros do Coração',
    'Princess Mononoke' => 'Princesa Mononoke',
    'My Neighbors the Yamadas' => 'Meus Vizinhos, os Yamada',
    'Spirited Away' => 'A Viagem de Chihiro',
    'The Cat Returns' => 'O Reino dos Gatos',
    "Howl's Moving Castle" => 'O Castelo Animado',
    'Tales from Earthsea' => 'Contos de Terramar',
    'Ponyo' => 'Ponyo: Uma Amizade que Veio do Mar',
    'Arrietty' => 'O Mundo dos Pequeninos',
    'From Up on Poppy Hill' => 'Da Colina das Papoulas',
    'The Wind Rises' => 'Vidas ao Vento',
    'The Tale of the Princess Kaguya' => 'O Conto da Princesa Kaguya',
    'When Marnie Was There' => 'As Memórias de Marnie',
    'The Red Turtle' => 'A Tartaruga Vermelha',
    'Earwig and the Witch' => 'Aya e a Bruxa',
];

// Palavras curtas (cores, clima, terreno, tipo de máquina) em português
const DICIONARIO = [
    'black' => 'preto', 'brown' => 'castanho', 'blonde' => 'loiro', 'blond' => 'loiro',
    'gray' => 'cinza', 'grey' => 'cinza', 'white' => 'branco', 'green' => 'verde',
    'blue' => 'azul', 'red' => 'vermelho', 'orange' => 'laranja', 'hazel' => 'avelã',
    'violet' => 'violeta', 'purple' => 'roxo', 'pink' => 'rosa', 'yellow' => 'amarelo',
    'dark' => 'escuro', 'light' => 'claro', 'none' => 'nenhum', 'mild' => 'ameno',
    'dry' => 'seco', 'wet' => 'úmido', 'humid' => 'úmido', 'temperate' => 'temperado',
    'varies' => 'variável', 'warm' => 'quente', 'cold' => 'frio', 'hot' => 'quente',
    'snow' => 'neve', 'tropical' => 'tropical', 'continental' => 'continental',
    'mountain' => 'montanha', 'mountains' => 'montanhas', 'river' => 'rio', 'hill' => 'colina',
    'hills' => 'colinas', 'forest' => 'floresta', 'plain' => 'planície', 'plains' => 'planícies',
    'ocean' => 'oceano', 'sea' => 'mar', 'coastal' => 'costeiro', 'city' => 'cidade',
    'lake' => 'lago', 'cliff' => 'penhasco', 'desert' => 'deserto', 'island' => 'ilha',
    'grassland' => 'campo', 'sky' => 'céu', 'valley' => 'vale', 'urban' => 'urbano',
    'swamp' => 'pântano', 'wetland' => 'pântano', 'airship' => 'dirigível',
    'aircraft' => 'aeronave', 'boat' => 'barco', 'ship' => 'navio', 'train' => 'trem',
    'car' => 'carro', 'plane' => 'avião', 'bicycle' => 'bicicleta', 'glider' => 'planador',
    'truck' => 'caminhão', 'and' => 'e', 'or' => 'ou',
];


// ---------- Funções pequenas ----------

// Evita que texto vindo da API quebre o HTML (segurança)
function proteger($texto)
{
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}

// Pega um valor do array; se não existir, devolve texto vazio
function pegar($lista, $chave)
{
    if (is_array($lista) && isset($lista[$chave])) {
        return $lista[$chave];
    }
    return '';
}

// Mostra "—" quando o dado não existe e traduz gênero
function valor($v)
{
    if ($v === '' || $v === null || $v === 'NA') {
        return '—';
    }
    if ($v === 'Male') {
        return 'Masculino';
    }
    if ($v === 'Female') {
        return 'Feminino';
    }
    return (string)$v;
}

// Traduz palavras curtas usando o dicionário lá de cima
function traduzirTermos($v)
{
    $v = valor($v);
    if ($v == '—') {
        return $v;
    }
    $palavras = explode(' ', $v);
    foreach ($palavras as $i => $palavra) {
        $chave = strtolower(trim($palavra, ',.;'));
        if (isset(DICIONARIO[$chave])) {
            $palavras[$i] = str_ireplace($chave, DICIONARIO[$chave], $palavra);
        }
    }
    $texto = implode(' ', $palavras);
    return mb_strtoupper(mb_substr($texto, 0, 1)) . mb_substr($texto, 1);
}

// Tira acentos e deixa minúsculo (para a busca achar "ceu" e "céu")
function tirarAcentos($texto)
{
    $texto = mb_strtolower($texto, 'UTF-8');
    $trocas = ['á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'é' => 'e', 'ê' => 'e',
               'í' => 'i', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ü' => 'u', 'ç' => 'c'];
    return strtr($texto, $trocas);
}

// O link de um item termina com o id (ex.: .../films/123). Esta função pega só o id.
function idDoLink($link)
{
    return basename(rtrim($link, '/'));
}


// ---------- Acessando a API ----------

// Faz a requisição GET e devolve o JSON já convertido em array (ou null se der erro)
function buscarApi($url)
{
    $resposta = false;
    $status = 0;

    if (function_exists('curl_init')) {
        // Jeito principal: cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'GhibliAlbum/1.0 (trabalho escolar)');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        $resposta = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    } else {
        // Plano B para quando o PHP não tem cURL
        $opcoes = ['http' => [
            'timeout' => 15,
            'ignore_errors' => true,
            'header' => "User-Agent: GhibliAlbum/1.0\r\nAccept: application/json\r\n",
        ]];
        $resposta = @file_get_contents($url, false, stream_context_create($opcoes));
        if (isset($http_response_header)) {
            foreach ($http_response_header as $linha) {
                if (strpos($linha, 'HTTP/') === 0) {
                    $partes = explode(' ', $linha);
                    $status = (int)$partes[1];
                }
            }
        }
    }

    if ($resposta === false || $status != 200) {
        return null;
    }
    $dados = json_decode($resposta, true);
    if (!is_array($dados)) {
        return null;
    }
    return $dados;
}

// recurso pode ser: films, people, locations ou vehicles
function pegarLista($recurso)
{
    return buscarApi(URL_API . $recurso);
}


// ---------- Funções sobre filmes ----------

function tituloEmPortugues($filme)
{
    $titulo = pegar($filme, 'title');
    if (isset(TITULOS_PT[$titulo])) {
        return TITULOS_PT[$titulo];
    }
    return $titulo;
}

function acharFilmePorId($id, $filmes)
{
    if (!is_array($filmes)) {
        return null;
    }
    foreach ($filmes as $filme) {
        if ($filme['id'] == $id) {
            return $filme;
        }
    }
    return null;
}

function acharFilmePorTitulo($titulo, $filmes)
{
    if (!is_array($filmes)) {
        return null;
    }
    foreach ($filmes as $filme) {
        if ($filme['title'] == $titulo) {
            return $filme;
        }
    }
    return null;
}

// Transforma os links dos filmes de um item em uma lista de títulos
function titulosDosFilmes($links, $todos_filmes)
{
    $titulos = [];
    if (!is_array($links)) {
        return $titulos;
    }
    foreach ($links as $link) {
        $filme = acharFilmePorId(idDoLink($link), $todos_filmes);
        if ($filme != null) {
            $titulos[] = tituloEmPortugues($filme);
        }
    }
    return $titulos;
}

// Primeiro filme em que o item aparece (usamos a cena dele como imagem)
function primeiroFilme($item, $todos_filmes)
{
    $links = pegar($item, 'films');
    if (!is_array($links)) {
        return null;
    }
    foreach ($links as $link) {
        $filme = acharFilmePorId(idDoLink($link), $todos_filmes);
        if ($filme != null) {
            return $filme;
        }
    }
    return null;
}

// Cena do filme (banner); se não tiver, usa o pôster
function imagemDoFilme($filme)
{
    if (pegar($filme, 'movie_banner') != '') {
        return $filme['movie_banner'];
    }
    return pegar($filme, 'image');
}

// Usada para ordenar a linha do tempo
function compararAno($a, $b)
{
    return (int)$a['release_date'] - (int)$b['release_date'];
}


// ---------- Busca e tradução ----------

// A API não tem busca, então filtramos a lista aqui no PHP
function filtrarLista($lista, $termo, $campo)
{
    if ($termo == '') {
        return $lista;
    }
    $busca = tirarAcentos($termo);
    $achados = [];
    foreach ($lista as $item) {
        $nomes = [pegar($item, $campo), pegar($item, 'original_title_romanised')];
        if (isset($item['title'])) {
            $nomes[] = tituloEmPortugues($item);
        }
        foreach ($nomes as $nome) {
            if ($nome != '' && strpos(tirarAcentos($nome), $busca) !== false) {
                $achados[] = $item;
                break;
            }
        }
    }
    return $achados;
}

// Traduz do inglês para o português usando a API pública MyMemory.
// O resultado fica salvo na pasta cache/ para não traduzir de novo.
function traduzirTexto($texto)
{
    global $chamadas_traducao;

    $texto = trim($texto);
    if ($texto == '') {
        return '';
    }

    $pasta = __DIR__ . '/../cache';
    $arquivo = $pasta . '/' . md5($texto) . '.txt';
    if (is_file($arquivo)) {
        return file_get_contents($arquivo);
    }

    // A MyMemory aceita no máximo ~500 caracteres por vez: quebramos por frases
    $pedacos = [];
    $atual = '';
    $frases = preg_split('/(?<=[.!?])\s+/', $texto);
    foreach ($frases as $frase) {
        if ($atual != '' && strlen($atual . ' ' . $frase) > 450) {
            $pedacos[] = $atual;
            $atual = '';
        }
        $atual = trim($atual . ' ' . $frase);
    }
    if ($atual != '') {
        $pedacos[] = $atual;
    }

    $traduzido = [];
    foreach ($pedacos as $pedaco) {
        if ($chamadas_traducao >= 8) {
            return $texto; // limite por página
        }
        $chamadas_traducao++;
        $url = 'https://api.mymemory.translated.net/get?q=' . urlencode($pedaco) . '&langpair=en|pt-BR';
        $dados = buscarApi($url);
        $resultado = '';
        if (isset($dados['responseData']['translatedText'])) {
            $resultado = $dados['responseData']['translatedText'];
        }
        if ($resultado == '' || strpos($resultado, 'MYMEMORY WARNING') !== false) {
            return $texto; // se falhar, mostra o original
        }
        $traduzido[] = html_entity_decode($resultado, ENT_QUOTES, 'UTF-8');
    }

    $final = implode(' ', $traduzido);
    if (!is_dir($pasta)) {
        @mkdir($pasta, 0775, true);
    }
    @file_put_contents($arquivo, $final);
    return $final;
}


// ---------- Imagens e cards ----------

// Mostra uma imagem. Recebe uma lista de endereços: se o primeiro falhar,
// o navegador tenta o próximo; se todos falharem, a imagem some.
function mostrarImagem($enderecos, $alt, $classe = '')
{
    $lista = [];
    foreach ($enderecos as $endereco) {
        if ($endereco != '' && !in_array($endereco, $lista)) {
            $lista[] = $endereco;
        }
    }
    if (count($lista) == 0) {
        return '';
    }
    $reserva = implode('|', array_slice($lista, 1));
    $js = "var l=(this.dataset.alt||'').split('|').filter(Boolean);if(l.length){this.dataset.alt=l.slice(1).join('|');this.src=l[0];}else{this.remove();}";

    $html = '<img';
    if ($classe != '') {
        $html .= ' class="' . proteger($classe) . '"';
    }
    $html .= ' src="' . proteger($lista[0]) . '" alt="' . proteger($alt) . '"';
    $html .= ' loading="lazy" referrerpolicy="no-referrer"';
    $html .= ' data-alt="' . proteger($reserva) . '" onerror="' . proteger($js) . '">';
    return $html;
}

// Monta os dados de um card (filme, personagem, lugar ou máquina)
function montarCard($recurso, $item, $termo, $todos_filmes)
{
    $card = [
        'nome' => '', 'sub' => '', 'imagens' => [], 'legenda' => '', 'selo' => '',
        'texto' => '', 'link' => '', 'alto' => false, 'linhas' => [],
    ];

    $filmes_do_item = implode(', ', titulosDosFilmes(pegar($item, 'films'), $todos_filmes));
    if ($filmes_do_item == '') {
        $filmes_do_item = '—';
    }

    if ($recurso == 'films') {
        $card['nome'] = tituloEmPortugues($item);
        if ($item['title'] != $card['nome']) {
            $card['sub'] = $item['title'];
        }
        $card['imagens'] = [pegar($item, 'movie_banner'), pegar($item, 'image')];
        $card['selo'] = pegar($item, 'release_date');
        $card['alto'] = true;
        $card['link'] = 'filme.php?id=' . $item['id'] . '&q=' . urlencode($termo);
        $card['linhas'] = [
            'Diretor' => valor(pegar($item, 'director')),
            'Duração' => valor(pegar($item, 'running_time')) . ' min',
            'Nota' => valor(pegar($item, 'rt_score')) . '/100',
        ];
        return $card;
    }

    // Personagens, lugares e máquinas não têm imagem na API.
    // Usamos uma cena do primeiro filme em que aparecem.
    $filme = primeiroFilme($item, $todos_filmes);
    if ($filme != null && imagemDoFilme($filme) != '') {
        $card['imagens'] = [pegar($filme, 'movie_banner'), pegar($filme, 'image')];
        $card['legenda'] = 'Cena de ' . tituloEmPortugues($filme);
    }
    $card['nome'] = $item['name'];

    if ($recurso == 'people') {
        $card['linhas'] = [
            'Gênero' => valor(pegar($item, 'gender')),
            'Idade' => valor(pegar($item, 'age')),
            'Olhos' => traduzirTermos(pegar($item, 'eye_color')),
            'Cabelo' => traduzirTermos(pegar($item, 'hair_color')),
            'Aparece em' => $filmes_do_item,
        ];
    } elseif ($recurso == 'locations') {
        $card['linhas'] = [
            'Clima' => traduzirTermos(pegar($item, 'climate')),
            'Terreno' => traduzirTermos(pegar($item, 'terrain')),
            'Água na superfície' => valor(pegar($item, 'surface_water')) . '%',
            'Aparece em' => $filmes_do_item,
        ];
    } else { // vehicles
        $card['sub'] = traduzirTermos(pegar($item, 'vehicle_class'));
        $card['texto'] = traduzirTexto(pegar($item, 'description'));
        $card['linhas'] = [
            'Comprimento' => valor(pegar($item, 'length')),
            'Aparece em' => $filmes_do_item,
        ];
    }
    return $card;
}
