<?php

namespace App\Classes;

use App\BD\MySQL;

class Aluno extends Usuario {

    private string $cidadeEstagio;
    private string $turnoDisponivel;
    private string $statusEstagio;
    private int $idTurma;

    public function __construct(string $email, string $senha) {
        Usuario::__construct($email, $senha);
    }

    public function salvarAluno(string $nome, string $sobrenome, array $preferencias, array $noPreferencias, string $cidadeEstagio, string $turnoDisponivel, string $statusEstagio, int $idTurma) : void {
        $idUsuario = parent::salvarUsuario($nome, $sobrenome, "Aluno", $preferencias, $noPreferencias);

        $connection = new MySQL();

        $tipos = "isssi";
        $params = [$idUsuario, $cidadeEstagio, $turnoDisponivel, $statusEstagio, $idTurma];
        $sql = "INSERT INTO aluno(ID_Aluno, Cidade_Residencia, Turno_Disponivel, Status_Estagio, ID_Turma) VALUES (?, ?, ?, ?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    public function atualizarAluno(string $nome, string $sobrenome, string $email, array $preferencias, array $noPreferencias, string $cidadeEstagio, string $turnoDisponivel, string $statusEstagio, int $idTurma) : void {
        parent::atualizarUsuario($nome, $sobrenome, $email, $preferencias, $noPreferencias);

        $connection = new MySQL();

        if(session_status() != 2) session_start();

        $tipos = "sssii";
        $params = [$cidadeEstagio, $turnoDisponivel, $statusEstagio, $idTurma, $_SESSION["idUsuario"]];
        $sql = "UPDATE aluno SET Cidade_Residencia = ?, Turno_Disponivel = ?, Status_Estagio = ?, ID_Turma = ? WHERE ID_Aluno = ?";

        $connection->execute($sql, $tipos, $params);
    }

    public static function findAluno($idAluno) : Aluno {
        $usuario = Usuario::findUsuario($idAluno);

        $connection = new MySql();

        $tipos = "i";
        $params = [$usuario->getIdUsuario()];
        $sql = "SELECT a.*, f.* FROM aluno a JOIN foto f ON f.ID_Foto = a.ID_Foto WHERE a.ID_Aluno = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $aluno = new Aluno();

        $aluno->setIdUsuario($resultado["ID_Aluno"]);
        $aluno->setCidadeEstagio($resultado["Cidade_Estagio"]);
        $aluno->setStatusEstagio($resultado["Status_Estagio"]);
        $aluno->setTurnoEstagio($resultado["Turno_Estagio"]);
        $aluno->setIdTurma($resultado["ID_Turma"]);
        
        return $aluno;
    } 

    // Getters e Setters

    // ID Turma
    public function getIdTurma() : int {
        return $this->idTurma;
    }

    public function setIdTurma(int $idTurma) : void {
        $this->idTurma = $idTurma;
    }

    // Cidade Estágio
    public function getCidadeEstagio() : string {
        return $this->cidadeEstagio;
    }

    public function setCidadeEstagio(string $cidadeEstagio) : void {
        $this->cidadeEstagio = $cidadeEstagio;
    }

    // Status Estagio
    public function getStatusEstagio() : string {
        return $this->statusEstagio;
    }

    public function setStatusEstagio(string $statusEstagio) : void {
        $this->statusEstagio = $statusEstagio;
    }

    // Turno Estagio
    public function getTurnoEstagio() : string {
        return $this->turnoEstagio;
    }

    public function setTurnoEstagio(string $turnoEstagio) : void {
        $this->turnoEstagio = $turnoEstagio;
    }

}

?>