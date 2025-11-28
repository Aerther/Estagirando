<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;
use App\Classes\Preferencia;

session_start();

$mensagemErro = "";

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] == "professor" && $_GET["id"] != $_SESSION["idUsuario"]) header("Location: ./../../privado.php");

if(isset($_SESSION["erro"])) {
    $mensagemErro = "Preencha ao menos um campo para pesquisar";

    unset($_SESSION["erro"]); 
}

$aluno = Aluno::findAluno($_SESSION["idUsuario"]);
$preferencias = Preferencia::findAllPreferenciasByCurso($aluno->getCurso()->getIdCurso());

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesquisa de Professores</title>

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/stylePesquisa.css">

    <script src="./../../src/js/editarCad.js" defer></script>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>
        
        <main>
            <form action="./../utils/dadosProfessores.php" method="post">
                <section class="dados">
                    <h2>Pesquisa de professores</h2>

                    <div class="coluna1">
                        <section>
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome">
                        </section>

                        <section>
                            <label for="email">E-mail:</label>
                            <input type="email" name="email">
                        </section>
                    </div>

                    <div class="coluna2">

                    </div>
                </section>

                <section class="preferencias">
                    <section>
                        <p>Preferências:</p>

                        <div>
                            <?php
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $aluno->getPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' onchange='sincronizarCheckbox(this)' name='preferencias[]' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências:</p>

                        <div>
                            <?php
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $aluno->getNaoPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' onchange='sincronizarCheckbox(this)' name='naoPreferencias[]' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>
                </section>

                <section id="btn" class="links">
                    <?php echo "<p class='erro'>{$mensagemErro}</p>"; ?>

                    <div>
                        <input class="link" type="submit" name="pesquisar" value="Pesquisar">
                        <a class="link" href="./../../privado.php" id='cancelar'>Cancelar</a>
                    </div>
                </section>
            </form>
        </main>
    </div>
</body>
</html>