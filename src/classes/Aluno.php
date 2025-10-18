<?php

namespace App\Classes;

use App\BD\MySQL;

class Aluno extends Usuario {

    private string $cidadeResidencia;
    private string $turnoDisponivel;
    private string $statusEstagio;
    private int $idTurma;

    public function __construct(string $email, string $senha) {
        Usuario::__construct($email, $senha);
    }

    public function salvarUsuario(string $nome, string $sobrenome, string $tipoUsuario, array $preferencias, array $noPreferencias, string $cidadeResidencia, string $turnoDisponivel, string $statusEstagio, int $idTurma) : void {
        $idUsuario = parent::salvarUsuario($nome, $sobrenome, $tipoUsuario, $preferencias, $noPreferencias);

        $connection = new MySQL();

        $tipos = "isssi";
        $params = [$idUsuario, $cidadeResidencia, $turnoDisponivel, $statusEstagio, $idTurma];
        $sql = "INSERT INTO aluno(ID_Aluno, Cidade_Residencia, Turno_Disponivel, Status_Estagio, ID_Turma) VALUES (?, ?, ?, ?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    public function atualizarUsuario(string $nome, string $sobrenome, string $email, array $preferencias, array $noPreferencias, string $cidadeResidencia, string $turnoDisponivel, string $statusEstagio, int $idTurma) : void {
        parent::atualizarUsuario($nome, $sobrenome, $email, $preferencias, $noPreferencias);

        $connection = new MySQL();

        session_start();

        $tipos = "sssii";
        $params = [$cidadeResidencia, $turnoDisponivel, $statusEstagio, $idTurma, $_SESSION["idUsuario"]];
        $sql = "UPDATE aluno SET Cidade_Residencia = ?, Turno_Disponivel = ?, Status_Estagio = ?, ID_Turma = ? WHERE ID_Aluno = ?";

        $connection->execute($sql, $tipos, $params);
    }

}

?>