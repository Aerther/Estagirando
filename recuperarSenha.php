<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Usuario;

$mensagem = "";
$novaSenha = "";

if (isset($_POST["recuperar"])) {
    $usuario = new Usuario($_POST["email"], "");

    if (!$usuario->usuarioExiste()) {
        $mensagem = "Email não cadastrado ou incorreto";
    } else {
        $usuario->criarNovaSenha();

        $novaSenha = $usuario->getSenha();

        $mensagem = "Senha redefinida com sucesso!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagirando</title>
</head>
<body>
    <div class="container">
        <h1>Esqueceu a sua senha?</h1>
        <h2>Redefina a sua senha aqui!</h2>

        <form action="./recuperarSenha.php" method="post">
            <section>
                <label for="email">Informe seu E-mail:</label>
                <input type="email" name="email" id="email" required>
            </section>

            <section>
                <a href="index.php">Voltar</a>
                <input type="submit" name="recuperar" value="Recuperar">
            </section>

            <section>
                <?php if($mensagem) echo $mensagem; ?>
            </section>

            <section>
                <?php if($novaSenha) echo "Nova Senha: " . $novaSenha; ?>
            </section>
        </form>
    </div>
</body>
</html>