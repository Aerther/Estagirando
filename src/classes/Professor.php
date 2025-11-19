<?php

namespace App\Classes;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Usuario;

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
        string $dataNascimento,
        string $cpf,
        array $preferencias,
        array $naoPreferencias,
        string $statusDisponibilidade
    ) : void {
        $idUsuario = parent::salvarUsuario($nome, $sobrenome, $dataNascimento, $cpf,"professor", 1, $preferencias, $naoPreferencias);

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
        string $dataNascimento,
        string $cpf,
        array $preferencias, 
        array $naoPreferencias, 
        string $statusDisponibilidade
    ) : void {
        parent::atualizarUsuario($nome, $sobrenome, $email, $dataNascimento, $cpf, $preferencias, $naoPreferencias);

        $connection = new MySQL();

        if(session_status() != 2) session_start();

        $tipos = "si";
        $params = [$statusDisponibilidade, $_SESSION["idUsuario"]];
        $sql = "UPDATE professor SET Status_Disponibilidade = ? WHERE ID_Professor = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Professor
    public static function findProfessor($idProfessor) : Professor {
        $usuario = parent::findUsuario($idProfessor);

        $connection = new MySQL();

        $tipos = "i";
        $params = [$idProfessor];
        $sql = "SELECT * FROM professor p WHERE p.ID_Professor = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];
        
        $professor = new Professor($usuario->getEmail(), $usuario->getSenha());
        
        $professor->setIdUsuario( $usuario->getIdUsuario() );
        $professor->setNome( $usuario->getNome() );
        $professor->setSobrenome( $usuario->getSobrenome() );
        $professor->setTipoUsuario( $usuario->getTipoUsuario() );
        $professor->setIdFoto( $usuario->getIdFoto() );
        $professor->setStatusCadastro( $usuario->getStatusCadastro() );
        $professor->setDataNascimento( $usuario->getDataNascimento() );
        $professor->setCPF( $usuario->getCPF() );
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
        JOIN usuario2 u ON u.ID_Usuario = p.ID_Professor 
        JOIN foto f ON f.ID_Foto = u.ID_Foto
        WHERE u.Status_Cadastro = 'ativo'";

        $resultados = $connection->search($sql, $tipos, $params);

        if(empty($resultados)) return [];

        foreach($resultados as $resultado) {
            $professor = new Professor($resultado["Email"], $resultado["Senha"]);
            
            $professor->setIdUsuario($resultado["ID_Usuario"]);
            $professor->setNome($resultado["Nome"]);
            $professor->setSobrenome($resultado["Sobrenome"]);
            $professor->setTipoUsuario($resultado["Tipo_Usuario"]);
            $professor->setStatusCadastro($resultado["Status_Cadastro"]);
            $professor->setPreferencias();

            $professor->setStatusDisponibilidade($resultado["Status_Disponibilidade"]);

            $professores[] = $professor;
        }

        return $professores;
    }

    public static function pesquisar($nome, $email, $preferencias, $naoPreferencias) : array {
        $connection = new MySQL();

        $professores = [];

        $parts = explode(" ", $nome, 2);
        $nome = $parts[0];
        $sobrenome = isset($parts[1]) ? $parts[1] : $parts[0];

        $placeholders1 = implode(',', array_fill(0, count($preferencias), '?'));
        $placeholders2 = implode(',', array_fill(0, count($naoPreferencias), '?'));

        $tipos = "sss" . str_repeat('i', count($preferencias) * 2 + count($naoPreferencias) * 2);;

        $params = ["%{$nome}%", "%{$sobrenome}%", "%{$email}%", ...$preferencias, ...$preferencias, ...$naoPreferencias, ...$naoPreferencias];
        $sql = "
        SELECT p.*, u.*, (
            (CASE WHEN u.Nome LIKE ? THEN 10 ELSE 0 END) +
            (CASE WHEN u.Sobrenome LIKE ? THEN 10 ELSE 0 END) +
            (CASE WHEN u.Email LIKE ? THEN 10 ELSE 0 END)
        ) + SUM(
            (CASE WHEN up.ID_Preferencia IN ({$placeholders1}) AND up.Prefere = 'sim' THEN 1 ELSE 0 END) +
            (CASE WHEN up.ID_Preferencia IN ({$placeholders1}) AND up.Prefere = 'nao' THEN -1 ELSE 0 END) +
            (CASE WHEN up.ID_Preferencia IN ({$placeholders2}) AND up.Prefere = 'nao' THEN 1 ELSE 0 END) +
            (CASE WHEN up.ID_Preferencia IN ({$placeholders2}) AND up.Prefere = 'sim' THEN -1 ELSE 0 END)
        ) AS pontos
        FROM professor p
        LEFT JOIN usuario_preferencia up
        ON p.ID_Professor = up.ID_Usuario
        LEFT JOIN usuario u ON u.ID_Usuario = p.ID_Professor
        GROUP BY p.ID_Professor
        ORDER BY pontos DESC
        ";

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $professor = new Professor($resultado["Email"], $resultado["Senha"]);
            
            $professor->setIdUsuario($resultado["ID_Usuario"]);
            $professor->setNome($resultado["Nome"]);
            $professor->setSobrenome($resultado["Sobrenome"]);
            $professor->setTipoUsuario($resultado["Tipo_Usuario"]);
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