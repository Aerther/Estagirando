<?php

namespace App\Classes;

use App\BD\MySQL;

class Cidade {

    private int $idCidade;
    private string $nome;
    private string $uf;

    public function __construct(string $nome, string $uf) {
        $this->nome = $nome;
        $this->uf = $uf;
    }

    // CRUD

    // Salvar
    public function salvarCidade() : void {
        $connection = new MySQL();

        $tipos = "ss";
        $params = [$this->nome, $this->uf];
        $sql = "INSERT INTO cidade (Nome, UF) VALUES (?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarCidade($nome, $uf) : void {
        $connection = new MySQL();

        $tipos = "ssi";
        $params = [$nome, $uf, $this->idCidade];
        $sql = "UPDATE cidade SET Nome = ?, UF = ? WHERE ID_Cidade = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Deletar
    public function deletarCidade() : void {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$this->idCidade];
        $sql = "DELETE FROM cidade WHERE ID_Cidade = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Curso
    public static function findCidade($idCidade) : Cidade {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$idCidade];
        $sql = "SELECT * FROM cidade c WHERE c.ID_Cidade = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $cidade = new Cidade($resultado["Nome"], $resultado["UF"]);
        $cidade->setIdCidade($resultado["ID_Cidade"]);

        return $cidade;
    }

    // Find All Cursos
    public static function findAllCidades($uf = "") : array {
        $connection = new MySQL();

        $cidades = [];

        $tipos = "s";
        $params = [$uf];
        $sql = "SELECT * FROM cidade c WHERE c.UF LIKE ? ORDER BY c.Nome";

        $resultados = $connection->search($sql, $tipos, $params);

        if(empty($resultados)) return [];

        foreach($resultados as $resultado) {
            $cidade = new Cidade($resultado["Nome"], $resultado["UF"]);
            $cidade->setIdCidade($resultado["ID_Cidade"]);

            $cidades[] = $cidade;
        }

        return $cidades;
    }

    // Getters e Setters
    
    // ID Curso
    public function getIdCurso() : int {
        return $this->idCidade;
    }

    public function setIdCidade($idCidade) : void {
        $this->idCidade = $idCidade;
    }

    // Nome Curso
    public function getNome() : string {
        return $this->nome;
    }

    public function setNome($nome) : void {
        $this->nome = $nome;
    }

    // UF Curso
    public function getUF() : string {
        return $this->uf;
    }

    public function setUF($uf) : void {
        $this->uf = $uf;
    }
}

?>