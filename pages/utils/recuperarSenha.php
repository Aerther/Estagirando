<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Usuario;

$mensagem = "";
$novaSenha = "";

if (isset($_POST["recuperar"]) ) {
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

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styles2.css">

    <script src="./src/js/main.js" defer></script>
    <title>Estagirando</title>
</head>
<body>
    <div class="container">
        <h1 id="titulo1">Esqueceu a sua senha?</h1>
        <h2 id="subtitulo">Redefina a sua senha aqui!</h2>

        <form action="./recuperarSenha.php" method="post">
            <section>
                <label for="email">Informe seu E-mail:</label>
                <input type="email" name="email" id="email" required>
            </section>

            <section>
                <?php if($mensagem) echo $mensagem; ?>
            </section>

            <section>
                <?php if($novaSenha) echo "Nova Senha: " . $novaSenha; ?>
            </section>

            <section>
                
                <input type="submit" name="recuperar" value="Recuperar" id="btn">
            </section>

        </form>
    </div>
    <a href="index.php">Voltar</a>

</body>
</html>