<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Preferencia;
use App\Classes\Curso;

$preferencias = Preferencia::findAllPreferencias();
$cursos = Curso::findAllCursos();
$cidadesEstagiar = [];

session_start();

$mensagemErro = "";

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] == "aluno" && $_GET["id"] != $_SESSION["idUsuario"]) header("Location: ./../../privado.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesquisa de Alunos</title>

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
        
        <div id='title'>
            <?php
                echo "<p class='title'>Pesquisa de alunos</p>"
            ?>
        </div>

        <main>
            <form action="./../utils/dadosAlunos.php" method="post">
                <section class="dados">
                    

                    <div class="coluna1">
                        <section>
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome">
                        </section>

                        <section>
                            <label for="email">E-mail:</label>
                            <input type="email" name="email">
                        </section>

                        <section>
                            <label for="curso">Curso:</label>

                            <select id="curso" name="curso">
                                <option value='-1'>Qualquer curso</option>
                                
                                <?php

                                foreach ($cursos as $curso) {
                                    echo "<option value='{$curso->getIdCurso()}'>{$curso->getNome()}</option>";
                                }

                                ?>
                            </select>
                        </section>
                    </div>

                    <div class="coluna2">
                        <section>
                            <label for="ano">Ano de ingresso:</label>
                            <input type="number" name="ano" max="2025">
                        </section>

                        <section>
                            <label for="turno">Turno disponível:</label>

                            <select id="turno" name="turno">
                                <option value="manhã"> Manhã</option>
                                <option value="tarde"> Tarde</option>
                                <option value="noite"> Noite</option>
                            </select>
                        </section>
                    </div>
                </section>

                <section class="preferencias">
                    <section class="cidades">
                        <p>Cidades para estagiar:</p>

                        <input type="text" name="cidadeEstagiar" id="cidadeEstagiar" placeholder="Cidade, Estado (sigla)">

                        <div class='sugestoes'></div>

                        <div class="checkboxes">
                            <label>Cidades escolhidas:</label>

                            <?php
                            
                            echo "<label><input type='checkbox' name='cidadesEstagiar[]' value=1 id='qualquerCidade'> Qualquer Cidade</label>";

                            ?>
                        </div>
                    </section>

                    <section>
                        <label for="preferencias">Preferências:</label>

                        <div id="preferencias">
                            <?php

                            foreach ($preferencias as $preferencia) {
                                echo "<label><input type='checkbox' name='preferencias[]' onchange='sincronizarCheckbox(this)' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <label for="naoPreferencias">Não preferências:</label>
                        
                        <div id="naoPreferencias">
                            <?php

                            foreach ($preferencias as $preferencia) {
                                echo "<label><input type='checkbox' name='naoPreferencias[]' onchange='sincronizarCheckbox(this)' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
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
