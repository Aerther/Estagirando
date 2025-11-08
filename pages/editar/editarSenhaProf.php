<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "professor") header("Location: ./../../privado.php");

$mensagemErro = "";

$professor = Professor::findProfessor($_SESSION["idUsuario"]);
$email = $professor->getEmail();
if(isset($_POST['salvar'])) {
    $novaSenha = isset($_POST["novaSenha"]) ? $_POST["novaSenha"] : "";

    
    $usuario = new Professor($email, $novaSenha);

    
    if($_POST["novaSenha"] != $_POST["confSenha"]) {
        $mensagemErro = "Os campos de senha estão diferentes";
    }else if(strlen($novaSenha) < 8 && !empty($novaSenha)) {
        $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

    }else {
        $usuario->atualizarSenha($novaSenha);
        header("Location: editarCadastroProfessor.php");
        session_start();
        $_SESSION['senhaEditada']=true;
    }
        
    
}



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleEditarSenha.css">

    <title>Edição de Senha do Professor</title>
</head>
<body>
    <div class="container">
        <header>
            <section class="texto-inicial">
                <h2>Bem-vindo Professor!</h2>
            </section>

            <section class="icones">
                <a href="./../pesquisa/pesquisa.php" title="Pesquisa avançada">
                    <img src="./../../icones/pesquisa.png" alt="Icone" class='iconeMenu' id='pesquisa'>
                </a>

                <a href="./../../solicitacoesOrientacao.php" title="Solicitações de orientação">
                    <img src="./../../icones/solicitacoes.png" alt="Icone" class='iconeMenu' id='solicitacoes'>
                </a>

                <a href="./../visualizar/visualizarCadastro.php" title="Visualizar cadastro">
                    <img src="./../../icones/iconProf.png" alt="Icone" class='iconeMenu' id='visualizar'>
                </a>

                <a href="./../../sair.php" title="Logout">
                    <img src="./../../icones/logout.png" alt="Icone" class='iconeMenu' id='logout'>
                </a>
            </section>
        </header>
        
        <main>

        
            <form action="./editarSenhaProf.php" method="post">
                <h1 id='titleEdit'>Edite sua senha!</h1>
                <section class="dados">
                
                    <section class="dados-senha">
                        <section>
                            <label for="novaSenha">Nova senha:</label>
                            <input type="password" name="novaSenha"  required>
                        </section>

                        <section>
                            <label for="confSenha">Confirme a senha:</label>
                            <input type="password" name="confSenha"  required>
                        </section>

                    </section>

                </section>

                <section class="links">
                    <?php echo "<p class='erro'>{$mensagemErro}</p>"; ?>

                    <div>
                        <input class='link' type="submit" name="salvar" value="Salvar">
                        <a class='link' href="editarCadastroProfessor.php">Cancelar</a>
                    </div>
                </section>
            </form>
        </main>
    </div>
</body>
</html>