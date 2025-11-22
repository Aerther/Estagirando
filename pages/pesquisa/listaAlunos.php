<?php

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Aluno;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_POST["pesquisar"])) header("Location: ./../../privado.php");

$tipoUsuario = $_SESSION['tipoUsuario'] == "aluno" ? "Professor" : "Aluno";

$quant = 0;
foreach($_POST as $key => $value) {
    if(!empty($value)) continue;

    $quant++;
}

if(count($_POST) - 1 <= $quant) {
    $_SESSION["erro"] = true;

    header("Location: ./pesquisa{$tipoUsuario}.php");
}

$nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
$email = isset($_POST["email"]) ? $_POST["email"] : "";
$turno = isset($_POST["turno"]) ? $_POST["turno"] : "";
$modalidades = isset($_POST["modalidades"]) ? $_POST["modalidades"] : "";

$cidades = isset($_POST["cidades"]) ? $_POST["cidades"] : [-1];
$cursos = isset($_POST["cursos"]) ? $_POST["cursos"] : [-1];
$preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [-1];
$naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [-1];

$alunos = Aluno::pesquisar($nome, $email, $turno, $cursos, $modalidades, $cidades, $preferencias, $naoPreferencias);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleListagem.css">

    <title>Listagem Alunos</title>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>

        <main>
            <div class="usuarios">
                <?php

                foreach($alunos as $aluno) {

                    $foto = $aluno->getFoto();
                    $curso = $aluno->getCurso();

                    // Preferências como String

                    $limite = 2;

                    $preferencias = "";
                    $naoPreferencias = "";

                    $i = 0;
                    foreach($aluno->getPreferencias() as $descricao) {
                        if($i == $limite) break;

                        $preferencias .= $descricao . ", ";

                        $i++;
                    }

                    $i = 0;
                    foreach($aluno->getNaoPreferencias() as $descricao) {
                        if($i == $limite) break;

                        $naoPreferencias .= $descricao . ", ";

                        $i++;
                    }

                    $preferencias = substr($preferencias, 0, -2);
                    $naoPreferencias = substr($naoPreferencias, 0, -2);

                    echo "<div class='usuario'>";

                    echo "<div class='imagem'";

                    echo "<a href='./../visualizar/visualizarAluno.php?id={$aluno->getIdUsuario()}'> <figure><img src='./../../{$foto->getLinkFoto()}' alt='Foto Professor' /></figure> </a>";

                    echo "</div>";

                    echo "<div class='dados'>";
                    
                    echo "<a href='./../visualizar/visualizarAluno.php?id={$aluno->getIdUsuario()}'>" . $aluno->getNome() . " ". $aluno->getSobrenome()."</a>";

                    echo "<p>{$aluno->getEmail()}</p>";

                    echo "<p>{$curso->getNome()}</p>";

                    echo "</div>";

                    echo "<div class='preferencias'>";

                    echo "<p>Preferências: {$preferencias}</p>";

                    echo "<p>Não Preferências: {$naoPreferencias}</p>";

                    echo "</div>";
                    
                    echo "</div>";
                }

                ?>
            </div>
        </main>
    </div>
    
</body>
</html>