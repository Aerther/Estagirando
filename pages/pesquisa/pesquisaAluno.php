<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Preferencia;
use App\Classes\Curso;

$preferencias = Preferencia::findAllPreferencias();
$cursos = Curso::findAllCursos();
$cidadesEstagiar = [];

$mensagemErro = "";
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pesquisa Avançada de Alunos</title>

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleCadastro.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Pesquisa Avançada de Alunos</h1>
        </header>

        <main>
            <form action="./pesquisaAluno.php" method="post">
                <section class="dados">
                    <section>
                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
                    </section>

                    <section>
                        <label for="sobrenome">Sobrenome:</label>
                        <input type="text" name="sobrenome" value="<?= isset($_POST['sobrenome']) ? htmlspecialchars($_POST['sobrenome']) : '' ?>">
                    </section>

                    <section>
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </section>

                    <section>
                        <label for="curso">Curso:</label>
                        <select id="curso" name="curso">
                            <option value="">Selecione o curso</option>
                            <?php
                            $cursoSelecionado = $_POST["curso"] ?? "";
                            foreach ($cursos as $curso) {
                                $selected = $curso->getIdCurso() == $cursoSelecionado ? 'selected' : '';
                                echo "<option value='{$curso->getIdCurso()}' {$selected}>{$curso->getNome()}</option>";
                            }
                            ?>
                        </select>
                    </section>

                    <section>
                        <label for="ano">Ano de Ingresso:</label>
                        <input type="number" name="ano" max="2025" value="<?= isset($_POST['ano']) ? htmlspecialchars($_POST['ano']) : '' ?>">
                    </section>

                    <section>
                        <label for="preferencias">Áreas de preferência:</label>
                        <select id="preferencias" name="preferencias[]" multiple>
                            <?php
                            foreach ($preferencias as $pref) {
                                $selected = (isset($_POST['preferencias']) && in_array($pref->getIdPreferencia(), $_POST['preferencias'])) ? 'selected' : '';
                                echo "<option value='{$pref->getIdPreferencia()}' {$selected}>{$pref->getNome()}</option>";
                            }
                            ?>
                        </select>
                    </section>

                    <section>
                        <label for="naoPreferencias">Áreas de não preferência:</label>
                        <select id="naoPreferencias" name="naoPreferencias[]" multiple>
                            <?php
                            foreach ($preferencias as $pref) {
                                $selected = (isset($_POST['naoPreferencias']) && in_array($pref->getIdPreferencia(), $_POST['naoPreferencias'])) ? 'selected' : '';
                                echo "<option value='{$pref->getIdPreferencia()}' {$selected}>{$pref->getNome()}</option>";
                            }
                            ?>
                        </select>
                    </section>

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
                        <label for="turno">Turno disponível:</label>

                        <?php

                        $opcoes = ["manhã" => "", "tarde" => ""];

                        if (isset($_POST["turno"]))
                            $opcoes[$_POST["turno"]] = "selected";

                        ?>

                        <select id="turno" name="turno">
                            <option value="manhã" <?php echo $opcoes["manhã"]; ?>>Manhã</option>
                            <option value="tarde" <?php echo $opcoes["tarde"]; ?>>Tarde</option>
                        </select>
                    </section>

                    <section>
                        <label for="situacao">Situação Atual:</label>

                        <?php

                        $opcoes = ["Procurando Estágio" => "", "Estagiando" => "", "Ocupado" => ""];

                        if (isset($_POST["situacao"]))
                            $opcoes[$_POST["situacao"]] = "selected";

                        ?>

                        <select id="situacao" name="situacao">
                            <option value="Procurando Estágio" <?php echo $opcoes["Procurando Estágio"]; ?>>Procurando Estágio</option>
                            <option value="Estagiando" <?php echo $opcoes["Estagiando"]; ?>>Estagiando</option>
                            <option value="Ocupado" <?php echo $opcoes["Ocupado"]; ?>>Ocupado</option>
                        </select>
                    </section>
                </section>
                
                <section id="btn" class="links">
                    <?php if (!empty($mensagemErro)) echo "<p class='erro'>{$mensagemErro}</p>"; ?>

                    <div>
                        <input class="link" type="submit" value="Pesquisar">
                        <a class="link" href="./../../index.php" id="cancelar">Cancelar</a>
                    </div>
                </section>
            </form>
        </main>
    </div>
</body>
</html>
