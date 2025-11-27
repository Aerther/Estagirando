<?php

if(session_status() != 2) session_start();

// Link Pesquisa 
$lp = $_SESSION["tipoUsuario"] == "aluno" ? "pesquisaProfessor.php" : "pesquisaAluno.php";

// Link Editar Cadastro
$lec = $_SESSION["tipoUsuario"] == "aluno" ? "editarCadastroAluno.php" : "editarCadastroProfessor.php";

// Link Icone do Usuario
$liu = $_SESSION["tipoUsuario"] == "aluno" ? "iconAluno.png" : "iconProf.png";

// Link Visualizar Cadastro
$lvc = ($_SESSION["tipoUsuario"] == "aluno" ? "visualizarAluno.php" : "visualizarProfessor.php") . "?id={$_SESSION['idUsuario']}";

?>

<link rel="stylesheet" href="<?php echo $URL_BASE; ?>/src/styles/menu.css">

<header>
    <section class="texto-inicial">
        <?php echo "<h2>Bem-vindo {$_SESSION['tipoUsuario']}!</h2>"; ?>
    </section>

    <section class="icones">
        <?php 
        
        echo "<a href='{$URL_BASE}/pages/pesquisa/{$lp}' title='Pesquisa'>";
        echo "<img src='{$URL_BASE}/icones/pesquisa.png' alt='Pesquisa' class='icone-menu'>";
        echo "</a>";

        echo "<a href='https://billyorg.com/2025/projeto/grupo2/index.php?idUsuario={$_SESSION['idUsuario']}' title='Solicitações de Orientação'>";
        echo "<img src='{$URL_BASE}/icones/solicitacoes.png' alt='Solicitação de Orientação' class='icone-menu'>";
        echo "</a>";

        echo "<a href='{$URL_BASE}/pages/visualizar/{$lvc}' title='Visualizar Cadastro'>";
        echo "<img src='{$URL_BASE}/icones/{$liu}' alt='Visualizar Cadastro' class='icone-menu'>";
        echo "</a>";

        echo "<a href='{$URL_BASE}/' title='Logout'>";
        echo "<img src='{$URL_BASE}/icones/logout.png' alt='Logout' class='icone-menu'>";
        echo "</a>";

        ?>
    </section>
</header>