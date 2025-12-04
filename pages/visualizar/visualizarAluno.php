<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

$modalidades = explode(", ", $aluno->getModalidade());

$modalidadeTexto = "";

foreach($modalidades as $index => $modalidade) {
    if(empty($modalidades[1])) break;

    if($index == count($modalidades) - 2) {
        $modalidadeTexto = substr($modalidadeTexto, 0, -2);

        $modalidadeTexto .= " ou " . trim($modalidade);

        break;
    }

    $modalidadeTexto .= $modalidade . ", ";
}

if(empty($modalidadeTexto)) {
    $modalidadeTexto = $modalidades[0];
}

$modalidadeTexto = ucfirst(strtolower($modalidadeTexto));

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleVisualizar2.css">

    <title>Visualizar Aluno</title>

    <style>
        .linha-2 {
            grid-template-columns: repeat(3, 1fr); 
        }

        .content {
            grid-template-columns: 40% 60%;
        }

        .dados-usuario {
            grid-template-rows: repeat(auto-fill, minmax(auto, 45px));
        }
    </style>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>

        <main>
            <div id='title'>
                <p class='title'>Visualização de aluno</p>
            </div>

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

                        if($status == 'Procurando Estágio'){
                            $cor = "green";
                        } else{
                            $cor = "red";
                        }
 
                        // echo "<p><strong style='margin-right: 8px;'>Nome: </strong>{$aluno->getNome()} {$aluno->getSobrenome()} <span class='status' style='color: {$cor}; border: 2px solid {$cor}'>{$status}</span></p>";
                        
                        echo "<p class='status' style='color: {$cor}; border: 2px solid {$cor}'>{$status}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Nome:</strong>{$aluno->getNome()} {$aluno->getSobrenome()}</p>";
                        
                        echo "<p><strong style='margin-right: 8px;'>E-mail:</strong>{$aluno->getEmail()}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Curso:</strong>{$curso->getNome()}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Ingressou em</strong>{$aluno->getAnoIngresso()}</p>"; 
                        
                        // remover se precisar
                        
                        echo "<p><strong style='margin-right: 8px;'>Data de nascimento:</strong>{$aluno->getDataNascimento()}</p>";

                        ?>

                        <p class="titulo-dados">Disponibilidade para Estágio</p>

                        <?php 

                        $turno = ucfirst($aluno->getTurnoDisponivel());

                        echo "<p><strong style='margin-right: 8px;'>Modalidade(s):</strong>{$modalidadeTexto}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Turno disponível:</strong>{$turno}</p>"; 

                        $editar = ($_SESSION['idUsuario'] == $_GET['id']) ? "<a href='./../editar/editarCadastroAluno.php'>Editar Cadastro</a>": "";
                        
                        echo "<div id='editPerfil'>{$editar}</div>";
                            
                        ?>
                    </section>
                    <!-- 
                    <section class="dados-usuario">
                        <?php

                        echo "<p><strong style='margin-right: 8px;'>Data de nascimento: </strong> {$aluno->getDataNascimento()}</p>";

                        ?>

                        <p class="titulo-dados">Disponibilidade para Estágio</p>

                        <?php 

                        $turno = ucfirst($aluno->getTurnoDisponivel());

                        echo "<p><strong style='margin-right: 8px;'>Modalidade(s): </strong> {$modalidadeTexto}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Turno disponível: </strong>{$turno}</p>"; 
                            
                        ?>
                    -->
                    </section>
                </section>

                <section class="linha-2">
                    <section class="cidades">
                        <p class="titulo-dados">Cidades para estagiar:</p>
                        
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
                        <p class="titulo-dados">Preferências:</p>
                        
                        <div>
                            <?php

                            foreach($preferencias as $preferencia) {
                                echo "<p>{$preferencia}</p>";
                            }

                            ?>
                        </div>
                    </section>

                    <section class="nao-preferencias">
                        <p class="titulo-dados">Não preferências:</p>
                        
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