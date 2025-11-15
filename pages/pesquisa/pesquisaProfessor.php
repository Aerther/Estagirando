<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Professor;
use App\Classes\Preferencia;

$mensagemErro = "";


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
            <h1>Pesquisa Avançada de Professores</h1>
        </header>

        <main>
            <form action="./pesquisaProfessor.php" method="post">
                <section class="dados">
                    <section>
                        <label for="nome">Nome Completo:</label>
                        <input type="text" name="nome" value="<?php if (isset($_POST['nome']))
                            echo htmlspecialchars($_POST['nome']); ?>">
                    </section>

                    <section>
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?php if (isset($_POST['email']))
                            echo htmlspecialchars($_POST['email']); ?>">
                    </section>

                    <section class="radio">
                        <label for="disponivel">Situação atual:</label>

                        <div class="disponibilidade">
                            <?php 

                            $opcoes = ["sim" => "", "nao" => ""];

                            if(isset($_POST["disponivel"])) $opcoes[$_POST["disponivel"]] = "checked";
                            
                            echo "<label><input type='radio' name='disponivel' value='sim' {$opcoes['sim']} >Disponível para orientar</label>";
                            echo "<label><input type='radio' name='disponivel' value='nao' {$opcoes['nao']} >Não disponível para orientar</label>";
                            
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
                                echo "<label><input type='checkbox' name='preferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>

                    <section>
                        <p>Não preferências</p>

                        <div>
                            <?php

                            foreach ($preferencias as $preferencia) {
                                echo "<label><input type='checkbox' name='naoPreferencias[]' value={$preferencia->getIdPreferencia()}> {$preferencia->getDescricao()}</label>";
                            }

                            ?>
                        </div>
                    </section>
                </section>

                <section id="btn" class="links">
                    <?php echo "<p class='erro'>{$mensagemErro}</p>"; ?>

                    <div>
                        <input class="link" type="submit" name="pesquisar" value="Pesquisar">
                        <a class="link" href="./../../privado.php" id='cancelar'>Cancelar</a>
                    </div>
                </section>
            </form>
        </main>
    </div>
</body>
</html>
