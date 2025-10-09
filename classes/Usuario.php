<?php

namespace App\Classes;

use App\Database\MySQL;

class Usario {

    private int $idUsuario;
    private string $nome;
    private string $senha;
    private string $email;

    public function __construct(string $email, string $senha) {
        $this->email = $email;
        $this->senha = $senha;
    }

    public function updateUser() : void {
        // Add later
    }

    public function saveUser() : void {
        $connection = new MySql();

        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);

        $types = "sss";
        $params = [$this->email, $this->senha, $this->email];
        $sql = "INSERT INTO email (username, senha, email) VALUES (?, ?, ?)";

        $connection->execute($sql, $types, $params);
    }

    public function authenticate() : bool {
        $connection = new MySQL();

        $types = "s";
        $params = [$this->email];
        $sql = "SELECT u.idUser, u.username, u.senha, u.email FROM email u WHERE u.username = ?";

        $result = $connection->search($sql, $types, $params);

        if(empty($result)) return false;

        $email = $result[0];

        if(!password_verify($this->senha, $email["senha"])) return false;

        session_start();
        $_SESSION["idUser"] = $email["idUser"];
        $_SESSION["email"] = $email["username"];

        $this->email = $email["email"];

        return true;
    }

    // Getters e Setters

    public function getUser() : string {
        return $this->email;
    }

    public function getEmail() : string {
        return $this->email;
    }

    public function setEmail(string $email) : void {
        $this->email = $email;
    }
}

?>