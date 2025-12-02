<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;
use App\Classes\Preferencia;
use App\Classes\Curso;

$mensagemErro = "";

if (isset($_POST["cadastrar"])) {
    $usuario = new Aluno($_POST["email"], $_POST["senha"]);

    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    $cidadesEstagiar = isset($_POST["cidadesEstagiar"]) ? $_POST["cidadesEstagiar"] : [1];
    $modalidades = isset($_POST["modalidades"]) ? $_POST["modalidades"] : [];
    
    // Modalidades (mais simplificado)

    $modalidade = "";

    foreach($modalidades as $index => $descricao) {
        $modalidade .= $descricao . ", ";
    }

    $modalidade = substr($modalidade, 0, -2);

    if (!$usuario->usuarioExiste()) {
        if ($_POST["senha"] != $_POST["confSenha"]) {
            $mensagemErro = "Os campos de senha estão diferentes";

        } else if ($_POST["email"] != $_POST["confEmail"]) {
            $mensagemErro = "Os campos de email estão diferentes";

        } else if (strlen($_POST["senha"]) < 8) {
            $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

        } else if (!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        } else {
            $usuario->salvarAluno(
                $_POST["nome"],
                $_POST["sobrenome"],
                $_POST["dataNascimento"],
                $_POST["cpf"],
                $preferencias,
                $naoPreferencias,
                $_POST["ano"],
                $cidadesEstagiar,
                $_POST["turno"],
                $_POST["situacao"],
                $modalidade,
                $_POST["matricula"],
                $_POST["curso"]
            );

            session_start();

            $_SESSION['cadastrado'] = true;

            header("Location: ./../../index.php");
        }
    } else {
        $mensagemErro = "Email já cadastrado";
    }
}

$preferencias = Preferencia::findAllPreferencias();
$cursos = Curso::findAllCursos();
$cidadesEstagiar = [];

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Estagirando</title>

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleCadastro.css">

    <script src="./../../src/js/editarCad.js" defer></script>
</head>
<body>
    <div class="container">
        <header>
            <h1>Cadastro de Aluno</h1>
        </header>

        <main>
            <form action="./cadastroAluno.php" method="post">
                <section class="dados">
                    <section class="more-space">
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" value="<?php if (isset($_POST['nome']))
                            echo htmlspecialchars($_POST['nome']); ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="sobrenome">Sobrenome:</label>
                        <input type="text" name="sobrenome" value="<?php if (isset($_POST['sobrenome']))
                            echo htmlspecialchars($_POST['sobrenome']); ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="cpf">CPF:</label>
                        <input type="texto" name="cpf" id="cpf" pattern="^(?=(?:.*\d){11}$)(?:\d{11}|\d{3}\.\d{3}\.\d{3}-\d{2})$" placeholder="___.___.___-__" value="<?php if (isset($_POST['cpf'])) echo $_POST['cpf'] ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?php if (isset($_POST['email']))
                            echo htmlspecialchars($_POST['email']); ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="confEmail">Confirme o Email:</label>
                        <input type="email" name="confEmail" value="<?php if (isset($_POST['confEmail']))
                            echo htmlspecialchars($_POST['confEmail']); ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="matricula">Matrícula:</label>
                        <input type="string" name="matricula"  value="<?php if(isset($_POST['matricula'])) echo $_POST['matricula']; ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="dataNascimento">Data de Nascimento:</label>
                        <input type="date" name="dataNascimento" value="<?php if(isset($_POST['dataNascimento'])) echo $_POST['dataNascimento']; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="situacao">Situação Atual:</label>

                        <?php

                        $opcoes = ["Procurando Estágio" => "", "Estagiando" => "", "Ocupado" => ""];

                        if (isset($_POST["situacao"]))
                            $opcoes[$_POST["situacao"]] = "selected";

                        ?>

                        <select id="situacao" name="situacao">
                            <option value="Procurando estágio" <?php echo $opcoes["Procurando Estágio"]; ?>>Procurando Estágio</option>
                            <option value="Estagiando" <?php echo $opcoes["Estagiando"]; ?>>Estagiando</option>
                            <option value="Ocupado" <?php echo $opcoes["Ocupado"]; ?>>Ocupado</option>
                        </select>
                    </section>
                </section>

                <section class="dados">
                    <section class="more-space">
                        <label for="ano">Ano de Ingresso:</label>
                        <input type="number" name="ano" max='2025' value="<?php if (isset($_POST['ano']))
                            echo htmlspecialchars($_POST['ano']); ?>" required>
                    </section>

                    <section class="more-space">
                        <?php

                        $opcoes = [1 => "", 2 => "", 3 => "", 4 => ""];

                        if (isset($_POST["curso"])) $opcoes[$_POST["curso"]] = "selected";

                        ?>

                        <label for="curso">Selecione o curso:</label>
                        <select id="curso" name="curso">
                            <option value="" disabled selected>Curso</option>
                            <?php

                            $cursoSelecionado = isset($_POST["curso"]) ? $_POST["curso"] : -1;

                            foreach($cursos as $curso) {
                                $selected = $curso->getIdCurso() == $cursoSelecionado ? 'selected' : '';

                                echo "<option value={$curso->getIdCurso()} {$selected}>{$curso->getNome()}</option>";
                            }

                            ?>
                        </select>
                    </section>

                    <section class="more-space">
                        <label for="turno">Turno disponível:</label>

                        <?php

                        $opcoes = ["manhã" => "", "tarde" => "", "noite" => ""];

                        if (isset($_POST["turno"]))
                            $opcoes[$_POST["turno"]] = "selected";

                        ?>

                        <select id="turno" name="turno">
                            <option value="manhã" <?php echo $opcoes["manhã"]; ?>>Manhã</option>
                            <option value="tarde" <?php echo $opcoes["tarde"]; ?>>Tarde</option>
                            <option value="noite" <?php echo $opcoes["noite"]; ?>>Noite</option>
                        </select>
                    </section>

                    <section class="more-space">
                        <label for="senha">Senha:</label>
                        <input type="password" name="senha" value="<?php if (isset($_POST['senha']))
                            echo htmlspecialchars($_POST['senha']); ?>" required>
                    </section>

                    <section class="more-space">
                        <label for="confSenha">Confirme a Senha:</label>
                        <input type="password" name="confSenha" value="<?php if (isset($_POST['confSenha']))
                            echo htmlspecialchars($_POST['confSenha']); ?>" required>
                    </section>

                    <div id="modalidade">
                        <label for="modalidade" class="modalidade">Modalidade:</label>

                        <label for="todosModalidade" class="modalidade"><input type="checkbox" id="todosModalidade" value="todos" onchange="selecionar()" > Selecionar todos</label>
                        <label for="presencial" class="modalidade"><input type="checkbox" name="modalidades[]" id="presencial" value="presencial" onchange="verificar()"> Presencial</label>
                        <label for="online" class="modalidade"><input type="checkbox" name="modalidades[]" id="online" value="online" onchange="verificar()"> Online</label>
                        <label for="hibrido" class="modalidade"><input type="checkbox" name="modalidades[]" id="hibrido" value="hibrido" onchange="verificar()"> Híbrido</label>
                    </div>
                </section>

                <section class="preferencias">
                    <section class="cidades">
                        <p>Cidades Para Estagiar</p>

                        <input type="text" name="cidadeEstagiar" id="cidadeEstagiar" placeholder="Cidade, Estado (sigla)">

                        <div class='sugestoes'></div>

                        <div class="checkboxes">
                            <label>Cidades Escolhidas</label>

                            <?php

                            $checked = (isset($cidadesEstagiar['1'])) ? "checked" : "";

                            echo "<label><input type='checkbox' name='cidadesEstagiar[]' value=1 id='qualquerCidade' {$checked}> Qualquer Cidade</label>";

                            foreach ($cidadesEstagiar as $index => $cidade) {
                                if ($cidade['nome'] === 'Todos')
                                    continue;

                                echo "<label><input type='checkbox' name='cidadesEstagiar[]' value={$index} onchange='resetarQualquerCidade()' checked> {$cidade['nome']}, {$cidade['uf']}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Preferências</p>

                        <div id="preferencias">
                            <p>Escolha um curso primeiro</p>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências</p>

                        <div id="naoPreferencias">
                            <p>Escolha um curso primeiro</p>
                        </div>
                    </section>
                </section>

                <section id="btn" class="links">
                    <?php echo "<p class='erro'>{$mensagemErro}</p>"; ?>

                    <div>
                        <input class="link" type="submit" name="cadastrar" value="Cadastrar">
                        <a class="link" href="./../../index.php" id='cancelar'>Cancelar</a>
                    </div>
                </section>
            </form>
        </main>
    </div>

    <script src="https://unpkg.com/inputmask/dist/inputmask.min.js"></script>
    <script>
        Inputmask("999.999.999-99").mask(document.getElementById('cpf'));
    </script>
</body>
</html>