<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "aluno") header("Location: ./../../privado.php");

$mensagemErro = "";

$aluno = Aluno::findAluno($_SESSION["idUsuario"]);
$email = $aluno->getEmail();
if(isset($_POST['salvar'])) {
    $novaSenha = isset($_POST["novaSenha"]) ? $_POST["novaSenha"] : "";

    
    $usuario = new Aluno($email, $novaSenha);

    
    if($_POST["novaSenha"] != $_POST["confSenha"]) {
        $mensagemErro = "Os campos de senha estão diferentes";
    }else if(strlen($novaSenha) < 8 && !empty($novaSenha)) {
        $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

    }else {
        $usuario->atualizarSenha($novaSenha);
        header("Location: editarCadastroAluno.php");
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

    <title>Edição de Senha do Aluno</title>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>
        <div id='title'>
            <?php
                echo "<p class='title'>Edição de senha</p>"
            ?>
        </div>
        <main>

        
            <form action="./editarSenhaAluno.php" method="post">
                
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
                        <a class='link' href="editarCadastroAluno.php">Cancelar</a>
                    </div>
                </section>
            </form>
        </main>
    </div>
</body>
</html>