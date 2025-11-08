<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

session_start();
$msgSenha = "";
if(isset($_SESSION['senhaEditada']) && $_SESSION['senhaEditada']){

    $msgSenha = "Senha editada com sucesso!";
    $_SESSION['senhaEditada']=false;
}

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "professor") header("Location: ./../../privado.php");

$mensagemErro = "";

if(isset($_POST['salvar'])) {
    $senha = isset($_POST["senha"]) ? $_POST["senha"] : "";
    
    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    $usuario = new Professor($_POST["email"], $senha);

    if(!$usuario->usuarioExiste()) {
        if(strlen($senha) < 8 && !empty($senha)) {
            $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

        } else if(!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        } else {
            $usuario->atualizarProfessor(
                $_POST["nome"],
                $_POST["sobrenome"],
                $_POST["email"],
                $preferencias,
                $naoPreferencias,
                $_POST["disponivel"]
            );

            if(isset($_POST["senha"])) {
                $usuario->atualizarSenha($senha);
            }

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
    <link rel="stylesheet" href="./../../src/styles/styleEditar.css">

    <title>Edição de Cadastro Professor</title>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>
        
        <main>
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

                    <section class="dados-input">
                        <section>
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?php echo $professor->getEmail(); ?>" required>
                    </section>

                    <section id=divEditSenha>
                        <a href="editarSenhaProf.php" id='editSenha'>Editar senha </a>
                        <?php echo " <p class='sucesso'>{$msgSenha}</p>";?>
                    </section>
                    </section>
                </section>

                <section class="preferencias">
                    <section>
                        <p>Preferências</p>

                        <div>
                            <?php 
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $professor->getPreferencias()) ? "checked" : "";
                                echo "<label><input type='checkbox' name='preferencias[]' value={$preferencia->getIdPreferencia()} {$selected}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências</p>
                        
                        <div>
                            <?php 
                        
                            foreach($preferencias as $preferencia) {
                                $selected = array_key_exists($preferencia->getIdPreferencia(), $professor->getNaoPreferencias()) ? "checked" : "";
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
</body>
</html>