<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;
use App\Classes\Preferencia;
use App\Classes\Curso;

session_start();
$msgSenha = "";
if(isset($_SESSION['senhaEditada']) && $_SESSION['senhaEditada']){

    $msgSenha = "Senha editada com sucesso!";
    $_SESSION['senhaEditada']=false;
}

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "aluno") header("Location: ./../../privado.php");

$mensagemErro = "";

if(isset($_POST['salvar'])) {
    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    $cidadesEstagiar = isset($_POST["cidadesEstagiar"]) ? $_POST["cidadesEstagiar"] : [1];

    $usuario = new Aluno($_POST["email"], "");

    if(isset($_POST[todosModalidade])){
        $modalidade = 'presencial,online,hibrido';
    }else if(isset($_POST[presencial]) && isset($_POST[online])){
        $modalidade = 'presencial,online';
    }else if(isset($_POST[presencial]) && isset($_POST[hibrido])){
        $modalidade = 'presencial,hibrido';
    }else if(isset($_POST[hibrido]) && isset($_POST[online])){
        $modalidade = 'online,hibrido';
    }

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

                        <section class="more-space">
                            <label for="dataNascimento">Data Nascimento:</label>
                            <input type="date" name="dataNascimento"  value="<?php echo date("Y-m-d", strtotime(str_replace("/", "-", $aluno->getDataNAscimento())));; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                        </section>

                        <section>
                            <label for="modalidade">Modalidade:</label>

                            <?php 
                            $modalidades = ["presencial" => "", "remoto" => "", "hibrido" => ""];
                            $checked = explode(",", $aluno->getModalidade());
                            $contador = 0;
                            for($i=0; $i < (sizeof($checked, 0)-1); $i++){
                                $modalidades[$checked[i]] = "checked";
                            }


                            ?>
                            <label for="modalidade">Modalidade:</label>
                            <input type="checkbox" name="todosModalidade" id="todosModalidade" value="todos" onchange="selecionar()" >Selecionar todos
                                
                            <input type="checkbox" name="presencial" id="presencial" value="presencial" <?php echo $modalidade["presencial"]; ?> onchange="verificar()"><label for="presencial">Presencial</label>
                            <input type="checkbox" name="online" id="online" value="online" <?php echo $modalidade["remoto"]; ?> onchange="verificar()"><label for="online">Online</label>
                            <input type="checkbox" name="hibrido" id="hibrido" value="hibrido" <?php echo $modalidade["hibrido"]; ?> onchange="verificar()"><label for="hibrido">Hibrido</label>

                        </section>

                        <section class="more-space">
                            <label for="turno">Turno disponível:</label>

                            <?php 
                            
                            $opcoes = ["manhã" => "", "tarde" => ""];

                            $opcoes[$aluno->getTurnoDisponivel()] = "selected";

                            ?>

                            <select id="turno" name="turno">
                                <option value="manhã" <?php echo $opcoes["manhã"]; ?>>Manhã</option>
                                <option value="tarde" <?php echo $opcoes["tarde"]; ?>>Tarde</option>
                            </select>
                        </section>
                        
                        <section class="more-space">
                            <label for="situacao">Situação Atual:</label>
                            
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
                    </section>

                    <section class="dados-input">
                        <section>
                            <label for="email">Email:</label>
                            <input type="email" name="email" value="<?php echo $aluno->getEmail(); ?>" required>
                        </section>

                        <section id="divEditSenha">
                            <a href="editarSenhaAluno.php" id='editSenha'>Editar senha </a>
                            <?php echo " <p class='sucesso'>{$msgSenha}</p>";?>
                        </section>

                        <section>
                            <label for="cpf">CPF:</label>
                            <input type="texto" name="cpf" pattern="^(?=(?:.*\d){11}$)(?:\d{11}|\d{3}\.\d{3}\.\d{3}-\d{2})$" id="cpf" placeholder="___.___.___-__" value="<?php echo $aluno->getCPF(); ?>" required>
                        </section>

                        <section>
                            <label for="matricula">Matricula:</label>
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
                    </section>
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

                            foreach($cidadesEstagiar as $index => $cidade) {
                                if ($cidade['nome'] === 'Todos') continue;
                                
                                echo "<label><input type='checkbox' name='cidadesEstagiar[]' value={$index} onchange='resetarQualquerCidade()' checked> {$cidade['nome']}, {$cidade['uf']}</label>";
                            }

                            ?>

                        </div>
                    </section>

                    <section>
                        <p>Preferências</p>

                        <div id="preferencias">
                            <?php
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $aluno->getPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' name='preferencias[]' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências</p>
                        
                        <div id="naoPreferencias">
                            <?php 
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $aluno->getNaoPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' name='naoPreferencias[]' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
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