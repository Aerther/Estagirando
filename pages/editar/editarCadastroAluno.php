<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estagirando</title>
    <link rel= "stylesheet" href="./../../src/styles/styleEdicaoAluno.css">

</head>
<body>
    <div id="menu">
        <div id='saudacao'>
            <h1>Bem vindo Aluno!</h1>

        </div>
        <div id='icone'>
            <a href="./../pesquisa/pesquisa.php"><img src="./../../icones/pesquisa.png" alt="" class='iconeMenu' id='pesquisa'></a>
            <a href="./../../solicitacoesOrientacao.php"><img src="./../../icones/solicitacoes.png" alt="" class='iconeMenu' id='solicitacoes'></a>
            <a href="./../editar/editarCadastro.php"><img src="./../../icones/edicao.png" alt="" class='iconeMenu' id='edicao'></a>   
            <a href="./../visualizar/visualizarCadastro.php"><img src="./../../icones/iconProf.png" alt="" class='iconeMenu' id='visualizar'></a>
            <a href="./../../sair.php"><img src="./../../icones/logout.png" alt="" class='iconeMenu' id='logout'></a>

        </div>
        
    </div>
    
    <div id="edicao">
        <form action="editarCadastroAluno.php" method="post">
            <div class='dados'>
                <section>
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" required>
                </section>

                <section>
                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" name="sobrenome" required>
                </section>
            </div>
        

            <div class='dados'>
                <section>
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </section>

                <section>
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" required>
                </section>
            </div>

            <div class='dados'>
                <section>
                    <label for="curso">Curso:</label>
                        <select id="curso" name="curso">
                        <!--Puxar do banco quais os cursos disponiveis-->
                        </select>
                </section>
                
                <section>
                    <label for="ano">Ingresso em:</label>
                    <input type="text" name="ano" maxlength="4" pattern="\d+" required>
                </section>
            </div>
        
            <div clas='dados'>

            
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
            </div>

            <div class='dados'>

            
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
            </div>

            <div class='dados'>
                <section>
                    <label for="pref">Preferências</label>
                    
                </section>
                    <!--aqui deve conter a listagem das preferências do banco 
                    com as opções do aluno já marcadas-->
                <section>
                    <label for="nPref">Não Preferências</label>
                    
                </section>
            </div>
        

        <div id='btn'>
            <section>
                <input type="submit" name="cadastrar" value="Salvar">
                <a href="./../home/homeAluno.php">Cancelar</a>
            </section>
        </div>

        </form>

    </div>
    
</body>
</html>