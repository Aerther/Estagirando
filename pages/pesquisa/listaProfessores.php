<?php

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_SESSION["ultima_pesquisa"])) header("Location: ./../../privado.php");

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Professor;

$resultado = $_SESSION["ultima_pesquisa"];

$nome = $resultado["nome"];
$email = $resultado["email"];
$preferencias = $resultado["preferencias"];
$naoPreferencias = $resultado["naoPreferencias"];

$professores = Professor::pesquisar($nome, $email, $preferencias, $naoPreferencias);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleListagem.css">

    <title>Listagem Professores</title>
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

                    $foto = $professor->getFoto();
                    $disponibilidade = "";
                    if($professor->getstatusDisponibilidade()=="sim"){
                        $disponibilidade = "Disponível para orientar";
                        $cor = "green";
                    }elseif($professor->getstatusDisponibilidade()=="nao"){
                        $disponibilidade = "Indisponível para orientar";
                        $cor = "red";
                    }

                    /*// Preferências como String

                    $limite = 2;

                    $preferencias = "";
                    $naoPreferencias = "";

                    $i = 0;
                    foreach($professor->getPreferencias() as $descricao) {
                        if($i == $limite) break;

                        $preferencias .= $descricao . ", ";

                        $i++;
                    }

                    $i = 0;
                    foreach($professor->getNaoPreferencias() as $descricao) {
                        if($i == $limite) break;

                        $naoPreferencias .= $descricao . ", ";

                        $i++;
                    }

                    $preferencias = substr($preferencias, 0, -2);
                    $naoPreferencias = substr($naoPreferencias, 0, -2);*/

                    echo "<div class='usuario'>";

                    echo "<div class='imagem'";

                    echo "<a href='./../visualizar/visualizarProfessor.php?id={$professor->getIdUsuario()}'> <figure><img src='./../../{$foto->getLinkFoto()}' alt='Foto Professor' /></figure> </a>";

                    echo "</div>";

                    echo "<div class='dados'>";
                    
                    echo "<a href='./../visualizar/visualizarProfessor.php?id={$professor->getIdUsuario()}' class=nome> " . $professor->getNome() . " ". $professor->getSobrenome()."</a>";

                    echo "<p>{$professor->getEmail()}</p>";

                    echo "</div>";

                    echo "<div class='dados'>";
                    
                    echo "<p><span class='disponibilidade' style='color: {$cor}; border: 2px solid {$cor}'>{$disponibilidade}</span></p>";

                    echo "</div>";

                    /*echo "<div class='preferencias'>";

                    echo "<p>Preferências: {$preferencias}</p>";

                    echo "<p>Não Preferências: {$naoPreferencias}</p>";

                    echo "</div>";*/
                    
                    echo "</div>";
                }

                ?>
            </div>
        </main>
    </div>
    
</body>
</html>