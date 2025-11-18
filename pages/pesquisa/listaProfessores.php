<?php

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Professor;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_POST["pesquisar"])) header("Location: ./../../privado.php");

$tipoUsuario = $_SESSION['tipoUsuario'] == "aluno" ? "Professor" : "Aluno";

if(empty($_POST)) {
    $_SESSION["erro"] = true;

    header("Location: ./pesquisa{$tipoUsuario}.php");
}

$nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
$email = isset($_POST["email"]) ? $_POST["email"] : "";
$preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [0];
$naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [0];

$professores = Professor::pesquisar($nome, $email, $preferencias, $naoPreferencias);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    foreach($professores as $professor) {
        echo $professor->getNome().$professor->getSobrenome()." ";
    }

    ?>
</body>
</html>