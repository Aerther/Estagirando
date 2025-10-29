<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagirando</title>
</head>
<body>
    <div id="menu">
        <h1>Bem Vindo Aluno!</h1>
        <a href="./../pesquisa/pesquisa.php"><img src="../../icones/pesquisa.png" alt="pesquisa" title="Pesquisar"></a>
        <a href="./../../solicitacoesOrientacao.php"><img src="../../icones/solicitacoes.png" alt="solicitacoes" title="Solicitações"></a>
        <a href="./../visualizar/visualizarCadastro.php">Visualizar Cadastro</a>
        <a href="./../editar/editarCadastroAluno.php"><img src="../../icones/edicao.png" alt="edicao" title="Editar"></a>
        <a href="./../../sair.php"><img src="../../icones/logout.png" alt="sair" title="Sair"></a>
    </div>
    
    <div id='edicao'>
        <form action="editarCadastroAluno.php" method="post">
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
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required>
        </section>

        <section>
            <label for="curso">Selecione o curso:</label>
                <select id="curso" name="curso">
                <!--Puxar do banco quais os cursos disponiveis-->
                </select>
        </section>
        
        <section>
            <label for="ano">Ano de Ingresso:</label>
            <input type="text" name="ano" maxlength="4" pattern="\d+" required>
        </section>
        
        <section>
            <label for="turno">Turno disponível:</label>
                <select id="turno" name="turno">
                    <option value="manha">Manhã</option>
                    <option value="tarde">Tarde</option>
            </select>
        </section>
        
        <section>
            <label for="situacao">Situação Atual:</label>
                <select id="situacao" name="situacao">
                    <option value="procurando">Procurando Estágio</option>
                    <option value="estagiando">Estagiando</option>
                    <option value="sla">Ocupado</option>
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
            <label for="cidede">Cidade para Estagiar:</label>
            <input type="text" name="cidadeEstagiar" required>
        </section>

        <section>
            <label for="pref">Preferências</label>
            
        </section>
            <!--aqui deve conter a listagem das preferências do banco 
             com as opções do aluno já marcadas-->
        <section>
            <label for="nPref">Não Preferências</label>
            
        </section>

        <section>
            <input type="submit" name="cadastrar" value="Cadastrar">
            <a href="./../home/homeAluno.php">Cancelar</a>
        </section>
        </form>

    </div>
    
</body>
</html>