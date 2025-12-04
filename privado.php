<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Usuario;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: index.php");

$msg = ($_SESSION["tipoUsuario"] == "aluno" ? "Navegue pelos professores cadastrados" : "Navegue pelos alunos cadastrados");

$usuario = Usuario::findUsuario($_SESSION["idUsuario"]);

$_SESSION["ultima_pesquisa"] = [
    "nome" => "",
    "email"=> "",
    "turno"=> "",
    "modalidades" => "",
    "cursos" => [-1],
    "cidades" => [-1],
    "preferencias" => array_keys($usuario->getPreferencias()),
    "naoPreferencias" => array_keys($usuario->getNaoPreferencias()),
];

$h = ($_SESSION["tipoUsuario"] == "aluno" ? "menuAluno.php" : "menuProfessor.php");

header("Location: ./{$h}");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./src/styles/reset.css">
    <link rel="stylesheet" href="./src/styles/styleHome.css">

    <title>Home</title>
</head>
<body>
    <div class="container">
        
        <?php 

        // Usado para definir o local da raiz
        $URL_BASE = ".";
        
        require_once __DIR__."/menu.php";

        ?>

        <div id='title'>
            <?php
            
            echo "<p class='title'>{$msg}</p>";
            
            ?>
        </div>
    </div>
</body>
</html>