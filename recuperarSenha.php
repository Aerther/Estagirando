<?php
session_start();

    if(!isset($_SESSION['$senha'])){
        $_SESSION['$senha']=0;//Alterar depois
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
</head>
<body>

    <h1>Esqueceu a sua senha?</h1>
    <h2>Redefina a sua senha aqui!</h2>
    <form action='recuperarSenha.php' method='post'>
        <label for='email'>E-mail:</label>
        <input type='email' name='email' id='email' required>
        <input type='submit' name='recuperar' value='Recuperar'>
    </form>
    <p>Nova senha:<?php echo htmlspecialchars($senha); ?></p>
    <a href="index.php"><button>Voltar</button></a>
</body>
</html>