<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

$mensagemErro = "";

if(isset($_POST["cadastrar"])) {
    $usuario = new Professor($_POST["email"], $_POST["senha"]);

    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    if(!$usuario->usuarioExiste()) {
        if($_POST["senha"] != $_POST["confSenha"]) {
            $mensagemErro = "Os campos de senha estão diferentes";

        } else if($_POST["email"] != $_POST["confEmail"]) {
            $mensagemErro = "Os campos de email estão diferentes";

        } else if(strlen($_POST["senha"]) < 8) {
            $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

        } else if(!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        } else {
            $usuario->salvarProfessor(
                $_POST["nome"],
                $_POST["sobrenome"],
                $preferencias,
                $naoPreferencias,
                $_POST["disponivel"]
            );
            $mensagemErro = "<div id='msg'>
            <p id='erro'>Cadastro realizado com sucesso!</p>
            <a href='./../../index.php' id='voltar'>Voltar</a></div>";
            //header("Location: ./../../index.php");
        }
    } else {
        $mensagemErro = "Email já cadastrado";
    }
}

$preferencias = Preferencia::findAllPreferencias();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Estagirando</title>

    <link rel="stylesheet" href="./../../src/styles/styleCadastroProf.css">
</head>
<body>
    <h1>Cadastro de Professor</h1>
    <div class="container">
    <p id="erro"><?php echo $mensagemErro;?></p>

    <form action="./cadastroProfessor.php" method="post">
        <div class="dado">
            <section>
                <label for="nome">Nome:</label>
                <input type="text" name="nome"  value="<?php if (isset($_POST['nome'])) echo htmlspecialchars($_POST['nome']); ?>" required>
            </section>
            <section>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required>
            </section>
            <section>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" value="<?php if (isset($_POST['senha'])) echo htmlspecialchars($_POST['senha']); ?>" required>
            </section>
        </div>

        <div class="dado">
            <section>
                <label for="sobrenome">Sobrenome:</label>
                <input type="text" name="sobrenome"  value="<?php if (isset($_POST['sobrenome'])) echo htmlspecialchars($_POST['sobrenome']); ?>" required>
            </section>
        
            <section>
                <label for="confEmail">Confirme o email:</label>
                <input type="email" name="confEmail" value="<?php if (isset($_POST['confEmail'])) echo htmlspecialchars($_POST['confEmail']); ?>" required>
            </section>
            <section>
                <label for="confSenha">Confirme a senha:</label>
                <input type="password" name="confSenha" value="<?php if (isset($_POST['confSenha'])) echo htmlspecialchars($_POST['confSenha']); ?>" required>
            </section>
        </div>

        <div class="preferencia">
        
            <section>
                <p>Preferências</p>

                <?php 
                
                foreach($preferencias as $preferencia) {
                    echo "<label><input type='checkbox' name='preferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                }

                ?>
            </section>

            <section>
                <p>Não preferências</p>

                <?php 
                
                foreach($preferencias as $preferencia) {
                    echo "<label><input type='checkbox' name='naoPreferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                }

                ?>
            </section>

        </div>

        <section id="radios">
            <label for="">Disponível para orientar?</label>

            <div id="disponibilidade">
                <label><input type="radio" name="disponivel" value="sim" required>Sim</label>
                <label><input type="radio" name="disponivel" value="nao" required>Não</label>
            </div>
        </section>
        <div id="btn">  
        
                <input type="submit" name="cadastrar" value="Cadastrar">
                <a href="./../../index.php" id='cancelar'>Cancelar</a>
           
        </div>
    </form> 
    </div>
</body>
</html>