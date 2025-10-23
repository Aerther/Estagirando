<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Aluno;

$aluno = new Aluno("Arthur", "Lassem", [], [], "Feliz", "Manhã", "Estagiando", 1);
$aluno->salvarAluno();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="./sair.php">Sair</a>
    <a href="./privado.php">aluno</a>
</body>
</html>