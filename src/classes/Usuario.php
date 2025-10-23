<?php

namespace App\Classes;

use App\BD\MySQL;

class Usuario {

    protected int $idUsuario;
    protected string $nome;
    protected string $senha;
    protected string $email;
    protected string $linkFoto;
    protected string $tipoUsuario;
    protected string $statusCadastro = "ativo";
    protected array $preferencias = [];
    protected array $naoPreferencias = [];

    public function __construct(string $email, string $senha) {
        $this->email = $email;
        $this->senha = $senha;
    }

    // CRUD

    // Salvar
    public function salvarUsuario(string $nome, string $sobrenome, string $tipoUsuario, array $preferencias, array $noPreferencias) {
        $conexao = new MySQL();

        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);

        $tipos = "sssss";
        $params = [$nome, $sobrenome, $this->email, $this->senha, $tipoUsuario];
        $sql = "INSERT INTO Usuario(Nome, Sobrenome, Email, Senha, Tipo_Usuario) VALUES (?, ?, ?, ?, ?)";

        $idUsuario = $conexao->execute($sql, $tipos, $params);

        foreach($preferencias as $preferencia) {
            $tipos = "iis";
            $params = [$idUsuario, $preferencia, "sim"];
            $sql = "INSERT INTO usuario_preferencia(ID_Usuario, ID_Preferencia, Prefere) VALUES (?, ?, ?)";

            $conexao->execute($sql, $tipos, $params);
        }

        foreach($noPreferencias as $preferencia) {
            $tipos = "iis";
            $params = [$idUsuario, $preferencia, "não"];
            $sql = "INSERT INTO usuario_preferencia(ID_Usuario, ID_Preferencia, Prefere) VALUES (?, ?, ?)";

            $conexao->execute($sql, $tipos, $params);
        }

        return $idUsuario;
    }

    // Atualizar
    public function atualizarUsuario(string $nome, string $sobrenome, string $email, array $preferencias, array $noPreferencias) : void {
        $conexao = new MySQL();

        session_start();

        $tipos = "sssi";
        $params = [$nome, $sobrenome, $email, $_SESSION["idUsuario"]];
        $sql = "UPDATE Usuario SET Nome = ?,  Sobrenome = ?, Email = ? WHERE ID_Usuario = ?";

        $conexao->execute($sql, $tipos, $params);

        foreach($preferencias as $preferencia) {
            $tipos = "ii";
            $params = [$preferencia, $_SESSION["idUsuario"]];
            $sql = "UPDATE usuario_preferencia SET Prefere = 'sim' WHERE ID_Preferencia = ? AND ID_Usuario = ?";

            $conexao->execute($sql, $tipos, $params);
        }

        foreach($noPreferencias as $preferencia) {
            $tipos = "ii";
            $params = [$preferencia, $_SESSION["idUsuario"]];
            $sql = "UPDATE usuario_preferencia SET Prefere = 'não' WHERE ID_Preferencia = ? AND ID_Usuario = ?";

            $conexao->execute($sql, $tipos, $params);
        }
    }

    // 'Excluir'
    public function desativarCadastro() : void {
        $conexao = new MySQL();

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];
        $sql = "UPDATE Usuario SET Status_Cadastro = 'inativo' WHERE ID_Usuario = ?";

        $conexao->execute($sql, $tipos, $params);
    }

    public function autenticar() : bool {
        $conexao = new MySQL();

        $tipos = "s";
        $params = [$this->email];
        $sql = "SELECT u.ID_Usuario, CONCAT(u.Nome, ' ', u.Sobrenome) AS Nome, u.Senha, u.Email, u.Tipo_Usuario, u.Status_Cadastro
        FROM Usuario u WHERE u.Email = ?";

        $resultado = $conexao->search($sql, $tipos, $params);

        if(empty($resultado)) return false;

        $usuario = $resultado[0];

        if($usuario["Status_Cadastro"] == "inativo") {
            $this->statusCadastro = "inativo";

            return false;
        }

        if(!password_verify($this->senha, $usuario["Senha"])) return false;

        session_start();
        $_SESSION["idUsuario"] = $usuario["idUsuario"];
        $_SESSION["nomeUsuario"] = $usuario["Nome"];
        $_SESSION["tipoUsuario"] = $usuario["Tipo_Usuario"];

        return true;
    }

    public function taAtivo() {
        return $this->statusCadastro == "ativo";
    }

    public static function findUsuario(int $idUsuario) : Usuario {
        $conexao = new MySQL();

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];
        $sql = "SELECT u.ID_Usuario, CONCAT(u.Nome, ' ', u.Sobrenome) AS Nome, u.Email, u.Senha, u.Tipo_Usuario, u.Status_Cadastro, f.Link_Foto FROM Usuario u 
        JOIN Foto f ON f.ID_Foto = u.ID_Foto WHERE u.ID_Usuario = ?";

        $resultados = $conexao->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        // Setando os dados
        $usuario = new Usuario($resultado["Email"], $resultado["Senha"]);

        $usuario->setIdUsuario($resultado["ID_Usuario"]);
        $usuario->setNome($resultado["Nome"]);
        $usuario->setTipoUsuario($resultado["Tipo_Usuario"]);
        $usuario->setLinkFoto($resultado["Link_Foto"]);
        $usuario->setStatusCadastro($resultado["Status_Cadastro"]);
        $usuario->setPreferencias();

        return $usuario;
    }

    public function setPreferencias() : void {
        $conexao = new MySQL();

        $tipos = "i";
        $params = [$this->idUsuario];

        $sql = "SELECT p.Descricao as Descricao FROM Usuario u 
        JOIN Usuario_Preferencia up ON up.ID_Usuario = u.ID_Usuario 
        JOIN Preferencia p ON p.ID_Preferencia = up.ID_Preferencia WHERE u.ID_Usuario = ? AND up.Prefere = 'sim'";

        $resultados = $conexao->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $this->preferencias[] = $resultado["Descricao"];
        }

        $sql = "SELECT p.Descricao as Descricao FROM Usuario u 
        JOIN Usuario_Preferencia up ON up.ID_Usuario = u.ID_Usuario 
        JOIN Preferencia p ON p.ID_Preferencia = up.ID_Preferencia WHERE u.ID_Usuario = ? AND up.Prefere = 'não'";

        $resultados = $conexao->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $this->noPreferencias[] = $resultado["Descricao"];
        }
    }

    public function usuarioExiste() {
        $connection = new MySQL();

        $tipos = "s";
        $params = [$this->email];
        $sql = "SELECT 1 FROM usuario u WHERE BINARY u.Email = ?";

        $resultado = $connection->search($sql, $tipos, $params);

        return !empty($resultado);
    }

    public function criarNovaSenha() : void {
        $connection = new MySql();

        $novaSenha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        $senhaCriptografada = password_hash($novaSenha, PASSWORD_BCRYPT);

        $tipos = "ss";
        $params = [$senhaCriptografada, $this->email];
        $sql = "UPDATE usuario SET senha = ? WHERE email = ?";

        $connection->execute($sql, $tipos, $params);

        $this->senha = $novaSenha;
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

    // Senha
    public function getSenha() : string {
        return $this->senha;
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

    // Prefere
    public function getPreferencias() : string {
        $resultado = "";

        foreach ($this->preferencias as $preferencia) {
            $resultado = $resultado . ", " .$preferencia;
        }

        return $resultado;
    }

    // Não Prefere
    public function getNoPreferencias() : string {
        $resultado = "";

        foreach ($this->noPreferencias as $noPreferencia) {
            $resultado = $resultado . ", " . $noPreferencia;
        }

        return $resultado;
    }

    public function setNoPreferencias(array $noPreferencias) : void {
        $this->noPreferencias = $noPreferencias;
    }

}

?>