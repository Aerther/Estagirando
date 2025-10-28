<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edição de Cadastro Professor</title>
</head>
<body>
    <div id="menu">
        <a href="./../pesquisa/pesquisa.php">Pesquisa</a>
        <a href="./../../solicitacoesOrientacao.php">Solicitações</a>
        <a href="./../visualizar/visualizarCadastro.php">Próprio Cadastro</a>
        <a href="./../editar/editarCadastro.php">Edição de Cadastro</a>
        <a href="./../../sair.php">Sair</a>
    </div>
    
    <div id='edicao'>
        <form action="editarCadastroProfessor.php" method="post">

            <label for="nomeProf">Nome: </label>
            <input type="text" name="nome" id="nomeProf">

            <label for="sobrenomeProf">Sobrenome: </label>
            <input type="text" name="sobrenome" id="sobrenomeProf">

            <label for="emailProf">E-mail: </label>
            <input type="text" name="email" id="emailProf">

            <label for="senhaProf">Senha: </label>
            <input type="password" name="senha" id="senhaProf">

            <label for="">Disponibilidade para orientar?</label>
            <input type="radio" name="disponibilidade" id="disponibilidade1">
            <label for="disponibilidade1">Sim</label>
            <input type="radio" name="disponibilidade" id="disponibilidade2">
            <label for="disponibilidade2">Não</label>

            <label for="preferencias">Preferências</label>

            <label for="naoPreferencias"> Não preferências</label>
             <!--aqui deve conter a listagem das preferências do banco 
             com as opções do professor já marcadas-->

             <input type="submit" value="Salvar">
             <input type="submit" value="Cancelar">
        </form>

    </div>
    
</body>
</html>