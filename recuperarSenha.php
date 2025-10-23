<?php
require_once __DIR__."/vendor/autoload.php";
use App\Classes\Usuario;

$mensagem = "";
$novaSenha = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["recuperar"])) {
    $email = trim($_POST["email"] ?? "");//Verifica se o email foi recebido corretamente
    if (empty($email)) {
        $mensagem = "Por favor, informe um e-mail válido.";
    } else {
        //Verifica se o email esta cadastrado no banco
        $conexao = new MySQL();
        $sql = "SELECT ID_Usuario FROM Usuario WHERE Email = ?";
        $resultado = $conexao->search($sql, "s", [$email]);

        if (empty($resultado)) {
            $mensagem = "Email Invalido.";
        } else {
            //Gera a nova senha criptografa e envia para o banco
            $novaSenha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
            $senhaCriptografada = password_hash($novaSenha, PASSWORD_BCRYPT);
            $sql = "UPDATE Usuario SET Senha = ? WHERE Email = ?";
            $conexao->execute($sql, "ss", [$senhaCriptografada, $email]);

            $mensagem = "Senha redefinida com sucesso!";
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
</head>
<body>
    <h1>Esqueceu a sua senha?</h1>
    <h2>Redefina a sua senha aqui!</h2>

    <div class="container">
    <form method="post">
        <section>
            <label for="email">Informe seu E-mail:</label>
            <input type="email" name="email" id="email" required>
        </section>
        <section>
            <input type="submit" name="recuperar" value="Recuperar">
        </section>
    </form>

    <?php if ($mensagem): ?>
        <p><strong><?php echo htmlspecialchars($mensagem); ?></strong></p>
    <?php endif; ?>

    <?php if ($novaSenha): ?>
        <p>Nova senha: <strong><?php echo htmlspecialchars($novaSenha); ?></strong></p>
    <?php endif; ?>

    <a href="index.php"><button>Voltar</button></a>
</div>
</body>
</html>