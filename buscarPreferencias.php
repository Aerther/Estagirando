<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Preferencia;

session_start();

if (!isset($_GET["idCurso"])) {
    http_response_code(400);

    echo json_encode(["erro" => "Parâmetro 'idCurso' não fornecido"]);

    exit;
}

try {
    $ps = Preferencia::findAllPreferenciasByCurso($_GET["idCurso"]);
    
    header("Content-Type: application/json; charset=utf-8");
    
    $psArray = array_map(function($p) {
        return [
            "ID_Preferencia" => $p->getIdPreferencia(),
            "Descricao" => $p->getDescricao()
        ];
    }, $ps);

    echo json_encode($psArray);

} catch (Exception $e) {
    http_response_code(500);

    echo json_encode(["erro" => "Erro ao buscar preferencias", "detalhe" => $e->getMessage()]);
}

?>