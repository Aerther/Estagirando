<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__."/vendor/autoload.php";

session_start();

// Settagem dos links
$linkEditarCadastro = $_SESSION["tipoUsuario"] == "Aluno" ? "editarCadastroAluno.php" : "editarCadastroProfessor.php";
$linkIcone = $_SESSION["tipoUsuario"] == "Aluno" ? "iconAluno.png" : "iconProf.png";

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./src/styles/reset.css">
    <link rel="stylesheet" href="./src/styles/styleEditar.css">

    <title>Home</title>
</head>
<body>
    <div class="container">
        <header>
            <section class="texto-inicial">
                <?php echo "<h2>Bem-vindo {$_SESSION['tipoUsuario']}!</h2>"; ?>
            </section>

            <section class="icones">
                <a href="./pesquisa/pesquisa.php">
                    <img src="./icones/pesquisa.png" alt="Icone" class='iconeMenu' id='pesquisa'>
                </a>

                <a href="./solicitacoesOrientacao.php">
                    <img src="./icones/solicitacoes.png" alt="Icone" class='iconeMenu' id='solicitacoes'>
                </a>

                <a href="./pages/editar/<?php echo $linkEditarCadastro; ?>">
                    <img src="./icones/edicao.png" alt="Icone" class='iconeMenu' id='edicao'>
                </a>   

                <a href="./visualizar/visualizarCadastro.php">
                    <img src="./icones/<?php echo $linkIcone; ?>" alt="Icone" class='iconeMenu' id='visualizar'>
                </a>

                <a href="./sair.php">
                    <img src="./icones/logout.png" alt="Icone" class='iconeMenu' id='logout'>
                </a>
            </section>
        </header>

        <main>
            <p>Nada por aqui ainda, aguarde futuras atualizações</p>
        </main>
    </div>
</body>
</html>