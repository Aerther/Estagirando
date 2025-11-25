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
    "preferencias" => isset($_POST["preferencias"]) ? $_POST["preferencias"] : [-1],
    "naoPreferencias" => isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [-1]
];

header("Location: ./../pesquisa/listaProfessores.php");

?>