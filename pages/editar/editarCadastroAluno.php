<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;
use App\Classes\Preferencia;
use App\Classes\Curso;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "aluno") header("Location: ./../../privado.php");

$mensagemErro = "";

if(isset($_SESSION['senhaEditada']) && $_SESSION['senhaEditada']){

    $mensagemErro = "Senha editada com sucesso!";
    $_SESSION['senhaEditada'] = false;
}

if(isset($_POST['salvar'])) {
    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    $cidadesEstagiar = isset($_POST["cidadesEstagiar"]) ? $_POST["cidadesEstagiar"] : [1];

    $usuario = new Aluno($_POST["email"], "");
    
    // Modalidades (mais simplificado)

    $presencial = isset($_POST["presencial"]) ? $_POST["presencial"] . ", " : ""; 
    $online = isset($_POST["online"]) ? $_POST["online"] . ", " : ""; 
    $hibrido = isset($_POST["hibrido"]) ? $_POST["hibrido"] . ", " : ""; 
    
    $modalidade = $presencial . $online . $hibrido;

    if(!$usuario->usuarioExiste()) {
        if(!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        } else {
            $usuario->atualizarAluno(
                $_POST["nome"],
                $_POST["sobrenome"],
                $_POST["email"],
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

            header("Location: ./../../privado.php");
        }
    } else {
        $mensagemErro = "Email já cadastrado";
    }
}

$aluno = Aluno::findAluno($_SESSION["idUsuario"]);
$preferencias = Preferencia::findAllPreferenciasByCurso($aluno->getCurso()->getIdCurso());
$cursos = Curso::findAllCursos();
$cidadesEstagiar = $aluno->getCidadesEstagiar();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Estagirando</title>

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel= "stylesheet" href="./../../src/styles/styleEditar.css">

    <script src="./../../src/js/editarCad.js" defer></script>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>
        <div id='title'>
            <?php
                echo "<p class='title'>Edição de cadastro</p>"
            ?>
        </div>
        
        <main>
            <form action="./editarCadastroAluno.php" method="post">
                <section class="dados">
                    <section class="imagem">

                        <figure>
                            <img src="./../../icones/iconAluno.png" alt="Icone Aluno">
                        </figure>
                    </section>

                    <section class="dados-input">
                        <section>
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome"  value="<?php echo $aluno->getNome(); ?>" required>
                        </section>

                        <section>
                            <label for="sobrenome">Sobrenome:</label>
                            <input type="text" name="sobrenome"  value="<?php echo $aluno->getSobrenome(); ?>" required>
                        </section>

                        <section>
                            <label for="email">E-mail:</label>
                            <input type="email" name="email" value="<?php echo $aluno->getEmail(); ?>" required>
                        </section>

                        <section>
                            <label for="cpf">CPF:</label>
                            <input type="texto" name="cpf" pattern="^(?=(?:.*\d){11}$)(?:\d{11}|\d{3}\.\d{3}\.\d{3}-\d{2})$" id="cpf" placeholder="___.___.___-__" value="<?php echo $aluno->getCPF(); ?>" required>
                        </section>

                        <section class="more-space">
                            <label for="dataNascimento">Data de nascimento:</label>
                            <input type="date" name="dataNascimento"  value="<?php echo date("Y-m-d", strtotime(str_replace("/", "-", $aluno->getDataNAscimento())));; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                        </section>
                        

                        <section class="more-space">
                            <label for="situacao">Situação atual:</label>
                            
                            <?php 
                            
                            $opcoes = ["procurando estágio" => "", "estagiando" => "", "ocupado" => ""];

                            $opcoes[$aluno->getStatusEstagio()] = "selected";

                            ?>

                            <select id="situacao" name="situacao">
                                <option value="procurando estágio" <?php echo $opcoes["procurando estágio"]; ?>>Procurando Estágio</option>
                                <option value="estagiando" <?php echo $opcoes["estagiando"]; ?>>Estagiando</option>
                                <option value="ocupado" <?php echo $opcoes["ocupado"]; ?>>Ocupado</option>
                            </select>
                        </section>
                        <section id="divEditSenha">
                            <a href="editarSenhaAluno.php" id='editSenha'>Editar senha </a>
                        </section>
                        
                        <section>
                            <a href="editarSenhaAluno.php" id='editSenha'>Editar senha </a>
                        </section>
                    </section>

                    <section class="dados-input">
                        <section>
                            <label for="matricula">Matrícula:</label>
                            <input type="string" name="matricula"  value="<?php echo $aluno->getMatricula(); ?>" required>
                        </section>

                        <section>
                            <label for="curso">Curso:</label>
                            <select id="curso" name="curso">
                                <?php

                                $cursoSelecionado = $aluno->getIdCurso();

                                foreach($cursos as $curso) {
                                    $selected = $curso->getIdCurso() == $cursoSelecionado ? 'selected' : '';

                                    echo "<option value={$curso->getIdCurso()} {$selected}>{$curso->getNome()}</option>";
                                }

                                ?>
                            </select>
                        </section>
                        
                        <section class="more-space">
                            <label for="ano">Ingressou em:</label>
                            <input type="number" name="ano" max='2025' value="<?php echo $aluno->getAnoIngresso(); ?>" required>
                        </section>

                        <section class="more-space">
                            <label for="turno">Turno disponível:</label>

                            <?php 
                            
                            $opcoes = ["manhã" => "", "tarde" => "", "noite" => ""];

                            $opcoes[$aluno->getTurnoDisponivel()] = "selected";

                            ?>

                            <select id="turno" name="turno">
                                <option value="manhã" <?php echo $opcoes["manhã"]; ?>>Manhã</option>
                                <option value="tarde" <?php echo $opcoes["tarde"]; ?>>Tarde</option>
                                <option value="noite" <?php echo $opcoes["noite"]; ?>>Noite</option>
                            </select>
                        </section>

                        <div id="modalidade">
                            <?php 

                            $modalidades = ["presencial" => "", "online" => "", "hibrido" => ""];

                            $checked = explode(", ", $aluno->getModalidade());

                            $selecionar = '';

                            for($i = 0; $i < sizeof($checked, 0); $i++) {
                                $modalidades[trim($checked[$i])] = "checked";                 
                            }

                            ?>
                            
                            <label for="modalidade" class="modalidade">Modalidade:</label>
                            
                            <div id="modalidade">
                                <label for="todosModalidade" class="modalidade"><input type="checkbox" name="todosModalidade" id="todosModalidade" value="todos" <?php echo $selecionar; ?> onchange="selecionar()"> Selecionar todos</label>
                                <label for="presencial" class="modalidade"><input type="checkbox" name="presencial" id="presencial" value="presencial" <?php echo $modalidades["presencial"]; ?> onchange="verificar()"> Presencial</label>
                                <label for="online" class="modalidade"><input type="checkbox" name="online" id="online" value="online" <?php echo $modalidades["online"]; ?> onchange="verificar()"> Online</label>
                                <label for="hibrido" class="modalidade"><input type="checkbox" name="hibrido" id="hibrido" value="hibrido" <?php echo $modalidades["hibrido"]; ?> onchange="verificar()"> Híbrido</label>
                            </div>
                        </div>
                    </section>
                </section>

                <section class="preferencias">
                    <section class="cidades">
                        <p>Cidades para estagiar:</p>

                        <input type="text" name="cidadeEstagiar" id="cidadeEstagiar" placeholder="Cidade, Estado (sigla)">

                        <div class='sugestoes'></div>
                        
                        <div class="checkboxes">
                            <label>Cidades escolhidas:</label>

                            <?php

                            $checked = (isset($cidadesEstagiar['1'])) ? "checked" : "";

                            echo "<label><input type='checkbox' name='cidadesEstagiar[]' value=1 id='qualquerCidade' {$checked}> Qualquer Cidade</label>";

                            foreach($cidadesEstagiar as $index => $cidade) {
                                if ($cidade['nome'] === 'Todos') continue;
                                
                                echo "<label><input type='checkbox' name='cidadesEstagiar[]' value={$index} onchange='resetarQualquerCidade()' checked> {$cidade['nome']}, {$cidade['uf']}</label>";
                            }

                            ?>

                        </div>
                    </section>

                    <section>
                        <p>Preferências:</p>

                        <div id="preferencias">
                            <?php
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $aluno->getPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' name='preferencias[]' onchange='sincronizarCheckbox(this)' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências:</p>
                        
                        <div id="naoPreferencias">
                            <?php 
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $aluno->getNaoPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' name='naoPreferencias[]' onchange='sincronizarCheckbox(this)' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>
                </section>

                <section class="links">
                    <?php echo "<p class='erro'>{$mensagemErro}</p>"; ?>

                    <div>
                        <input class='link' type="submit" name="salvar" value="Salvar">
                        <a class='link' href="./../../privado.php">Cancelar</a>
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