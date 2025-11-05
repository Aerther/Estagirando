<?php

namespace App\Classes;

use App\BD\MySQL;

class Preferencia {

    private int $idPreferencia;
    private string $descricao;

    public function __construct(string $descricao) {
        $this->descricao = $descricao;
    }

    // CRUD

    // Salvar
    public function salvarPreferencia() : void {
        $connection = new MySQL();

        $tipos = "s";
        $params = [$this->descricao];
        $sql = "INSERT INTO Preferencia (Descricao) VALUES (?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarPreferencia($descricao) : void {
        $connection = new MySQL();

        $tipos = "si";
        $params = [$descricao, $this->idPreferencia];
        $sql = "UPDATE Preferencia SET Descricao = ? WHERE ID_Preferencia = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Deletar
    public function deletarPreferencia() : void {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$this->idPreferencia];
        $sql = "DELETE FROM Preferencia WHERE ID_Preferencia = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Preferencia
    public static function findPreferencia($idPreferencia) : Preferencia {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$idPreferencia];
        $sql = "SELECT * FROM Preferencia p WHERE p.ID_Preferencia = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $preferencia = new Preferencia($resultado["Descricao"]);
        $preferencia->setIdPreferencia($resultado["ID_Preferencia"]);

        return $preferencia;
    }

    // Find All Preferencias
    public static function findAllPreferencias() : array {
        $connection = new MySQL();

        $preferencias = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT * FROM preferencia p ORDER BY p.Descricao";

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $preferencia = new Preferencia($resultado["Descricao"]);
            $preferencia->setIdPreferencia($resultado["ID_Preferencia"]);

            $preferencias[] = $preferencia;
        }

        return $preferencias;
    }

    // Getters e Setters

    // ID Preferencia
    public function getIdPreferencia() : int {
        return $this->idPreferencia;
    }

    public function setIdPreferencia($idPreferencia) : void {
        $this->idPreferencia = $idPreferencia;
    }

    // Descrição
    public function getDescricao() : string {
        return $this->descricao;
    }

    public function setDescricao($descricao) : void {
        $this->descricao = $descricao;
    }
}

?>