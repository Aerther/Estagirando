<?php

namespace App\Classes;

use App\BD\MySQL;

class Professor extends Usuario {

    private string $statusDisponibilidade;

    public function __construct(string $email, string $senha) {
        parent::__construct($email, $senha);
    }

    public function salvarUsuario(string $nome, string $sobrenome, string $tipoUsuario, array $preferencias, array $noPreferencias, string $statusDisponibilidade) : void {
        $idUsuario = parent::salvarUsuario($nome, $sobrenome, $tipoUsuario, $preferencias, $noPreferencias);

        $connection = new MySQL();

        $tipos = "is";
        $params = [$idUsuario, $statusDisponibilidade];
        $sql = "INSERT INTO professor(ID_Professor, Status_Disponibilidade) VALUES (?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    public function atualizarUsuario(string $nome, string $sobrenome, string $email, array $preferencias, array $noPreferencias, string $statusDisponibilidade) : void {
        parent::atualizarUsuario($nome, $sobrenome, $email, $preferencias, $noPreferencias);

        $connection = new MySQL();

        session_start();

        $tipos = "si";
        $params = [$statusDisponibilidade, $_SESSION["idUsuario"]];
        $sql = "UPDATE professor SET Status_Disponibilidade = ? WHERE ID_Professor = ?";

        $connection->execute($sql, $tipos, $params);
    }

}

?>