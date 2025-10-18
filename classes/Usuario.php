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
    private array $preferencias = [];
    private array $naoPreferencias = [];

    public function __construct(string $email, string $senha) {
        $this->email = $email;
        $this->senha = $senha;
    }

    // CRUD

    // Salvar
    public function salvarUsuario(string $nome, string $sobrenome, string $tipoUsuario, array $preferencias) {
        $conexao = new MySql();

        $this->senha = password_hash($this->senha, PASSWORD_BCRYPT);

        $tipos = "sssss";
        $params = [$nome, $sobrenome, $this->email, $this->senha, $tipoUsuario];
        $sql = "INSERT INTO Usuario(Nome, Sobrenome, Email, Senha, Tipo_Usuario) VALUES (?, ?, ?, ?, ?)";

        $conexao->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarUsuario(string $nome, string $sobrenome, string $email) : void {
        $conexao = new MySql();

        session_start();

        $tipos = "sssi";
        $params = [$nome, $sobrenome, $email, $_SESSION["idUsuario"]];
        $sql = "UPDATE Usuario SET Nome = ?,  Sobrenome = ?, Email = ? WHERE ID_Usuario = ?";

        $conexao->execute($sql, $tipos, $params);
    }

    // 'Excluir'
    public function desativarCadastro() : void {
        $conexao = new MySql();

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];
        $sql = "UPDATE Usuario SET Status_Cadastro = 'inativo' WHERE ID_Usuario = ?";

        $conexao->execute($sql, $tipos, $params);
    }

    public function taAtivo() : bool {
        return $this->statusCadastro == "ativo";
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

        // Setando os dados
        $usuario = new Usuario($resultado["Email"], $resultado["Senha"]);

        $usuario->setIdUsuario($resultado["ID_Usuario"]);
        $usuario->setNome($resultado["Nome"]);
        $usuario->setTipoUsuario($resultado["Tipo_Usuario"]);
        $usuario->setLinkFoto($resultado["Link_Foto"]);
        $usuario->setStatusCadastro($resultado["Status_Cadastro"]);

        return $usuario;
    }

    public function setPreferencias() : void {
        $conexao = new MySql();

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];

        $sql = "SELECT p.Descricao FROM Usuario u 
        JOIN Usuario_Preferencia up ON up.ID_Usuario = u.ID_Usuario 
        JOIN Preferencia p ON p.Preferencia = up.Preferencia WHERE u.ID_Usuario = ? AND up.Prefere = 'sim'";

        $resultado = $conexao->search($sql, $tipos, $params);

        $sql = "SELECT p.Descricao FROM Usuario u 
        JOIN Usuario_Preferencia up ON up.ID_Usuario = u.ID_Usuario 
        JOIN Preferencia p ON p.Preferencia = up.Preferencia WHERE u.ID_Usuario = ? AND up.Prefere = 'não'";

        $resultado = $conexao->search($sql, $tipos, $params);
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

    // Prefere
    public function getPreferencias() : string {
        $resultado = "";

        foreach ($this->preferencias as $preferencia) {
            $resultado = $resultado . $preferencia;
        }

        return $resultado;
    }

    // Não Prefere
    public function getNoPreferencias() : string {
        $resultado = "";

        foreach ($this->noPreferencias as $noPreferencia) {
            $resultado = $resultado . $noPreferencia;
        }

        return $resultado;
    }

    public function setNoPreferencias(array $noPreferencias) : void {
        $this->noPreferencias = $noPreferencias;
    }

}

?>