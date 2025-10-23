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
            <label for="confEmail">Confirme o Email:</label>
            <input type="email" name="confEmail" required>
        </section>
        <section>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
        </section>
        <section>
            <label for="confSenha">Confirme a Senha:</label>
            <input type="password" name="confSenha" required>
        </section>
        <section>
            <label for="pref">Preferências</label>
            
        </section>
        <section>
            <label for="nPref">Não Preferências</label>
            <!--Puxar do banco quais as preferencias e não preferencias-->
            
        </section>
        <section>
            <fieldset>
                <legend>Disponível para orientar?</legend>
                <label><input type="radio" name="disponivel" value="sim">Sim</label>
                <label><input type="radio" name="disponivel" value="nao">Não</label>
            </fieldset>
        </section>
        <section>
            <label for="pref">Preferências</label>
            
        </section>
        <section>
            <label for="nPref">Não Preferências</label>
            <!--Puxar do banco quais as preferencias e não preferencias-->
            
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