<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Aluno;

$aluno = new Aluno("arthurlassem11@gmail.com", "if5dXvPL");
$aluno->salvarAluno("Arthur", "Lassem", [], [], "Feliz", "Manhã", "Estagiando", 1);

?>

<!DOCTYPE html>
<html lang="pt-BR">
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