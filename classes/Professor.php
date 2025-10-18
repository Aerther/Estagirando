<?php

// Colocar os requires e talvez namespace

class Professor extends Usuario {

    private string $statusDisponibilidade;

    public function __construct(string $statusDisponibilidade) {
        $this->statusDisponibilidade = $statusDisponibilidade;
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

}

?>