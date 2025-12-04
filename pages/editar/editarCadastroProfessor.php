<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "professor") header("Location: ./../../privado.php");

$mensagemErro = "";

if(isset($_SESSION['senhaEditada']) && $_SESSION['senhaEditada']) {
    $mensagemErro = "Senha editada com sucesso!";
    
    $_SESSION['senhaEditada'] = false;
}

if(isset($_POST['salvar'])) {
    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    $usuario = new Professor($_POST["email"], "");

    if(!$usuario->usuarioExiste()) {
        if(!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        }else if(empty($_POST['preferencias'])) {
            $mensagemErro = "Você deve selecionar uma preferência";

        } else if(empty($_POST['naoPreferencias'])) {
            $mensagemErro = "Você deve selecionar uma não preferência";

        } else {
            $usuario->atualizarProfessor(
                $_POST["nome"],
                $_POST["sobrenome"],
                $_POST["email"],
                $_POST["dataNascimento"],
                $_POST["cpf"],
                $preferencias,
                $naoPreferencias,
                $_POST["disponivel"]
            );

            header("Location: ./../../privado.php");
        }
    } else {
        $mensagemErro = "Email já cadastrado";
    }
}

$professor = Professor::findProfessor($_SESSION["idUsuario"]);
$preferencias = Preferencia::findAllPreferencias();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleEditar2.css">

    <script src="./../../src/js/editarCad.js" defer></script>

    <title>Edição de Cadastro Professor</title>

    <style>
        form {
            height: 700px;

            grid-template-rows: 28% 55% 18%;
        }

        .preferencias {
            grid-template-columns: repeat(2, 1fr);
        }

        .preferencias > section {
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>
        
        <main>
            <div id='title'>
                <?php
                    echo "<p class='title'>Edição de cadastro</p>"
                ?>
            </div>

            <form action="./editarCadastroProfessor.php" method="post">
                <section class="dados">
                    <section class="imagem">
                        <figure>
                            <img src="./../../icones/iconProf.png" alt="Icone Professor">
                        </figure>
                    </section>

                    <section class="dados-input">
                        <section>
                            <label for="nome">Nome:</label>
                            <input type="text" name="nome"  value="<?php echo $professor->getNome(); ?>" required>
                        </section>

                        <section>
                            <label for="sobrenome">Sobrenome:</label>
                            <input type="text" name="sobrenome"  value="<?php echo $professor->getSobrenome(); ?>" required>
                        </section>

                        <section>
                            <label for="email">E-mail:</label>
                            <input type="email" name="email" value="<?php echo $professor->getEmail(); ?>" required>
                        </section>

                        <section>
                            <a href="editarSenhaProf.php" id='editSenha'>Editar senha</a>
                        </section>
                    </section>

                    <section class="dados-input">
                        <section>
                            <label for="cpf">CPF:</label>
                            <input type="texto" name="cpf" pattern="^(?=(?:.*\d){11}$)(?:\d{11}|\d{3}\.\d{3}\.\d{3}-\d{2})$" id="cpf" placeholder="___.___.___-__" value="<?php echo $professor->getCPF(); ?>" required>
                        </section>

                        <section class="more-space">
                            <label for="dataNascimento">Data de nascimento:</label>
                            <input type="date" name="dataNascimento"  value="<?php echo date("Y-m-d", strtotime(str_replace("/", "-", $professor->getDataNAscimento())));; ?>" max="<?php echo date('Y-m-d'); ?>" required>
                        </section>

                        <section class="radio">
                            <label for="disponivel">Disponível para orientar?</label>

                            <div class="disponibilidade">
                                <?php 

                                $opcoes = ["sim" => "", "nao" => ""];

                                $opcoes[$professor->getStatusDisponibilidade()] = "checked";
                                
                                echo "<label><input type='radio' name='disponivel' value='sim' {$opcoes['sim']} required>Sim</label>";
                                echo "<label><input type='radio' name='disponivel' value='nao' {$opcoes['nao']} required>Não</label>";

                                ?>
                            </div>
                        </section>
                    </section>
                </section>

                <section class="preferencias">
                    <section>
                        <p>Preferências:</p>

                        <div>
                            <?php
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $professor->getPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' name='preferencias[]' onchange='sincronizarCheckbox(this)' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências:</p>
                        
                        <div>
                            <?php 
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $professor->getNaoPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' name='naoPreferencias[]' onchange='sincronizarCheckbox(this)' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>
                </section>

                <section class="links">
                    <?php 

                    $cor = strpos($mensagemErro, "sucesso") ? "green" : "red";
                    echo "<p class='erro' style='color: {$cor}'>{$mensagemErro}</p>"; 
                    
                    ?>

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