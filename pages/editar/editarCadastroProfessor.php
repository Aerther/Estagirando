<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "Professor") header("Location: ./../../privado.php");

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
        <header>
            <section class="texto-inicial">
                <h2>Bem-vindo Professor!</h2>
            </section>

            <section class="icones">
                <a href="./../pesquisa/pesquisa.php">
                    <img src="./../../icones/pesquisa.png" alt="Icone" class='iconeMenu' id='pesquisa'>
                </a>

                <a href="./../../solicitacoesOrientacao.php">
                    <img src="./../../icones/solicitacoes.png" alt="Icone" class='iconeMenu' id='solicitacoes'>
                </a>

                <a href="./../editar/editarCadastro.php">
                    <img src="./../../icones/edicao.png" alt="Icone" class='iconeMenu' id='edicao'>
                </a>   

                <a href="./../visualizar/visualizarCadastro.php">
                    <img src="./../../icones/iconProf.png" alt="Icone" class='iconeMenu' id='visualizar'>
                </a>

                <a href="./../../sair.php">
                    <img src="./../../icones/logout.png" alt="Icone" class='iconeMenu' id='logout'>
                </a>
            </section>
        </header>
        
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

                    <section>
                        <label for="senha">Senha:</label>
                        <input type="password" name="senha" placeholder="Se vazio, senha não muda">
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