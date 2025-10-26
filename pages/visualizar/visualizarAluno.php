<?php

require_once __DIR__."/../../vendor/autoload.php";

use App\Classes\Aluno;

if(!isset($_GET["idUsuario"])) {
    header("Location: privado.php");
}

$aluno = Aluno::findAluno($_GET["idUsuario"]);

$foto = $aluno->getFoto();
$curso = $aluno->getCurso();
$preferencias = $aluno->getPreferencias();
$naoPreferencias = $aluno->getNaoPreferencias();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Visualizar Aluno</title>
</head>
<body>
    <div class="container">
        <main>
            <section class="linha-1">
                <section class="imagem">
                    <figure>
                        <?php echo "<img src='{$foto->getLinkFoto()}' alt='Foto do Aluno' />"; ?>
                    </figure>
                </section>

                <section class="dados-usuario">
                    <?php echo "<p>Nome: {$aluno->getNome()} <p class='status'>{$aluno->getStatusEstagio()}</p> </p>"; ?>
                    <?php echo "<p>Email: {$aluno->getEmail()}</p>"; ?>
                    <?php echo "<p>Curso: {$curso->getNome()}</p>"; ?>
                    <?php echo "<p>Ingressou em {$aluno->getAnoIngresso()}</p>"; ?>
                </section>
            </section>

            <section class="linha-2">
                <section class="disponibilidade">
                    <p class="titulo-dados">Disponibilidade do Estágio</p>
                    <?php echo "<p>Cidade: {$aluno->getCidadeEstagio()}</p>"; ?>
                    <?php echo "<p>Modalidade: {$aluno->getModalidade()}</p>"; ?>
                    <?php echo "<p>Turno: {$aluno->getTurnoDisponivel()}</p>"; ?>
                </section>

                <section class="preferencias">
                    <p class="titulo-dados">Preferências</p>
                    <?php

                    foreach($preferencias as $preferencia) {
                        echo "<p>{$preferencia}</p>";
                    }

                    ?>
                </section>

                <section class="nao-preferencias">
                    <p class="titulo-dados">Não Preferências</p>
                    <?php

                    foreach($naoPreferencias as $preferencia) {
                        echo "<p>{$preferencia}</p>";
                    }

                    ?>
                </section>
            </section>
        </main>
    </div>
</body>
</html>