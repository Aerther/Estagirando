<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Cidade;

session_start();

if (!isset($_GET["nome"])) {
    http_response_code(400);

    echo json_encode(["erro" => "Parâmetro 'nome' não fornecido"]);

    exit;
}

$uf = isset($_GET["uf"]) ? $_GET["uf"] : "";

try {
    $cidades = Cidade::findAllCidades($_GET["nome"], $uf, 10);
    
    header("Content-Type: application/json; charset=utf-8");
    
    $cidadesArray = array_map(function($cidade) {
        return [
            "ID_Cidade" => $cidade->getIdCidade(),
            "Nome" => $cidade->getNome(),
            "UF" => $cidade->getUF()
        ];
    }, $cidades);

    echo json_encode($cidadesArray);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode(["erro" => "Erro ao buscar cidades", "detalhe" => $e->getMessage()]);
}

?>