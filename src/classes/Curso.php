<?php

namespace App\Classes;

use App\BD\MySQL;

class Curso {

    private int $idCurso;
    private string $nome;

    public function __construct(string $nome) {
        $this->nome = $nome;
    }

    // CRUD

    // Salvar
    public function salvarCurso() : void {
        $connection = new MySQL();

        $tipos = "s";
        $params = [$this->nome];
        $sql = "INSERT INTO Curso (Nome) VALUES (?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarCurso($nome) : void {
        $connection = new MySQL();

        $tipos = "si";
        $params = [$nome, $this->idCurso];
        $sql = "UPDATE Curso SET Nome = ? WHERE ID_Curso = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Deletar
    public function deletarCurso() : void {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$this->idCurso];
        $sql = "DELETE FROM Curso WHERE ID_Curso = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Curso
    public static function findCurso($idCurso) : Curso {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$idCurso];
        $sql = "SELECT * FROM Curso c WHERE c.ID_Curso = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $curso = new Curso($resultado["Nome"]);
        $curso->setIdCurso($resultado["ID_Curso"]);

        return $curso;
    }

    // Find All Cursos
    public static function findAllCursos() : array {
        $connection = new MySQL();

        $cursos = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT * FROM Curso";

        $resultados = $connection->search($sql, $tipos, $params);

        if(empty($resultados)) return [];

        foreach($resultados as $resultado) {
            $curso = new Curso($resultado["Nome"]);
            $curso->setIdCurso($resultado["ID_Curso"]);

            $cursos[] = $curso;
        }

        return $cursos;
    }

    // Getters e Setters
    
    // ID Curso
    public function getIdCurso() : int {
        return $this->idCurso;
    }

    public function setIdCurso($idCurso) : void {
        $this->idCurso = $idCurso;
    }

    // Nome Curso
    public function getNome() : string {
        return $this->nome;
    }

    public function setNome($nome) : void {
        $this->nome = $nome;
    }
}

?>