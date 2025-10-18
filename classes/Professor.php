<?php

// Colocar os requires e talvez namespace

class Professor extends Usuario {

    private string $statusDisponibilidade;

    public function __construct(string $statusDisponibilidade) {
        $this->statusDisponibilidade = $statusDisponibilidade;
    }

    public function salvarUsuario(string $nome, string $sobrenome, string $tipoUsuario, array $preferencias, array $noPrefencias, string $statusDisponibilidade) : void {
        $idUsuario = Usuario::salvarUsuario($nome, $sobrenome, $tipoUsuario, $preferencias, $noPrefencias);

        $connection = new MySql();

        $tipos = "is";
        $params = [$idUsuario, $statusDisponibilidade];
        $sql = "INSERT INTO professor(ID_Professor, Status_Disponibilidade) VALUES (?, ?)";

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

}

?>