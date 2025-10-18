<?php

namespace App\Classes;

use App\BD\MySQL;

class Foto {

    private int $idFoto;
    private string $nomeFoto;
    private string $linkFoto;

    public function __construct(string $nomeFoto, string $linkFoto) {
        $this->nomeFoto = $nomeFoto;
        $this->linkFoto = $linkFoto;
    }

    // CRUD

    // Salvar
    public function salvarFoto() : void {
        $connection = new MySQL();

        $tipos = "ss";
        $params = [$this->nomeTurma, $this->linkFoto];
        $sql = "INSERT INTO Foto(Nome_Foto, Link_Foto) VALUES (?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarFoto($nomeFoto, $linkFoto) : void {
        $connection = new MySQL();

        $tipos = "ssi";
        $params = [$nomeTurma, $linkFoto, $this->idFoto];
        $sql = "UPDATE Foto SET Nome_Foto = ?, Link_Foto = ? WHERE ID_Foto = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Deletar
    public function deletarFoto() : void {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$this->idFoto];
        $sql = "DELETE FROM Foto WHERE ID_Foto = ?";

        $connection->execute($sql, $tipos, $params);
    }

    public static function findFoto($idFoto) : Foto {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$idFoto];
        $sql = "SELECT * FROM Foto f WHERE f.ID_Foto = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $foto = new Foto($resultado["Nome_Foto"], $resultado["Link_Foto"]);
        $foto->setIdFoto($resultado["ID_Foto"]);

        return $foto;
    }

    public static function findAllFotos() : array {
        $connection = new MySQL();

        $fotos = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT * FROM Foto";

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $foto = new Foto($resultado["Nome_Foto"], $resultado["Link_Foto"]);

            $foto->setIdFoto($resultado['ID_Foto']);

            $fotos[] = $foto;
        }

        return $fotos;
    }

    // Getters e Setters

    // Nome Turma
    public function getNomeFoto() : string {
        return $this->nomeFoto;
    }

    public function setNomeFoto($nomeFoto) : void {
        $this->nomeFoto = $nomeFoto;
    }

    // ID Foto
    public function getIdFoto() : int {
        return $this->idFoto;
    }

    public function setIdFoto($idFoto) : void {
        $this->idFoto = $idFoto;
    }

    // Link Foto
    public function getLinkFoto() : string {
        return $this->linkFoto;
    }

    public function setLinkFoto($linkFoto) : void {
        $this->linkFoto = $linkFoto;
    }
}

?>