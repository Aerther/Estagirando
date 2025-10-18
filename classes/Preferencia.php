<?php

class Preferencia {

    private int $idPreferencia;
    private string $descricaoPreferencia;

    public function __construct(string $descricaoPreferencia, string $linkFoto) {
        $this->descricaoPreferencia = $descricaoPreferencia;
    }

    // CRUD

    // Salvar
    public function salvarPreferencia($descricaoPreferencia) : void {
        $connection = new MySql();

        $tipos = "s";
        $params = [$descricaoPreferencia];
        $sql = "INSERT INTO Preferencia(Descricao) VALUES (?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarPreferencia($descricaoPreferencia) : void {
        $connection = new MySql();

        $tipos = "si";
        $params = [$descricaoPreferencia, $this->idPreferencia];
        $sql = "UPDATE Preferencia SET Descricao = ? WHERE ID_Preferencia = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Deletar
    public function deletarPreferencia() : void {
        $connection = new MySql();

        $tipos = "i";
        $params = [$this->idPreferencia];
        $sql = "DELETE FROM Preferencia WHERE ID_Preferencia = ?";

        $connection->execute($sql, $tipos, $params);
    }

    public static function findPreferencia($idPreferencia) : Preferencia {
        $connection = new MySql();

        $tipos = "i";
        $params = [$idPreferencia];
        $sql = "SELECT * FROM Preferencia p WHERE p.ID_Preferencia = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $preferencia = new Foto($resultado["Descricao"]);
        $preferencia->setIdPreferencia($resultado["ID_Preferencia"]);

        return $preferencia;
    }

    public static function findAllFotos() : array {
        $connection = new MySql();

        $preferencias = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT * FROM Preferencia";

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $preferencia = new Preferencia($resultado["Descricao"]);

            $preferencia->setIdPreferencia($resultado['ID_Prferencia']);

            $preferencias[] = $preferencia;
        }

        return $preferencias;
    }

    // Getters e Setters

    // Descrição
    public function getDescricao() : string {
        return $this->descricaoPreferencia;
    }

    public function setDescricao($descricaoPreferencia) : void {
        $this->descricaoPreferencia = $descricaoPreferencia;
    }

    // ID Preferencia
    public function getIdPreferencia() : int {
        return $this->idPreferencia;
    }

    public function setIdPreferencia($idPreferencia) : void {
        $this->idPreferencia = $idPreferencia;
    }
}

?>