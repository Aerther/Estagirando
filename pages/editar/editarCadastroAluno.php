<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Aluno;
use App\Classes\Preferencia;
use App\Classes\Curso;

session_start();

if(!isset($_SESSION["idUsuario"])) header("Location: ./../../index.php");

if($_SESSION["tipoUsuario"] != "aluno") header("Location: ./../../privado.php");

$mensagemErro = "";

if(isset($_POST['salvar'])) {
    $senha = isset($_POST["senha"]) ? $_POST["senha"] : "";
    
    $preferencias = isset($_POST["preferencias"]) ? $_POST["preferencias"] : [];
    $naoPreferencias = isset($_POST["naoPreferencias"]) ? $_POST["naoPreferencias"] : [];

    $usuario = new Aluno($_POST["email"], $_POST["senha"]);

    if(!$usuario->usuarioExiste()) {
        if(strlen($senha) < 8 && !empty($senha)) {
            $mensagemErro = "Senha deve possuir no mínimo 8 caracteres";

        } else if(!empty(array_intersect($preferencias, $naoPreferencias))) {
            $mensagemErro = "Você não pode selecionar o mesmo atributo tanto para Preferências e Não Preferências";

        } else {
            $usuario->atualizarAluno(
                $_POST["nome"],
                $_POST["sobrenome"],
                $_POST["email"],
                $preferencias,
                $naoPreferencias,
                $_POST["ano"],
                $_POST["cidadesEstagiar"],
                $_POST["turno"],
                $_POST["situacao"],
                $_POST["modalidade"],
                $_POST["curso"]
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

$aluno = Aluno::findAluno($_SESSION["idUsuario"]);
$preferencias = Preferencia::findAllPreferencias();
$cursos = Curso::findAllCursos();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Estagirando</title>

    <link rel="stylesheet" href="./../../src/styles/reset.css">
    <link rel= "stylesheet" href="./../../src/styles/styleEditar.css">

</head>
<body>
    <div class="container">
        <header>
            <section class="texto-inicial">
                <h2>Bem-vindo Aluno!</h2>
            </section>

            <section class="icones">
                <a href="./../pesquisa/pesquisa.php" title="Pesquisa avançada">
                    <img src="./../../icones/pesquisa.png" alt="Icone" class='iconeMenu' id='pesquisa' >
                </a>

                <a href="./../../solicitacoesOrientacao.php" title="Solicitações de orientação">
                    <img src="./../../icones/solicitacoes.png" alt="Icone" class='iconeMenu' id='solicitacoes'>
                </a>   

                <a href="./../visualizar/visualizarCadastro.php" title="Visualizar cadastro">
                    <img src="./../../icones/iconAluno.png" alt="Icone" class='iconeMenu' id='visualizar'>
                </a>

                <a href="./../../sair.php" title="Logout">
                    <img src="./../../icones/logout.png" alt="Icone" class='iconeMenu' id='logout'>
                </a>
            </section>
        </header>
        
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
                            <label for="modalidade">Modalidade:</label>

                            <?php 
                            
                            $opcoes = ["presencial" => "", "remoto" => "", "hibrido" => ""];

                            $opcoes[$aluno->getModalidade()] = "selected";

                            ?>

                            <select id="modalidade" name="modalidade">
                                <option value="presencial" <?php echo $opcoes["presencial"]; ?>>Presencial</option>
                                <option value="remoto" <?php echo $opcoes["remoto"]; ?>>Remoto</option>
                                <option value="hibrido" <?php echo $opcoes["hibrido"]; ?>>Híbrido</option>
                            </select>
                        </section>

                        <section class="more-space">
                            <label for="turno">Turno disponível:</label>

                            <?php 
                            
                            $opcoes = ["manha" => "", "tarde" => ""];

                            $opcoes[$aluno->getTurnoDisponivel()] = "selected";

                            ?>

                            <select id="turno" name="turno">
                                <option value="manha" <?php echo $opcoes["manha"]; ?>>Manhã</option>
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

                        <section>
                            <label for="senha">Senha:</label>
                            <input type="password" name="senha" placeholder="Se vazio, senha não muda">
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
                            <input type="number" name="ano" min='2015' max='2025' value="<?php echo $aluno->getAnoIngresso(); ?>" required>
                        </section>
                    </section>
                </section>

                <section class="preferencias">
                    <section class="cidades">
                        <p>Cidades Para Estagiar</p>

                        <input type="text" name="cidadeEstagiar" placeholder="Pesquisar Cidade...">

                        <ul class='sugestoes'></ul>
                        
                        <div>
                            <label><input type="checkbox" name="cidadesEstagiar[]" value="-1"> Qualquer Cidade</label>
                        </div>
                    </section>

                    <section>
                        <p>Preferências</p>

                        <div>
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
                        
                        <div>
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
</body>
</html>