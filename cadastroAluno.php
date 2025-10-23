<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagirando</title>
</head>
<body>
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
            <select id="cidade" name="cidade">
                <!--Cidades-->
            </select>
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
        <section>
            <input type="submit" name="cadastrar" value="Cadastrar">
        </section>
        <!-- Criar a exibição correta das mensagens conforme RF12-->
    </form> 
        <a href="index.php">Cancelar</a>
    </div>
</body>
</html>