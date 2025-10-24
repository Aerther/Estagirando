<?php

namespace App\Classes;

use App\BD\MySQL;

class Turma {

    private int $idTurma;
    private string $nome;

    public function __construct(string $nome) {
        $this->nome = $nome;
    }

    // CRUD

    // Salvar
    public function salvarTurma() : void {
        $connection = new MySQL();

        $tipos = "s";
        $params = [$this->nome];
        $sql = "INSERT INTO Turma (Nome) VALUES (?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarTurma($nome) : void {
        $connection = new MySQL();

        $tipos = "si";
        $params = [$nome, $this->idTurma];
        $sql = "UPDATE Turma SET Nome = ? WHERE ID_Turma = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Deletar
    public function deletarTurma() : void {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$this->idTurma];
        $sql = "DELETE FROM Turma WHERE ID_Turma = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Turma
    public static function findTurma($idTurma) : Turma {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$idTurma];
        $sql = "SELECT * FROM Turma t WHERE t.ID_Turma = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $turma = new Turma($resultado["Nome"]);
        $turma->setIdTurma($resultado["ID_Turma"]);

        return $turma;
    }

    // Find All Turmas
    public static function findAllTurmas() : array {
        $connection = new MySQL();

        $turmas = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT * FROM Turma";

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $turma = new Turma($resultado["Nome"]);
            $turma->setIdTurma($resultado["ID_Turma"]);

            $turmas[] = $turma;
        }

        return $turmas;
    }

    // Getters e Setters
    
    // ID Turma
    public function getIdTurma() : int {
        return $this->idTurma;
    }

    public function setIdTurma($idTurma) : void {
        $this->idTurma = $idTurma;
    }

    // Nome Turma
    public function getNome() : string {
        return $this->nome;
    }

    public function setNome($nome) : void {
        $this->nome = $nome;
    }
}

?>