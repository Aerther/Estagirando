<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

$mensagemErro = "";

if(isset($_POST["cadastrar"])) {
    $usuario = new Professor($_POST["email"], $_POST["senha"]);

    if(!$usuario->usuarioExiste()) {
        if($_POST["senha"] != $_POST["confSenha"]) {
            $mensagemErro = "Os campos de senha estão diferentes";

        } else if($_POST["email"] != $_POST["confEmail"]) {
            $mensagemErro = "Os campos de email estão diferentes";

        } else if(strlen($_POST["senha"]) < 8) {
            $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

        } else {
            $usuario->salvarProfessor();
            
            header();
        }
    } else {
        $mensagemErro = "Email já cadastrado";
    }
}

$preferenciasSelecionadas = [];
$naoPreferenciasSelecionadas = [];

foreach ($preferencias as $preferencia) {
    $nomePref = 'p' . $preferencia->getDescricao();
    $nomeNaoPref = 'np' . $preferencia->getDescricao();
    if (isset($_POST[$nomePref])) {
        $preferenciasSelecionadas[$preferencia->getIdPreferencia()] = $preferencia->getDescricao();
    }
    if (isset($_POST[$nomeNaoPref])) {
        $naoPreferenciasSelecionadas[$preferencia->getIdPreferencia()] = $preferencia->getDescricao();
    }
}

$preferencias = Preferencia::findAllPreferencias();

if($contadorpEnp > 0) {
    $mensagemErro = "Você não pode selecionar '$pOUnp' como preferência e não preferência, selecione cada opção em apenas um campo!";
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagirando</title>
    <link rel="stylesheet" href="../../src/styles/styleCadastro.css">

</head>
<body>
    <h1>Cadastro de Professor</h1>
    <div class="container">
    <p><?php echo $mensagemErro;?></p>
    <form action="./cadastroProfessor.php" method="post">
        <section>
            <label for="nome">Nome:</label>
            <input type="text" name="nome"  value="<?php if (isset($_POST['nome'])) echo htmlspecialchars($_POST['nome']); ?>" required>
        </section>

        <section>
            <label for="sobrenome">Sobrenome:</label>
            <input type="text" name="sobrenome"  value="<?php if (isset($_POST['sobrenome'])) echo htmlspecialchars($_POST['sobrenome']); ?>" required>
        </section>

        <section>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required>
        </section>

        <section>
            <label for="confEmail">Confirme o email:</label>
            <input type="email" name="confEmail" value="<?php if (isset($_POST['confEmail'])) echo htmlspecialchars($_POST['confEmail']); ?>" required>
        </section>

        <section>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" value="<?php if (isset($_POST['senha'])) echo htmlspecialchars($_POST['senha']); ?>" required>
        </section>

        <section>
            <label for="confSenha">Confirme a senha:</label>
            <input type="password" name="confSenha" value="<?php if (isset($_POST['confSenha'])) echo htmlspecialchars($_POST['confSenha']); ?>" required>
        </section>

        <section>
            <fieldset>
                <legend>Disponível para orientar?</legend>
                <label><input type="radio" name="disponivel" value="sim"> Sim</label>
                <label><input type="radio" name="disponivel" value="nao"> Não</label>
                <label><input type="radio" name="disponivel" value="sim"
                 <?php if(isset($_POST['disponivel']) && $_POST['disponivel'] == 'sim') echo 'checked'; ?> >Sim</label>
                <label><input type="radio" name="disponivel" value="nao"
                 <?php if(isset($_POST['disponivel']) && $_POST['disponivel'] == 'nao') echo 'checked'; ?>>Não</label>
            </fieldset>
        </section>

        <section>
            <p>Preferências</p>
            <?php
            foreach ($preferencias as $preferencia){
                echo "<input type='checkbox' value={$preferencia->getIdPreferencia()}>{$preferencia->getDescricao()}";
                $nomeCampo = 'p' . $preferencia->getDescricao();
                $checked = isset($_POST[$nomeCampo]) ? 'checked' : '';

                 echo "<input type='checkbox' name='{$nomeCampo}' value='sim' $checked>{$preferencia->getDescricao()}";
            }

            ?>
        </section>

        <section>
            <p>Não preferências</p>
                        <?php
            foreach ($preferencias as $preferencia){
                echo "<input type='checkbox' value={$preferencia->getIdPreferencia()}>{$preferencia->getDescricao()}";
               $nomeCampo = 'np' . $preferencia->getDescricao();
                $checked = isset($_POST[$nomeCampo]) ? 'checked' : '';

                 echo "<input type='checkbox' name='{$nomeCampo}' value='sim' $checked>{$preferencia->getDescricao()}";
            }
            ?>
        </section>

        <section>
            <input type="submit" name="cadastrar" value="Cadastrar">
        </section>
        <!-- Criar a exibição correta das mensagens conforme RF13-->
    </form> 
        <a href="index.php">Cancelar</a>
    </div>
</body>
</html>