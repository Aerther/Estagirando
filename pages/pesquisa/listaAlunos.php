<?php

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if(!isset($_SESSION["ultima_pesquisa"])) header("Location: ./../../privado.php");

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Aluno;

$resultado = $_SESSION["ultima_pesquisa"];

$nome = $resultado["nome"];
$email = $resultado["email"];
$turno = $resultado["turno"];
$turnoDisp = "";
if($turno=="manhã"){
    $turnoDisp="Disponível pela manhã";
}else{
    $turnoDisp="Disponível à tarde";
}
$modalidades = $resultado["modalidades"];
$cidades = $resultado["cidades"];
$cursos = $resultado["cursos"];
$preferencias = $resultado["preferencias"];
$naoPreferencias = $resultado["naoPreferencias"];

$alunos = Aluno::pesquisar($nome, $email, $turno, $cursos, $modalidades, $cidades, $preferencias, $naoPreferencias);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel="stylesheet" href="./../../src/styles/styleListagem.css">

    <title>Listagem Alunos</title>
</head>
<body>
    <div class="container">
        <?php

        $URL_BASE = "./../..";

        require_once __DIR__ . "/../../menu.php";

        ?>

        <main>
            <div class="usuarios">
                <?php

                foreach($alunos as $aluno) {

                    $foto = $aluno->getFoto();
                    $curso = $aluno->getCurso();
                    $disponibilidade = $aluno->getStatusEstagio();

                        if($disponibilidade == 'procurando estágio'){
                            $cor = "green";
                        } else{
                            $cor = "red";
                        }

                    /*// Preferências como String

                    $limite = 2;

                    $preferencias = "";
                    $naoPreferencias = "";

                    $i = 0;
                    foreach($aluno->getPreferencias() as $descricao) {
                        if($i == $limite) break;

                        $preferencias .= $descricao . ", ";

                        $i++;
                    }

                    $i = 0;
                    foreach($aluno->getNaoPreferencias() as $descricao) {
                        if($i == $limite) break;

                        $naoPreferencias .= $descricao . ", ";

                        $i++;
                    }

                    $preferencias = substr($preferencias, 0, -2);
                    $naoPreferencias = substr($naoPreferencias, 0, -2);*/

                    echo "<div class='usuario'>";

                    echo "<div class='imagem'";

                    echo "<a href='./../visualizar/visualizarAluno.php?id={$aluno->getIdUsuario()}'> <figure><img src='./../../{$foto->getLinkFoto()}' alt='Foto Professor' /></figure> </a>";

                    echo "</div>";

                    echo "<div class='dados'>";
                    
                    echo "<a href='./../visualizar/visualizarAluno.php?id={$aluno->getIdUsuario()}' class=nome>" . $aluno->getNome() . " ". $aluno->getSobrenome()."</a>";

                    echo "<p>{$aluno->getEmail()}</p>";

                    echo "<p>{$curso->getNome()}</p>";

                    echo "</div>";
                    echo "<div class='dados'>";
                    
                    echo "<p><span class='disponibilidade' style='color: {$cor}; border: 2px solid {$cor}'>{$disponibilidade}</span></p>";

                    echo "<p>{$turnoDisp}</p>";
                    echo "</div>";

                   /* echo "<div class='preferencias'>";

                    echo "<p>Preferências: {$preferencias}</p>";

                    echo "<p>Não Preferências: {$naoPreferencias}</p>";

                    echo "</div>";*/
                    
                    echo "</div>";
                }

                ?>
            </div>
        </main>
    </div>
    
</body>
</html>