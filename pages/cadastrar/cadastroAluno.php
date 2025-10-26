<?php
require_once __DIR__."/vendor/autoload.php";

use App\Classes\Aluno;

$msgError = "";
if (isset($_POST['cadastrar'])){
    if ($_POST['senha'] != $_POST['confSenha']) {
        $msgError = "As senhas digitadas são diferentes. Por favor, confirme sua senha corretamente.";
    }
    elseif ($_POST['email'] != $_POST['confEmail']) {
        $msgError = "Os endereços de e-mail digitados são diferentes. Por favor, confirme seu e-mail corretamente.";
    }
    elseif (strlen($_POST['senha']) < 8) {
    $msgError = "A senha deve ter no mínimo 8 caracteres.";
    }
    else{
        $aluno = new Aluno($_POST["email"], $_POST["senha"]);
        $aluno->salvarUsuario(
        $_POST['nome'],
        $_POST['sobrenome'],
        [], // preferencias — preencher depois se houver
        [], // noPreferencias — idem
        $_POST["ano"],
        $_POST['cidadeEstagiar'],
        $_POST['turno'],
        $_POST['situacao'],
        (int) $_POST['curso'] // supondo que ID_Turma vem do select "curso"
        );
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Estagirando</title>
    
    <link rel= "stylesheet" href="./../../src/styles/styleCadastroaluno.css">
</head>
<body id="body">
    <h1>Cadastro de Aluno</h1>
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
            <label for="ano">Ano de Ingresso:</label>
            <input type="text" name="ano" maxlength="4" pattern="\d+" required>
        </section>

        <section>
            <label for="curso">Selecione o curso:</label>
                <select id="curso" name="curso">
                <!--Puxar do banco quais os cursos disponiveis-->
                </select>
        </section>

        <section>
            <label for="cidede">Cidade para Estagiar:</label>
            <input type="text" name="cidadeEstagiar" required>
        </section>

        <section>
            <label for="turno">Turno disponível:</label>
                <select id="turno" name="turno">
                    <option value="manha">Manhã</option>
                    <option value="tarde">Tarde</option>
            </select>
        </section>

        <section>
            <label for="modalidade">Modalidade:</label>
                <select id="modalidade" name="modalidade">
                    <option value="presencial">Presencial</option>
                    <option value="remoto">Remoto</option>
                    <option value="hibrido">Híbrido</option>
            </select>
        </section>

        <section>
            <label for="situacao">Situação Atual:</label>
                <select id="situacao" name="situacao">
                    <option value="procurando">Procurando Estágio</option>
                    <option value="estagiando">Estagiando</option>
                    <option value="sla">Sla</option>
            </select>
        </section>

        <section>
            <input type="submit" name="cadastrar" value="Cadastrar">
            <a href="index.php">Cancelar</a>
        </section>
        <!-- Criar a exibição correta das mensagens conforme RF12-->
    </form> 
        
</div>
</body>
</html>
