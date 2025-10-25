<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Usuario;

$mensagem = "";
$novaSenha = "";

if (isset($_POST["recuperar"]) ) {
    if ($_POST["recuperar"]=="Recuperar") {
        if($_POST["email"]!=""){
            $usuario = new Usuario($_POST["email"], "");

            if (!$usuario->usuarioExiste()) {
                $mensagem = "Email não cadastrado ou incorreto";
            } else {
                $usuario->criarNovaSenha();

                $novaSenha = $usuario->getSenha();

                $mensagem = "Senha redefinida com sucesso!";
            }

        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagirando</title>
    <link rel="stylesheet" href="src/styles/reset.css">
</head>
<body>
    <div class="container">
        <div class='header'>
            <h1>Esqueceu a sua senha?</h1>
        <h2>Redefina a sua senha aqui!</h2>
        </div>

        <form action="./recuperarSenha.php" method="post">
        <div class='content'>
            <section>
                <label for="email">E-mail:</label> <br>
                <input type="email" name="email" id="email" class='aba' required>
            </section>

            <section>
                <input type="submit" name="recuperar" value="Recuperar" class='save'>
            </section>

            <section>
                <?php if($mensagem) echo $mensagem; ?>
            </section>

            <section class='novasenha'>
                <?php if($novaSenha) echo "Nova Senha: " . $novaSenha; ?>oi
            </section>
        </div>
        </form>
    </div>
    <div class='btnreturn'>
            <a href="index.php" class='return'>Voltar</a>
    </div>
</body>
</html>