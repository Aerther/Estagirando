<?php

namespace App\Classes;

use App\BD\MySQL;

class Foto {

    private int $idFoto;
    private string $nome;
    private string $linkFoto;

    public function __construct(string $nome, string $linkFoto) {
        $this->nome = $nome;
        $this->linkFoto = $linkFoto;
    }

    // CRUD

    // Salvar
    public function salvarFoto() : void {
        $connection = new MySQL();

        $tipos = "ss";
        $params = [$this->nome, $this->linkFoto];
        $sql = "INSERT INTO Foto (Nome_Foto, Link_Foto) VALUES (?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarFoto($nome, $linkFoto) : void {
        $connection = new MySQL();

        $tipos = "ssi";
        $params = [$nome, $linkFoto, $this->idFoto];
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

    // Find Foto
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

    // Find All Fotos
    public static function findAllFotos() : array {
        $connection = new MySQL();

        $fotos = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT * FROM Foto";

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $foto = new Foto($resultado["Nome_Foto"], $resultado["Link_Foto"]);
            $foto->setIdFoto($resultado["ID_Foto"]);

            $fotos[] = $foto;
        }

        return $fotos;
    }

    // Getters e Setters

    // ID Foto
    public function getIdFoto() : int {
        return $this->idFoto;
    }

    public function setIdFoto($idFoto) : void {
        $this->idFoto = $idFoto;
    }

    // Nome Foto
    public function getNome() : string {
        return $this->nome;
    }

    public function setNome($nome) : void {
        $this->nome = $nome;
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