<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use App\Classes\Aluno;
use App\Classes\Professor;
use App\Classes\Preferencia;
$mensagemdeerro = "";
$emailAlunos = Aluno::findAllAlunos();
$emailProfessores = Professor::findAllProfessores();
$contadorEmail = 0;
foreach ($emailAlunos as $emailAluno){
    if ($emailAluno -> getEmail == $_POST['email']){
        $contadorEmail+=1;
    }
}
foreach ($emailProfessores as $emailProfessor){
    if ($emailProfessor -> getEmail == $_POST['email']){
        $contadorEmail+=1;
    }
}


//css deixa a mensagem em vermelho please!
if (isset($_POST['email']) && isset($_POST['confEmail']) && $_POST['email'] != $_POST['confEmail']){
    $mensagemdeerro = "Você não inseriu o mesmo email nos campos 'Email:' e 'Confirme o email:'";
}else if ($contadorEmail<0){
    $mensagemdeerro = "O email inserido já possui um cadastro, você não pode usá-lo novamente";
}else if (isset($_POST['senha']) && isset($_POST['confSenha']) && $_POST['senha'] != $_POST['confSenha']){
    $mensagemdeerro = "Você não inseriu a mesma senha nos campos 'Senha:' e 'Confirme a senha:'";
}else if (isset($_POST['senha']) && isset($_POST['confSenha']) && (strlen($_POST['senha']) < 8 || strlen($_POST['confSenha']) < 8)){
    $mensagemdeerro = "A senha inserida deve conter no mínimo 8 caracteres";
}

$preferencias = Preferencia::findAllPreferencias();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagirando</title>
</head>
<body>
    <h1>Cadastro de Professor</h1>
    <div class="container">
    <p><?php echo $mensagemdeerro;?></p>
    <form action="" method="post">
        <section>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" required>
        </section>
        <section>
            <label for="sobrenome">Sobrenome:</label>
            <input type="text" name="sobrenome" required>
        </section>
        <section>
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </section>
        <section>
            <label for="confEmail">Confirme o email:</label>
            <input type="email" name="confEmail" required>
        </section>
        <section>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
        </section>
        <section>
            <label for="confSenha">Confirme a senha:</label>
            <input type="password" name="confSenha" required>
        </section>
        <section>
            <fieldset>
                <legend>Disponível para orientar?</legend>
                <label><input type="radio" name="disponivel" value="sim">Sim</label>
                <label><input type="radio" name="disponivel" value="nao">Não</label>
            </fieldset>
        </section>
        <section>
            <p>Preferências</p>
            <?php
            foreach ($preferencias as $preferencia){
                echo "<input type='checkbox' >{$preferencia->getDescricao()}";
            }
            ?>

            
        </section>
        <section>
            <p>Não preferências</p>
                        <?php
            foreach ($preferencias as $preferencia){
                echo "<input type='checkbox' >{$preferencia->getDescricao()}";
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