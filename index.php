<?php

require_once __DIR__."/vendor/autoload.php";

use App\Classes\Usuario;

$msgError = "";

if(isset($_POST["botao-enviar"])) {
    $usuario = new Usuario($_POST["email"], $_POST["senha"]);

    if($usuario->autenticar()) {
        header("Location: privado.php");
    }

    $msgError = "Dados não cadastrados ou incorretos";

    if(!$usuario->taAtivo() && $usuario->usuarioExiste()) {
        $msgError = "Usuário Inativo";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./src/styles/reset.css">
    <link rel="stylesheet" href="./src/styles/style.css">

    <title>Estagirando</title>

    <style>

    </style>
    
</head>
<body>
    <h1>Bem-vindo ao Estagirando!</h1>
    <div class="container">
        <section class="coluna-1">
            <div class="texto-coluna-1">
                <h3>Com o Estagirando nós te <br> ajudamos a facilitar a sua <br> jornada de estágio.</h3>
                <h3>Junte-se a nós!</h3>
            </div>

            <div class="links-cad">
                <a href="cadastroprofessor.php">Cadastrar-se como professor</a>
                <a href="cadastroaluno.php">Cadastrar-se como aluno</a>
            </div>
        </section>
        
        <section class="coluna-2">
            <form action="index.php" method="post">
                <section>
                    <h2>Login</h2>
                </section>

                <section class="email">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" required>
                </section>

                <section class="senha">
                    <label for="senha">Senha</label>
                    <input type="password" name="senha" id="senha" required>
                </section>

                <section class="links">
                    <a href="recuperarSenha.php">Recuperar Senha</a>
                    <input type="submit" value="Entrar" name="botao-enviar" class="botao">
                </section>    
                
                <section class="error">
                    <p> <?php echo $msgError ?> </p>
                </section>
            </form>
        </section>
    </div>
</body>
</html>