<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa Avançada</title>
    <link rel="stylesheet" href="./../../src/styles/styleMenu.css">
</head>
<body>
    <div id="menu">
        <div id='saudacao'>
            <h1>Olá, ...</h1>

        </div>
        <div id='icone'>
            <a href="./../pesquisa/pesquisa.php"><img src="./../../icones/pesquisa.png" alt="" class='iconeMenu' id='pesquisa'></a>
            <a href="./../../solicitacoesOrientacao.php"><img src="./../../icones/solicitacoes.png" alt="" class='iconeMenu' id='solicitacoes'></a>
            <a href="./../editar/editarCadastro.php"><img src="./../../icones/edicao.png" alt="" class='iconeMenu' id='edicao'></a>   
            <a href="./../visualizar/visualizarCadastro.php"><img src="./../../icones/iconProf.png" alt="" class='iconeMenu' id='visualizar'></a>
            <a href="./../../sair.php"><img src="./../../icones/logout.png" alt="" class='iconeMenu' id='logout'></a>
        </div>

        <div id="busca">
            <label for="nome">Pesquise por nome do aluno:</label>
            <input type="text" id="txtnome" placeholder="Pesquisar nome">

            <label for="sobrenome">Pesquise por sobrenome:</label>
            <input type="text" id="txtsobrenome" placeholder="Pesquisar sobrenome">

            <label for="email">Pesquisar por email:</label>
            <input type="text" id=txtemail placeholder="Pesquisar email"> 
        
            <label for="curso">Pesquisar por curso:</label>
            <select id="curso" name="curso">
                <option value="1">Informática</option>
                <option value="2" <?php echo $opcoes[2]; ?>>Administração</option>
                <option value="3" <?php echo $opcoes[3]; ?>>Química</option>
                <option value="4" <?php echo $opcoes[4]; ?>>Meio Ambiente</option>
        </div>
        
    </div>
    
</body>
</html>
