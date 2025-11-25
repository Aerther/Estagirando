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

$modalidades = $aluno->getModalidade();
$modalidade = "";
if($modalidades == "presencial,online,hibrido"){
    $modalidade = "Presencial, online ou híbrido";
}else if ($modalidades == "presencial,online"){
    $modalidade = "Presencial ou online";
}else if ($modalidades == "presencial,hibrido"){
    $modalidade = "Presencial ou híbrido";
}else if ($modalidades == "online,hibrido"){
    $modalidade = "Online ou Híbrido";
}else if ($modalidades == "presencial"){
    $modalidade = "Presencial";
}else if ($modalidades == "online"){
    $modalidade = "Online";
}else if ($modalidades == "hibrido"){
    $modalidade = "Híbrido";
}
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

                        <div id=editPerfil>
                            <?php if($_SESSION["idUsuario"] == $_GET["id"]) echo "<a href='./../editar/editarCadastroAluno.php'>Editar Cadastro</a>"; ?>
                        </div>
                    </section>

                    <section class="dados-usuario">
                        <?php 

                        $status = ucwords($aluno->getStatusEstagio());

                        if($status == 'Procurando Estágio'){
                            $cor = "green";
                        } else{
                            $cor = "red";
                        }
 
                        echo "<p><strong style='margin-right: 8px;'>Nome:</strong> {$aluno->getNome()} {$aluno->getSobrenome()} <span class='status' style='color: {$cor}; border: 2px solid {$cor}'>{$status}</span> ";
                        echo "<p><strong style='margin-right: 8px;'>Email: </strong> {$aluno->getEmail()}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Curso: </strong> {$curso->getNome()}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Ingressou em </strong>{$aluno->getAnoIngresso()}</p>"; 
                        
                        ?>
                    </section>

                    <section class="dados-usuario">
                        <?php

                        echo "<p><strong style='margin-right: 8px;'>Data de Nascimento: </strong> {$aluno->getDataNascimento()}</p>";

                        ?>

                        <p class="titulo-dados">Disponibilidade para Estágio</p>

                        <?php 

                        echo "<p><strong style='margin-right: 8px;'>Modalidade(s): </strong> {$modalidade}</p>";
                        echo "<p><strong style='margin-right: 8px;'>Turno disponível: </strong>{$aluno->getTurnoDisponivel()}</p>"; 
                            
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