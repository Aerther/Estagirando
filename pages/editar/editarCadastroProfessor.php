<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

$mensagemErro = "";

if(isset($_POST['cadastrar'])) {
    $usuario = new Professor($_POST["email"], $_POST["senha"]);

    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    if(!$usuario->usuarioExiste()) {
        if(strlen($_POST["senha"]) < 8) {
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

            header("Location: ./../../privado.php");
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

    <title>Edição de Cadastro Professor</title>
</head>
<body>
    <div id="menu">
        <a href="./../pesquisa/pesquisa.php">Pesquisa</a>
        <a href="./../../solicitacoesOrientacao.php">Solicitações</a>
        <a href="./../visualizar/visualizarCadastro.php">Próprio Cadastro</a>
        <a href="./../editar/editarCadastro.php">Edição de Cadastro</a>
        <a href="./../../sair.php">Sair</a>
    </div>
    
    <div id='edicao'>
        <p id="erro"><?php echo $mensagemErro;?></p>

        <form action="editarCadastroProfessor.php" method="post">
            <div class="dado">
                <section>
                    <label for="nome">Nome:</label>
                    <input type="text" name="nome"  value="<?php if (isset($_POST['nome'])) echo htmlspecialchars($_POST['nome']); ?>" required>
                </section>

                <section>
                    <label for="sobrenome">Sobrenome:</label>
                    <input type="text" name="sobrenome"  value="<?php if (isset($_POST['sobrenome'])) echo htmlspecialchars($_POST['sobrenome']); ?>" required>
                </section>
            </div>

            <div class="dado">
                <section>
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php if (isset($_POST['email'])) echo htmlspecialchars($_POST['email']); ?>" required>
                </section>

                <section>
                    <label for="senha">Senha:</label>
                    <input type="password" name="senha" value="<?php if (isset($_POST['senha'])) echo htmlspecialchars($_POST['senha']); ?>" required>
                </section>
            </div>

            <div class="preferencia">
            
                <section>
                    <p>Preferências</p>

                    <?php 
                    
                    foreach($preferencias as $preferencia) {
                        echo "<label><input type='checkbox' name='preferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                    }

                    ?>
                </section>

                <section>
                    <p>Não preferências</p>

                    <?php 
                    
                    foreach($preferencias as $preferencia) {
                        echo "<label><input type='checkbox' name='naoPreferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                    }

                    ?>
                </section>

            </div>

            <section id="radios">
                <label for="">Disponível para orientar?</label>

                <div id="disponibilidade">
                    <label><input type="radio" name="disponivel" value="sim" required>Sim</label>
                    <label><input type="radio" name="disponivel" value="nao" required>Não</label>
                </div>
            </section>

            <div id="btn">  
                    <input type="submit" name="cadastrar" value="Cadastrar">
                    <a href="./../../privado.php">Cancelar</a>
            </div>
        </form>

    </div>
    
</body>
</html>