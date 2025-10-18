<?php

// Colocar os requires e talvez namespace

class Aluno extends Usuario {

    private string $cidadeResidencia;
    private string $turnoDisponivel;
    private string $statusEstagio;
    private int $idTurma;

    public function __construct(string $email, string $senha) {
        Usuario::__construct($email, $senha);
    }

    public function salvarUsuario(string $nome, string $sobrenome, string $tipoUsuario, array $preferencias, array $noPrefencias, string $cidadeResidencia, string $turnoDisponivel, string $statusEstagio, int $idTurma) : void {
        $idUsuario = Usuario::salvarUsuario($nome, $sobrenome, $tipoUsuario, $preferencias, $noPrefencias);

        $connection = new MySql();

        $tipos = "isssi";
        $params = [$idUsuario, $cidadeResidencia, $turnoDisponivel, $statusEstagio, $idTurma];
        $sql = "INSERT INTO aluno(ID_Aluno, Cidade_Residencia, Turno_Disponivel, Status_Estagio, ID_Turma) VALUES (?, ?, ?, ?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    public function atualizarUsuario(string $nome, string $sobrenome, string $email) : void {
        Usuario::atualizarUsuario($nome, $sobrenome, $email, );

        $connection = new MySql();

        $tipos = "";
        $params = [];
        $sql = "UPDATE aluno";

        $connection->execute($sql, $tipos, $params);
    }

    public function mandarSolicitacaoOrientacao() {
        $connection = new MySql();

        $tipos = "";
        $params = "";
        $sql = "";
    }

}

?>