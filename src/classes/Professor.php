<?php

namespace App\Classes;

use App\BD\MySQL;

class Professor extends Usuario {

    private string $statusDisponibilidade;

    public function __construct(string $email, string $senha) {
        parent::__construct($email, $senha);
    }

    // CRUD

    // Salvar
    public function salvarProfessor(
        string $nome, 
        string $sobrenome,
        int $idFoto,
        array $preferencias, 
        array $naoPreferencias, 
        string $statusDisponibilidade
    ) : void {
        $idUsuario = parent::salvarUsuario($nome, $sobrenome, "Professor", $idFoto, $preferencias, $naoPreferencias);

        $connection = new MySQL();

        $tipos = "is";
        $params = [$idUsuario, $statusDisponibilidade];
        $sql = "INSERT INTO professor (ID_Professor, Status_Disponibilidade) VALUES (?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarProfessor(
        string $nome, 
        string $sobrenome, 
        string $email, 
        array $preferencias, 
        array $naoPreferencias, 
        string $statusDisponibilidade
    ) : void {
        parent::atualizarUsuario($nome, $sobrenome, $email, $preferencias, $naoPreferencias);

        $connection = new MySQL();

        session_start();

        $tipos = "si";
        $params = [$statusDisponibilidade, $_SESSION["idUsuario"]];
        $sql = "UPDATE professor SET Status_Disponibilidade = ? WHERE ID_Professor = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Professor
    public static function findProfessor($idProfessor) : Professor {
        $usuario = parent::findUsuario($idProfessor);

        if(empty($usuario)) return null;

        $connection = new MySQL();

        $tipos = "i";
        $params = [$idProfessor];
        $sql = "SELECT * FROM professor p WHERE p.ID_Professor = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];
        
        $professor = new Professor($usuario->getEmail(), $usuario->getSenha());
        
        $professor->setIdUsuario( $usuario->getIdUsuario() );
        $professor->setNome( $usuario->getNome() );
        $professor->setTipoUsuario( $usuario->getTipoUsuario() );
        $professor->setLinkFoto( $usuario->getLinkFoto() );
        $professor->setStatusCadastro( $usuario->getStatusCadastro() );
        $professor->setPreferencias();

        $professor->setStatusDisponibilidade($resultado["Status_Disponibilidade"]);

        return $professor;
    }

    // Find All Professores
    public static function findAllProfessores() : array {
        $connection = new MySQL();

        $professores = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT p.*, u.*, f.* FROM professor p 
        JOIN usuario u ON u.ID_Usuario = p.ID_Professor 
        JOIN foto f ON f.ID_Foto = u.ID_Foto
        WHERE u.Status_Cadastro = 'ativo'";

        $resultados = $connection->search($sql, $tipos, $params);

        if(empty($resultados)) return null;

        foreach($resultados as $resultado) {
            $professor = new Professor($resultado["Email"], $resultado["Senha"]);
            
            $professor->setIdUsuario($resultado["ID_Usuario"]);
            $professor->setNome($resultado["Nome"]);
            $professor->setTipoUsuario($resultado["Tipo_Usuario"]);
            $professor->setLinkFoto($resultado["Link_Foto"]);
            $professor->setStatusCadastro($resultado["Status_Cadastro"]);
            $professor->setPreferencias();

            $professor->setStatusDisponibilidade($resultado["Status_Disponibilidade"]);

            $professores[] = $professor;
        }

        return $professores;
    }

    // Getters e Setters

    // Status Disponibilidade
    public function getStatusDisponibilidade() : string {
        return $this->statusDisponibilidade;
    }

    public function setStatusDisponibilidade($statusDisponibilidade) : void {
        $this->statusDisponibilidade = $statusDisponibilidade;
    }
}

?>