<?php

class Turma {

    private int $idTurma;
    private string $nomeTurma;

    public function __construct(string $nomeTurma) {
        $this->nomeTurma = $nomeTurma;
    }

    // CRUD

    // Salvar
    public function salvarTurma($nomeTurma) : void {
        $connection = new MySql();

        $tipos = "s";
        $params = [$nomeTurma];
        $sql = "INSERT INTO Turma(Nome) VALUES (?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarTurma($nomeTurma) : void {
        $connection = new MySql();

        $tipos = "si";
        $params = [$nomeTurma, $this->idTurma];
        $sql = "UPDATE Turma SET Nome = ? WHERE ID_Turma = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Deletar
    public function deletarTurma() : void {
        $connection = new MySql();

        $tipos = "i";
        $params = [$this->idTurma];
        $sql = "DELETE FROM Turma WHERE ID_Turma = ?";

        $connection->execute($sql, $tipos, $params);
    }

    public static function findTurma($idTurma) : Turma {
        $connection = new MySql();

        $tipos = "i";
        $params = [$idTurma];
        $sql = "SELECT * FROM Turma t WHERE t.ID_Turma = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $turma = new Turma($resultado["Nome"]);
        $turma->setIdTurma($resultado["ID_Turma"]);

        return $turma;
    }

    public static function findAllTurmas() : array {
        $connection = new MySql();

        $turmas = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT * FROM Turma";

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $turma = new Turma($resultado["Nome"]);

            $turma->setIdTurma($resultado['ID_Turma']);

            $turmas[] = $turma;
        }

        return $turmas;
    }

    // Getters e Setters

    // Nome Turma
    public function getNomeTurma() : string {
        return $this->nomeTurma;
    }

    public function setNomeTurma($nomeTurma) : void {
        $this->nomeTurma = $nomeTurma;
    }

    // ID Turma
    public function getIdTurma() : int {
        return $this->idTurma;
    }

    public function setIdTurma($idTurma) : void {
        $this->idTurma = $idTurma;
    }
}

?>