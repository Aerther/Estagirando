<?php

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Professor;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_GET["id"])) header("Location: ./../../privado.php");

if($_SESSION["tipoUsuario"] == "professor" && $_GET["id"] != $_SESSION["idUsuario"]) header("Location: ./../../privado.php");

$professor = Professor::findProfessor($_GET["id"]);

$foto = $professor->getFoto();
$preferencias = $professor->getPreferencias();
$naoPreferencias = $professor->getNaoPreferencias();

// Settagem dos links
$linkEditarCadastro = $_SESSION["tipoUsuario"] == "Professor" ? "editarCadastroProfessor.php" : "editarCadastroAluno.php";
$linkVisualizarCadastro = $_SESSION["tipoUsuario"] == "Professor" ? "visualizarProfessor.php" : "visualizarAluno.php";
$linkIcone = $_SESSION["tipoUsuario"] == "Professor" ? "iconProf.png" : "iconAluno.png";

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleVisualizar.css">

    <title>Visualizar Professor</title>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>

        <main>
            <div class="content">
                <section class="linha-1">
                    <section class="imagem">
                        <figure>
                            <?php echo "<img src='./../../{$foto->getLinkFoto()}' alt='Foto do Professor' />"; ?>
                        </figure>
                        <div id=editPerfil>
                            <a href="./../editar/editarCadastroProfessor.php">Editar Cadastro</a> 
                        </div>
                    </section>

                    <section class="dados-usuario professor">
                        <?php  

                        $status = $professor->getStatusDisponibilidade();

                        if($status=='sim'){
                            $status = "Disponível";
                            $cor = "green";
                        } else {
                            $status = "Não Disponivel";
                            $cor = "red";
                        }

                        
 
                        echo "<p><strong style='margin-right: 8px;'>Nome: </strong>{$professor->getNome()} {$professor->getSobrenome()} <span> <span class='status' style='color: {$cor}; border: 2px solid {$cor}'>{$status}</span> </p>";
                        echo "<p><strong style='margin-right: 8px;'>Email: </strong>{$professor->getEmail()}</p>";
                        
                        ?>
                    </section>

                </section>

                <section class="linha-2-prof">


                    <section class="preferencias">
                        <p class="titulo-dados">Preferências</p>
                        
                        <div>
                            <?php

                            foreach($preferencias as $preferencia) {
                                echo "<p>{$preferencia}</p>";
                            }

                            ?>
                        </div>
                    </section>

                    <section class="nao-preferencias">
                        <p class="titulo-dados">Não Preferências</p>
                        
                        <div>
                            <?php

                            foreach($naoPreferencias as $preferencia) {
                                echo "<p>{$preferencia}</p>";
                            }

                            ?>
                        </div>
                    </section>
                </section>
            </div>
        </main>
    </div>
</body>
</html>