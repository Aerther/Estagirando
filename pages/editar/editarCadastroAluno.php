<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;
use App\Classes\Preferencia;
use App\Classes\Curso;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

$mensagemErro = "";

if(isset($_POST['cadastrar'])) {
    $usuario = new Aluno($_POST["email"], $_POST["senha"]);

    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    if(!$usuario->usuarioExiste()) {
        if(strlen($_POST["senha"]) < 8) {
            $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

        } else if(!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        } else {
            $usuario->atualizarAluno(
                $_POST["nome"],
                $_POST["sobrenome"],
                $_POST["email"],
                $preferencias,
                $naoPreferencias,
                $_POST["ano"],
                $_POST["cidadeEstagiar"],
                $_POST["turno"],
                $_POST["situacao"],
                $_POST["modalidade"],
                $_POST["curso"]
            );

            header("Location: ./../../privado.php");
        }
    } else {
        $mensagemErro = "Email já cadastrado";
    }
}

$preferencias = Preferencia::findAllPreferencias();
$cursos = Curso::findAllCursos();

?>

<!DOCTYPE html>
<html lang="pt-BR">
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
        <p id="erro"><?php echo $mensagemErro;?></p>

        <form action="editarCadastroAluno.php" method="post">
            <div class='dados'>
                <section>
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome" value="<?php if (isset($_POST['nome'])) echo htmlspecialchars($_POST['nome']); ?>" required>
                </section>

                <section>
                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" name="sobrenome" value="<?php if (isset($_POST['sobrenome'])) echo htmlspecialchars($_POST['sobrenome']); ?>" required>
                </section>
            </div>

            <div class='dados'>
                <section>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required>
                </section>

                <section>
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" value="<?php if (isset($_POST['senha'])) echo htmlspecialchars($_POST['senha']); ?>" required>
                </section>
            </div>

            <div class='dados'>
                <section>
                    <label for="curso">Curso:</label>
                    <select id="curso" name="curso">
                        <?php

                        $cursoSelecionado = isset($_POST["curso"]) ? $_POST["curso"] : -1;

                        foreach($cursos as $curso) {
                            $selected = $curso->getIdCurso() == $cursoSelecionado ? 'selected' : '';

                            echo "<option value={$curso->getIdCurso()} {$selected}>{$curso->getNome()}</option>";
                        }

                        ?>
                    </select>
                </section>
                
                <section>
                    <label for="ano">Ingresso em:</label>
                    <input type="number" name="ano" min='2015' max='2025' value="<?php if (isset($_POST['ano'])) echo htmlspecialchars($_POST['ano']); ?>" required>
                </section>
            </div>
        
            <div clas='dados'>
                <section>
                    <label for="turno">Turno disponível:</label>

                    <?php 
                    
                    $opcoes = ["manha" => "", "tarde" => ""];

                    if(isset($_POST["turno"])) $opcoes[$_POST["turno"]] = "selected";

                    ?>

                    <select id="turno" name="turno">
                        <option value="manha" <?php echo $opcoes["manha"]; ?>>Manhã</option>
                        <option value="tarde" <?php echo $opcoes["tarde"]; ?>>Tarde</option>
                    </select>
                </section>
                
                <section>
                    <label for="situacao">Situação Atual:</label>
                    
                    <?php 
                    
                    $opcoes = ["procurando" => "", "estagiando" => "", "ocupado" => ""];

                    if(isset($_POST["situacao"])) $opcoes[$_POST["situacao"]] = "selected";

                    ?>

                    <select id="situacao" name="situacao">
                        <option value="procurando" <?php echo $opcoes["procurando"]; ?>>Procurando Estágio</option>
                        <option value="estagiando" <?php echo $opcoes["estagiando"]; ?>>Estagiando</option>
                        <option value="ocupado" <?php echo $opcoes["ocupado"]; ?>>Ocupado</option>
                    </select>
                </section>
            </div>

            <div class='dados'>
                <section>
                    <label for="modalidade">Modalidade:</label>

                    <?php 
                    
                    $opcoes = ["presencial" => "", "remoto" => "", "hibrido" => ""];

                    if(isset($_POST["modalidade"])) $opcoes[$_POST["modalidade"]] = "selected";

                    ?>

                    <select id="modalidade" name="modalidade">
                        <option value="presencial" <?php echo $opcoes["presencial"]; ?>>Presencial</option>
                        <option value="remoto" <?php echo $opcoes["remoto"]; ?>>Remoto</option>
                        <option value="hibrido" <?php echo $opcoes["hibrido"]; ?>>Híbrido</option>
                    </select>
                </section>
                
                <section>
                    <label for="cidadeEstagiar">Cidade para Estagiar:</label>
                    <input type="text" name="cidadeEstagiar" value="<?php if (isset($_POST['cidadeEstagiar'])) echo htmlspecialchars($_POST['cidadeEstagiar']); ?>" required>
                </section>
            </div>

            <div class='dados'>
                <section>
                    <label for="pref">Preferências</label>

                    <?php 
                        
                        foreach($preferencias as $preferencia) {
                            echo "<label><input type='checkbox' name='preferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                        }

                    ?>
                </section>

                <section>
                    <label for="nPref">Não Preferências</label>
                    
                    <?php 
                        
                        foreach($preferencias as $preferencia) {
                            echo "<label><input type='checkbox' name='naoPreferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                        }

                    ?>
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