<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

$mensagemErro = "";

if(isset($_POST["cadastrar"])) {
    $usuario = new Professor($_POST["email"], $_POST["senha"]);

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
            $mensagemErro = "Você não pode selecionar o mesmo atributo para Preferências e Não Preferências";

        } else if(empty($_POST['preferencias'])) {
            $mensagemErro = "Você deve selecionar uma preferência";

        } else if(empty($_POST['naoPreferencias'])) {
            $mensagemErro = "Você deve selecionar uma não preferência";

        } else {
            $usuario->salvarProfessor(
                $_POST["nome"],
                $_POST["sobrenome"],
                $_POST["dataNascimento"],
                $_POST["cpf"],
                $preferencias,
                $naoPreferencias,
                $_POST["disponivel"]
            );
            session_start();
            $_SESSION['cadastrado']=true;
            header("Location: ./../../index.php");
            
        }
    } else {
        $mensagemErro = "Email já cadastrado";
    }
}

$preferencias = Preferencia::findAllPreferencias();

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
            <h1>Cadastro de Professor</h1>
        </header>

        <main>
            <form action="./cadastroProfessor.php" method="post">
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
                        <label for="dataNascimento">Data Nascimento:</label>
                        <input type="date" name="dataNascimento" value="<?php if(isset($_POST['dataNascimento'])) echo $_POST['dataNascimento']; ?>" max="<?php echo date('Y-m-d'); ?>" required>
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
                </section>

                <section class="dados">
                    <section class="more-space">
                        <label for="cpf">CPF:</label>
                        <input type="texto" name="cpf" id="cpf" pattern="^(?=(?:.*\d){11}$)(?:\d{11}|\d{3}\.\d{3}\.\d{3}-\d{2})$" placeholder="___.___.___-__" value="<?php if (isset($_POST['cpf'])) echo $_POST['cpf'] ?>" required>
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
                    

                    <section class="radio" class="more-space">
                        <label for="disponivel" id='lblD'>Disponível para orientar?</label>

                        <div class="disponibilidade">
                            <?php 

                            $opcoes = ["sim" => "", "nao" => ""];

                            if(isset($_POST["disponivel"])) $opcoes[$_POST["disponivel"]] = "checked";
                            
                            echo "<label><input type='radio' name='disponivel' value='sim' {$opcoes['sim']} required>Sim</label>";
                            echo "<label><input type='radio' name='disponivel' value='nao' {$opcoes['nao']} required>Não</label>";
                            
                            ?>
                        </div>
                    </section>
                </section>

                <section class="preferencias">
                    <section>
                        <p>Preferências</p>

                        <div>
                            <?php

                            foreach ($preferencias as $preferencia) {
                                echo "<label id=pref><input type='checkbox' onchange='sincronizarCheckbox(this)' name='preferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências</p>

                        <div>
                            <?php

                            foreach ($preferencias as $preferencia) {
                                echo "<label id=pref><input type='checkbox' onchange='sincronizarCheckbox(this)' name='naoPreferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
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