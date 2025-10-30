<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;
use App\Classes\Preferencia;
use App\Classes\Curso;

$mensagemErro = "";

if(isset($_POST["cadastrar"])) {
    $usuario = new Aluno($_POST["email"], $_POST["senha"]);

    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    if(!$usuario->usuarioExiste()) {
        if($_POST["senha"] != $_POST["confSenha"]) {
            $mensagemErro = "Os campos de senha estão diferentes";

        } else if($_POST["email"] != $_POST["confEmail"]) {
            $mensagemErro = "Os campos de email estão diferentes";

        } else if(strlen($_POST["senha"]) < 8) {
            $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

        } else if(!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        } else {
            $usuario->salvarAluno(
                $_POST["nome"],
                $_POST["sobrenome"],
                $preferencias,
                $naoPreferencias,
                $_POST["ano"],
                $_POST["cidadeEstagiar"],
                $_POST["turno"],
                $_POST["situacao"],
                $_POST["modalidade"],
                $_POST["curso"]
            );
            
            header("Location: ./../../index.php");
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
    
    <link rel= "stylesheet" href="./../../src/styles/styleCadastroAluno.css">
</head>
<body id="body">
    <h1>Cadastro de Aluno</h1>
    <div class="container">

    <p id="erro"><?php echo $mensagemErro;?></p>

    <form action="./cadastroAluno.php" method="post">
        <div class="dado">
        <section>
            <label for="nome">Nome:</label>
            <input type="text" name="nome" value="<?php if (isset($_POST['nome'])) echo htmlspecialchars($_POST['nome']); ?>" required>
        </section>

        <section>
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required>
        </section>

        <section>
            <label for="senha">Senha:</label>
            <input type="password" name="senha" value="<?php if (isset($_POST['senha'])) echo htmlspecialchars($_POST['senha']); ?>" required>
        </section>
        </div>

        <div class="dado">
        <section>
            <label for="sobrenome">Sobrenome:</label>
            <input type="text" name="sobrenome" value="<?php if (isset($_POST['sobrenome'])) echo htmlspecialchars($_POST['sobrenome']); ?>" required>
        </section>

        <section>
            <label for="confEmail">Confirme o Email:</label>
            <input type="email" name="confEmail" value="<?php if (isset($_POST['confEmail'])) echo htmlspecialchars($_POST['confEmail']); ?>" required>
        </section>

        <section>
            <label for="confSenha">Confirme a Senha:</label>
            <input type="password" name="confSenha" value="<?php if (isset($_POST['confSenha'])) echo htmlspecialchars($_POST['confSenha']); ?>" required>
        </section>
        </div>

        <div class="preferencia">
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

        <div id="dado2">

        <section>
            <label for="ano">Ano de Ingresso:</label>
            <input type="number" name="ano" min='2015' max='2025' value="<?php if (isset($_POST['ano'])) echo htmlspecialchars($_POST['ano']); ?>" required>
        </section>

        <section>
            <label for="curso">Selecione o curso:</label>
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
            <label for="cidede">Cidade para Estagiar:</label>
            <input type="text" name="cidadeEstagiar" value="<?php if (isset($_POST['cidadeEstagiar'])) echo htmlspecialchars($_POST['cidadeEstagiar']); ?>" required>
        </section>

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

        </div>

        <div id="dado3">
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

        <div id="btn">
        <section>
            <input type="submit" name="cadastrar" value="Cadastrar">
            <a href="./../../index.php">Cancelar</a>
        </section>
        </div>
        </div>
    </form> 
        
</div>
</body>
</html>
