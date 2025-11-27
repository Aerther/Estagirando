<?php

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_POST["pesquisar"])) header("Location: ./../../privado.php");

$tipoUsuario = $_SESSION['tipoUsuario'] == "aluno" ? "Professor" : "Aluno";

$quant = 0;
foreach($_POST as $key => $value) {
    if(!empty($value)) continue;

    $quant++;
}

if((count($_POST) - 1 <= $quant)) {
    $_SESSION["erro"] = true;

    header("Location: ./../pesquisa/pesquisa{$tipoUsuario}.php");
    die;
}

$_SESSION["ultima_pesquisa"] = [
    "nome" => isset($_POST["nome"]) ? $_POST["nome"] : "",
    "email"=> isset($_POST["email"]) ? $_POST["email"] : "",
    "turno"=> isset($_POST["turno"]) ? $_POST["turno"] : "",
    "modalidades" => isset($_POST["modalidades"]) ? $_POST["modalidades"] : "",
    "cursos" => isset($_POST["curso"]) ? [$_POST["curso"]] : [-1],
    "cidades" => isset($_POST["cidadesEstagiar"]) ? $_POST["cidadesEstagiar"] : [-1],
    "preferencias" => isset($_POST["preferencias"]) ? $_POST["preferencias"] : [-1],
    "naoPreferencias" => isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [-1]
];

header("Location: ./../pesquisa/listaAlunos.php");

?>