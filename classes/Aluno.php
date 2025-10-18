<?php

// Colocar os requires e talvez namespace

class Aluno extends Usuario {

    private string $cidadeResidencia;
    private string $turnoDisponivel;
    private string $statusEstagio;
    private int $idTurma;

    public function __construct(string $cidadeResidencia, string $turnoDisponivel, string $statusEstagio, int $idTurma) {
        $this->cidadeResidencia = $cidadeResidencia;
        $this->turnoDisponivel = $turnoDisponivel;
        $this->statusEstagio = $statusEstagio;
        $this->idTurma = $idTurma;
    }

    public function salvarUsuario() : void {
        Usuario::salvarUsuario();

        $connection = new MySql();

        $tipos = "";
        $params = [];
        $sql = "INSERT INTO aluno";

        $connection->execute($sql, $tipos, $params);
    }

    public function atualizarUsuario() : void {
        Usuario::atualizarUsuario();

        $connection = new MySql();

        $tipos = "";
        $params = [];
        $sql = "UPDATE aluno";

        $connection->execute($sql, $tipos, $params);
    }

    public function mandarSolicitacaoOrientacao() {
        
    }

}

?>