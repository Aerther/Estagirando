<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__."/vendor/autoload.php";

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: index.php");
$msg = ($_SESSION["tipoUsuario"] == "aluno" ? "Navegue pelos professores cadastrados" : "Navegue pelos alunos cadastrados");


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
                echo "<p class='title'>{$msg}</p>"
            ?>
        </div>


    </div>
</body>
</html>