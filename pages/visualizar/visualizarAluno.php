<?php

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Aluno;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_GET["id"])) header("Location: ./../../privado.php");

$aluno = Aluno::findAluno($_GET["id"]);

$foto = $aluno->getFoto();
$curso = $aluno->getCurso();
$preferencias = $aluno->getPreferencias();
$naoPreferencias = $aluno->getNaoPreferencias();

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
        <header>
            <section class="texto-inicial">
                <?php echo "<h2>Bem-vindo {$_SESSION['tipoUsuario']}!</h2>"; ?>
            </section>

            <section class="icones">
                <a href="./../pesquisa/pesquisa.php">
                    <img src="./../../icones/pesquisa.png" alt="Icone" class='iconeMenu' id='pesquisa'>
                </a>

                <a href="./../../solicitacoesOrientacao.php">
                    <img src="./../../icones/solicitacoes.png" alt="Icone" class='iconeMenu' id='solicitacoes'>
                </a>

                <a href="./../../pages/editar/<?php echo $linkEditarCadastro; ?>">
                    <img src="./../../icones/edicao.png" alt="Icone" class='iconeMenu' id='edicao'>
                </a>   

                <a href="./../../pages/visualizar/<?php echo $linkVisualizarCadastro."?id={$_SESSION["idUsuario"]}"; ?>">
                    <img src="./../../icones/<?php echo $linkIcone; ?>" alt="Icone" class='iconeMenu' id='visualizar'>
                </a>

                <a href="./../../sair.php">
                    <img src="./../../icones/logout.png" alt="Icone" class='iconeMenu' id='logout'>
                </a>
            </section>
        </header>

        <main>
            <div class="content">
                <section class="linha-1">
                    <section class="imagem">
                        <figure>
                            <?php echo "<img src='./../../{$foto->getLinkFoto()}' alt='Foto do Aluno' />"; ?>
                        </figure>
                    </section>

                    <section class="dados-usuario">
                        <?php echo "<p>Nome: {$aluno->getNome()} <p class='status'>{$aluno->getStatusEstagio()}</p> </p>"; ?>
                        <?php echo "<p>Email: {$aluno->getEmail()}</p>"; ?>
                        <?php echo "<p>Curso: {$curso->getNome()}</p>"; ?>
                        <?php echo "<p>Ingressou em {$aluno->getAnoIngresso()}</p>"; ?>
                    </section>
                </section>

                <section class="linha-2">
                    <section class="disponibilidade">
                        <p class="titulo-dados">Disponibilidade do Estágio</p>
                        <?php echo "<p>Cidade: {$aluno->getCidadeEstagio()}</p>"; ?>
                        <?php echo "<p>Modalidade: {$aluno->getModalidade()}</p>"; ?>
                        <?php echo "<p>Turno: {$aluno->getTurnoDisponivel()}</p>"; ?>
                    </section>

                    <section class="preferencias">
                        <p class="titulo-dados">Preferências</p>
                        <?php

                        foreach($preferencias as $preferencia) {
                            echo "<p>{$preferencia}</p>";
                        }

                        ?>
                    </section>

                    <section class="nao-preferencias">
                        <p class="titulo-dados">Não Preferências</p>
                        <?php

                        foreach($naoPreferencias as $preferencia) {
                            echo "<p>{$preferencia}</p>";
                        }

                        ?>
                    </section>
                </section>
            </div>
        </main>
    </div>
</body>
</html>