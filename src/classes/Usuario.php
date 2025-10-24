<?php

namespace App\Classes;

use App\BD\MySQL;

class Usuario {

    protected int $idUsuario;
    protected string $nome; // Junção de Nome + Sobrenome
    protected string $senha;
    protected string $email;
    protected string $linkFoto;
    protected string $tipoUsuario;

    protected string $statusCadastro = "Ativo";
    protected array $preferencias = []; // index -> descrição , Ex.: 1 -> Redes
    protected array $naoPreferencias = []; 

    public function __construct(string $email, string $senha) {
        $this->email = $email;
        $this->senha = $senha;
    }

    // CRUD

    // Salvar
    public function salvarUsuario(
        string $nome, 
        string $sobrenome, 
        string $tipoUsuario, 
        array $preferencias, 
        array $naoPreferencias
    ) : int {
        $conexao = new MySQL();

        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);

        $tipos = "sssss";
        $params = [$this->email, $this->senha, $nome, $sobrenome, $tipoUsuario];
        $sql = "INSERT INTO usuario (Nome, Sobrenome, Email, Senha, Tipo_Usuario) VALUES (?, ?, ?, ?, ?)";

        $idUsuario = $conexao->execute($sql, $tipos, $params);

        foreach($preferencias as $index => $preferencia) {
            $tipos = "iis";
            $params = [$idUsuario, $index, "sim"];
            $sql = "INSERT INTO usuario_preferencia (ID_Usuario, ID_Preferencia, Prefere) VALUES (?, ?, ?)";

            $conexao->execute($sql, $tipos, $params);
        }

        foreach($naoPreferencias as $index => $preferencia) {
            $tipos = "iis";
            $params = [$idUsuario, $index, "não"];
            $sql = "INSERT INTO usuario_preferencia (ID_Usuario, ID_Preferencia, Prefere) VALUES (?, ?, ?)";

            $conexao->execute($sql, $tipos, $params);
        }

        return $idUsuario;
    }

    // Atualizar
    public function atualizarUsuario(
        string $nome, 
        string $sobrenome, 
        string $email, 
        array $preferencias, 
        array $naoPreferencias
    ) : void {
        $conexao = new MySQL();

        session_start();

        $tipos = "sssi";
        $params = [$email, $nome, $sobrenome, $_SESSION["idUsuario"]];
        $sql = "UPDATE usuario SET Email = ?, Nome = ?, Sobrenome = ? WHERE ID_Usuario = ?";

        $conexao->execute($sql, $tipos, $params);

        foreach($preferencias as $index => $preferencia) {
            $tipos = "ii";
            $params = [$index, $_SESSION["idUsuario"]];
            $sql = "UPDATE usuario_preferencia SET Prefere = 'sim' WHERE ID_Preferencia = ? AND ID_Usuario = ?";

            $conexao->execute($sql, $tipos, $params);
        }

        foreach($naoPreferencias as $index => $preferencia) {
            $tipos = "ii";
            $params = [$index, $_SESSION["idUsuario"]];
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

    // Find Usuario
    public static function findUsuario(int $idUsuario) : Usuario {
        $conexao = new MySQL();

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];
        $sql = "SELECT *, CONCAT(u.Nome, ' ', u.Sobrenome) AS Nome, f.Link_Foto FROM Usuario u 
        JOIN Foto f ON f.ID_Foto = u.ID_Foto WHERE u.ID_Usuario = ? AND u.Status_Cadastro = 'ativo'";

        $resultados = $conexao->search($sql, $tipos, $params);

        if(empty($resultados)) return null;

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

    // Find All Usuarios
    public static function findAllUsuarios($tipoUsuario = "") : array {
        $connection = new MySQL();

        $usuarios = [];

        $tipos = "s";
        $params = [$tipoUsuario];
        $sql = "SELECT *, CONCAT(u.Nome, ' ', u.Sobrenome) AS Nome, f.Link_Foto FROM Usuario u 
        JOIN Foto f ON f.ID_Foto = u.ID_Foto WHERE u.Tipo_Usuario LIKE ?";

        $resultados = $conexao->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $usuario = new Usuario($resultado["Email"], $resultado["Senha"]);

            $usuario->setIdUsuario($resultado["ID_Usuario"]);
            $usuario->setNome($resultado["Nome"]);
            $usuario->setTipoUsuario($resultado["Tipo_Usuario"]);
            $usuario->setLinkFoto($resultado["Link_Foto"]);
            $usuario->setStatusCadastro($resultado["Status_Cadastro"]);
            $usuario->setPreferenciasUsuario();

            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    // Setta as preferencias do usuario
    public function setPreferencias() : void {
        $conexao = new MySQL();

        $tipos = "i";
        $params = [$this->idUsuario];

        // Pegar preferencias que gosta
        $sql = "SELECT * FROM preferencia p 
        JOIN usuario_preferencia up ON up.ID_Preferencia = p.ID_Preferencia WHERE up.Prefere = 'sim' AND up.ID_Usuario = ?";

        $resultados = $conexao->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $this->preferencias[$resultado["ID_Preferencia"]] = $resultado["Descricao"];
        }

        // Pegar preferencias que não gosta
        $sql = "SELECT * FROM preferencia p 
        JOIN usuario_preferencia up ON up.ID_Preferencia = p.ID_Preferencia WHERE up.Prefere = 'não' AND up.ID_Usuario = ?";

        $resultados = $conexao->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $this->naoPreferencias[$resultado["ID_Preferencia"]] = $resultado["Descricao"];
        }
    }

    // Autenticar Usuario
    public function autenticar() : bool {
        $conexao = new MySQL();

        $tipos = "s";
        $params = [$this->email];
        $sql = "SELECT *, CONCAT(u.Nome, ' ', u.Sobrenome) AS Nome FROM Usuario u WHERE BINARY u.Email = ?";

        $resultado = $conexao->search($sql, $tipos, $params);

        // Caso usuario não cadastrado
        if(empty($resultado)) return false;

        $usuario = $resultado[0];

        $this->statusCadastro = $usuario["Status_Cadastro"];

        // Caso usuario inativo
        if($this->statusCadastro == "inativo") return false;

        // Caso senha do login e do banco forem diferentes
        if(!password_verify($this->senha, $usuario["Senha"])) return false;

        // Settando dados da session
        session_start();

        $_SESSION["idUsuario"] = $usuario["ID_Usuario"];
        $_SESSION["nome"] = $usuario["Nome"];
        $_SESSION["tipoUsuario"] = $usuario["Tipo_Usuario"];

        return true;
    }

    // Checa se o usuario está ativo
    public function taAtivo() {
        return $this->statusCadastro == "ativo";
    }

    // Checa se o usuario está cadastrado
    public function usuarioExiste() {
        $connection = new MySQL();

        $tipos = "s";
        $params = [$this->email];
        $sql = "SELECT 1 FROM usuario u WHERE BINARY u.Email = ?";

        $resultado = $connection->search($sql, $tipos, $params);

        return !empty($resultado);
    }

    // Cria nova senha aleatoria para o usuario
    public function criarNovaSenha() : void {
        $connection = new MySql();

        $novaSenha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

        $senhaCriptografada = password_hash($novaSenha, PASSWORD_BCRYPT);

        $tipos = "ss";
        $params = [$senhaCriptografada, $this->email];
        $sql = "UPDATE usuario SET senha = ? WHERE BINARY email = ?";

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

    public function setSenha($senha) : void {
        $this->senha = $senha;
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

        foreach ($this->preferencias as $index => $preferencia) {
            $resultado = $resultado . ", " .$preferencia;
        }

        return $resultado;
    }

    // Não Prefere
    public function getNaoPreferencias() : string {
        $resultado = "";

        foreach ($this->naoPreferencias as $index => $noPreferencia) {
            $resultado = $resultado . ", " . $noPreferencia;
        }

        return $resultado;
    }
}

?>