<?php

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Professor;

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
$preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [-1];
$naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [-1];

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
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>

        <main>
            <div class="usuarios">
                <?php

                foreach($professores as $professor) {

                    echo "<div class='usuario'>";

                    echo "<div>";

                    echo "<figure><img src='' alt='Foto Professor' /></figure>";

                    echo "</div>";

                    echo "<a href='./../visualizar/visualizarProfessor.php?id={$professor->getIdUsuario()}'>" . $professor->getNome().$professor->getSobrenome()."</a>";
                    
                    echo "</div>";
                }

                ?>
            </div>
        </main>
    </div>
    
</body>
</html>