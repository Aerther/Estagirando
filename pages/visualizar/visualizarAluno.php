<?php

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Aluno;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_GET["id"])) header("Location: ./../../privado.php");

if($_SESSION["tipoUsuario"] == "aluno" && $_GET["id"] != $_SESSION["idUsuario"]) header("Location: ./../../privado.php");

$aluno = Aluno::findAluno($_GET["id"]);

$foto = $aluno->getFoto();
$curso = $aluno->getCurso();
$preferencias = $aluno->getPreferencias();
$naoPreferencias = $aluno->getNaoPreferencias();
$cidadesEstagiar = $aluno->getCidadesEstagiar();

// Settagem dos links
$linkEditarCadastro = $_SESSION["tipoUsuario"] == "Aluno" ? "editarCadastroAluno.php" : "editarCadastroProfessor.php";
$linkVisualizarCadastro = $_SESSION["tipoUsuario"] == "Aluno" ? "visualizarAluno.php" : "visualizarProfessor.php";
$linkIcone = $_SESSION["tipoUsuario"] == "Aluno" ? "iconAluno.png" : "iconProf.png";

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleVisualizar.css">

    <title>Visualizar Aluno</title>
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
                            <?php echo "<img src='./../../{$foto->getLinkFoto()}' alt='Foto do Aluno' />"; ?>
                        </figure>
                    </section>

                    <section class="dados-usuario">
                        <?php 

                        $status = ucwords($aluno->getStatusEstagio());

                        $cor = str_contains($status, "Procurando") ? "green" : "red";
 
                        echo "<p>Nome: {$aluno->getNome()} {$aluno->getSobrenome()} <span class='status' style='color: {$cor}; border: 2px solid {$cor}'>{$status}</span> </p>";
                        echo "<p>Email: {$aluno->getEmail()}</p>";
                        echo "<p>Curso: {$curso->getNome()}</p>";
                        echo "<p>Ingressou em {$aluno->getAnoIngresso()}</p>"; 
                        
                        ?>
                    </section>

                    <section class="dados-usuario">
                        <?php

                        echo "<p>Data de Nascimento: {$aluno->getDataNascimento()}</p>";

                        ?>

                        <p class="titulo-dados">Disponibilidade do Estágio</p>

                        <?php 

                        echo "<p>Modalidade: {$aluno->getModalidade()}</p>";
                        echo "<p>Turno: {$aluno->getTurnoDisponivel()}</p>"; 
                            
                        ?>
                    </section>
                </section>

                <section class="linha-2">
                    <section class="cidades">
                        <p class="titulo-dados">Cidades Para Estagiar</p>
                        
                        <div>
                            <?php

                            foreach($cidadesEstagiar as $cidade) {
                                if($cidade["nome"] == "Todos") {
                                    echo "<p>Qualquer Cidade</p>";
                                    continue;
                                }

                                echo "<p>{$cidade['nome']}, {$cidade['uf']}</p>";
                            }

                            ?>
                        </div>
                    </section>

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