<?php

// Colocar os requires e talvez namespace

class Usuario {

    private int $idUsuario;
    private string $nome;
    private string $senha;
    private string $email;
    private string $linkFoto;
    private string $tipoUsuario;
    private string $statusCadastro;

    public function __construct(string $email, string $senha) {
        $this->email = $email;
        $this->senha = $senha;
    }

    public function atualizarUsuario(string $nome, string $sobrenome, string $email) : void {
        $conexao = new MySql();

        session_start();

        $tipos = "sssi";
        $params = [$nome, $sobrenome, $email, $_SESSION["idUsuario"]];
        $sql = "UPDATE Usuario SET Nome = ?,  Sobrenome = ?, Email = ? WHERE ID_Usuario = ?";

        $conexao->execute($sql, $tipos, $params);
    }

    public function autenticar() : bool {
        $conexao = new MySQL();

        $tipos = "s";
        $params = [$this->email];
        $sql = "SELECT u.ID_Usuario, CONCAT(u.Nome, ' ', u.Sobrenome) AS Nome, u.Senha, u.Email, u.Tipo_Usuario FROM Usuario u WHERE u.Email = ?";

        $resultado = $conexao->search($sql, $tipos, $params);

        if(empty($resultado)) return false;

        $usuario = $resultado[0];

        if(!password_verify($this->senha, $usuario["senha"])) return false;

        session_start();
        $_SESSION["idUsuario"] = $usuario["idUsuario"];
        $_SESSION["nomeUsuario"] = $usuario["Nome"];
        $_SESSION["tipoUsuario"] = $usuario["Tipo_Usuario"];

        return true;
    }

    public static function findUsuario(int $idUsuario) : Usuario {
        $conexao = new MySql();

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];
        $sql = "SELECT u.ID_Usuario, CONCAT(u.Nome, ' ', u.Sobrenome) AS Nome, u.Email, u.Senha, u.Tipo_Usuario, u.Status_Cadastro, f.Link_Foto FROM Usuario u 
        JOIN Foto f ON f.ID_Foto = u.ID_Foto WHERE u.ID_Usuario = ?";

        $resultados = $conexao->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $usuario = new Usuario($resultado["Email"], $resultado["Senha"]);
        $usuario->setDados($resultado["ID_Usuario"], $resultado["Nome"], $resultado["Tipo_Usuario"], $resultado["Link_Foto"], $resultado["Status_Cadastro"]);

        return $usuario;
    }

    public function setDados(int $idUsuario, string $nome, string $tipoUsuario, string $linkFoto, string $statusCadastro) : void {
        $this->idUsuario = $idUsuario;
        $this->nome = $nome;
        $this->tipoUsuario = $tipoUsuario;
        $this->linkFoto = $linkFoto;
        $this->statusCadastro = $statusCadastro;
    }

    public function taAtivo() : bool {
        return $this->statusCadastro == "ativo";
    }

    public function desativarCadastro() {
        $conexao = new MySql();

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];
        $sql = "UPDATE Usuario SET Status_Cadastro = 'inativo' WHERE ID_Usuario = ?";

        $conexao->execute($sql, $tipos, $params);
    }

    // Getters e Setters

    // ID Usuario
    public function getIdUsuario() : int {
        return $this->idUsuario;
    }

    public function setIdUsuario(int $idUsuario) : void {
        $this->idUsuario = $idUsuario;
    }

    // Email
    public function getEmail() : string {
        return $this->email;
    }

    public function setEmail(string $email) : void {
        $this->email = $email;
    }

    // Nome
    public function getNome() : string {
        return $this->nome;
    }

    public function setNome(string $nome) : void {
        $this->nome = $nome;
    }

    // Link Foto
    public function getLinkFoto() : string {
        return $this->linkFoto;
    }

    public function setLinkFoto(string $linkFoto) : void {
        $this->linkFoto = $linkFoto;
    }

    // Tipo Usuario
    public function getTipoUsuario() : string {
        return $this->tipoUsuario;
    }

    public function setTipoUsuario(string $tipoUsuario) : void {
        $this->tipoUsuario = $tipoUsuario;
    }

    // Status Cadastro
    public function getStatusCadastro() : string {
        return $this->statusCadastro;
    }

    public function setStatusCadastro(string $statusCadastro) : void {
        $this->statusCadastro = $statusCadastro;
    }

}

?>