<?php 

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Cidade;

$url = "https://servicodados.ibge.gov.br/api/v1/localidades/municipios/";
$dados = file_get_contents($url);
$cidades = json_decode($dados, true);

foreach ($cidades as $cidade) {
    $nome = $cidade['nome'];
    $uf = $cidade["microrregiao"]["mesorregiao"]["UF"]["sigla"];

    if(isset($nome) && isset($uf)) {
        $c = new Cidade($nome, $uf);
        $c->salvarCidade();
    }
}

header("Location: index.php");

?>