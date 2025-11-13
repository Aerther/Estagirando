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

                    <section>
                        <label for="cidadesEstagiar">Cidade(s) que pode estagiar:</label>
                        <input type="text" name="cidadesEstagiar" placeholder="Ex: Belo Horizonte, MG" value="<?= isset($_POST['cidadesEstagiar']) ? htmlspecialchars($_POST['cidadesEstagiar']) : '' ?>">
                    </section>

                    <section>
                        <label for="turno">Turno disponível:</label>
                        <?php
                        $opcoesTurno = ["manha" => "", "tarde" => "", "noite" => ""];
                        if (isset($_POST["turno"])) $opcoesTurno[$_POST["turno"]] = "selected";
                        ?>
                        <select id="turno" name="turno">
                            <option value="">Selecione</option>
                            <option value="manha" <?= $opcoesTurno["manha"]; ?>>Manhã</option>
                            <option value="tarde" <?= $opcoesTurno["tarde"]; ?>>Tarde</option>
                            <option value="noite" <?= $opcoesTurno["noite"]; ?>>Noite</option>
                        </select>
                    </section>

                    <section>
                        <label for="situacao">Status de estágio:</label>
                        <?php
                        $opcoes = ["procurando" => "", "estagiando" => "", "ocupado" => ""];
                        if (isset($_POST["situacao"])) $opcoes[$_POST["situacao"]] = "selected";
                        ?>
                        <select id="situacao" name="situacao">
                            <option value="">Selecione</option>
                            <option value="procurando" <?= $opcoes["procurando"]; ?>>Procurando Estágio</option>
                            <option value="estagiando" <?= $opcoes["estagiando"]; ?>>Estagiando</option>
                            <option value="ocupado" <?= $opcoes["ocupado"]; ?>>Ocupado</option>
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
