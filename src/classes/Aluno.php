<?php

namespace App\Classes;

use App\BD\MySQL;

class Aluno extends Usuario {

    private string $cidadeEstagio;
    private string $turnoDisponivel;
    private string $statusEstagio;
    private string $modalidade;
    private int $idTurma;

    public function __construct(string $email, string $senha) {
        parent::__construct($email, $senha);
    }

    // CRUD

    // Salvar
    public function salvarAluno(
        string $nome, 
        string $sobrenome,
        int $idFoto,
        array $preferencias, 
        array $naoPreferencias, 
        string $cidadeEstagio,
        string $turnoDisponivel,
        string $statusEstagio,
        string $modalidade,
        int $idTurma
    ) : void {
        $idUsuario = parent::salvarUsuario($nome, $sobrenome, "Aluno", $idFoto, $preferencias, $naoPreferencias);

        $connection = new MySQL();

        $tipos = "issssi";
        $params = [$idUsuario, $cidadeEstagio, $turnoDisponivel, $statusEstagio, $modalidade, $idTurma];
        $sql = "INSERT INTO aluno (ID_Aluno, Cidade_Estagio, Turno_Disponivel, Status_Estagio, Modalidade, ID_Turma) VALUES (?, ?, ?, ?, ?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    // Atualizar
    public function atualizarAluno(
        string $nome, 
        string $sobrenome, 
        string $email, 
        array $preferencias, 
        array $naoPreferencias, 
        string $cidadeEstagio,
        string $turnoDisponivel,
        string $statusEstagio,
        string $modalidade,
        int $idTurma
    ) : void {
        parent::atualizarUsuario($nome, $sobrenome, $email, $preferencias, $naoPreferencias);

        $connection = new MySQL();

        session_start();

        $tipos = "ssssii";
        $params = [$cidadeEstagio, $turnoDisponivel, $statusEstagio, $modalidade, $idTurma, $_SESSION["idUsuario"]];
        $sql = "UPDATE aluno SET Cidade_Estagio = ?, Turno_Disponivel = ?, Status_Estagio = ?, Modalidade = ?, ID_Turma = ? WHERE ID_Aluno = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Aluno
    public static function findAluno($idAluno) : Aluno {
        $usuario = parent::findUsuario($idAluno);

        if(empty($usuario)) return null;

        $connection = new MySQL();

        $tipos = "i";
        $params = [$idAluno];
        $sql = "SELECT *, t.Nome AS Nome_Turma FROM aluno a JOIN turma t ON t.ID_Turma = a.ID_Turma WHERE a.ID_Aluno = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];
        
        $aluno = new Aluno($usuario->getEmail(), $usuario->getSenha());
        
        $aluno->setIdUsuario( $usuario->getIdUsuario() );
        $aluno->setNome( $usuario->getNome() );
        $aluno->setTipoUsuario( $usuario->getTipoUsuario() );
        $aluno->setLinkFoto( $usuario->getLinkFoto() );
        $aluno->setStatusCadastro( $usuario->getStatusCadastro() );
        $aluno->setPreferencias();

        $aluno->setCidadeEstagio($resultado["Cidade_Estagio"]);
        $aluno->setTurnoDisponivel($resultado["Turno_Disponivel"]);
        $aluno->setStatusEstagio($resultado["Status_Estagio"]);
        $aluno->setModalidade($resultado["Modalidade"]);
        $aluno->setIdTurma($resultado["ID_Turma"]);

        return $aluno;
    }

    // Find All Alunos
    public static function findAllAlunos() : array {
        $connection = new MySQL();

        $alunos = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT a.*, u.*, f.*, t.Nome AS Nome_Turma FROM aluno a 
        JOIN usuario u ON u.ID_Usuario = a.ID_Aluno 
        JOIN foto f ON f.ID_Foto = u.ID_Foto
        JOIN turma t ON t.ID_Turma = a.ID_Turma
        WHERE u.Status_Cadastro = 'ativo'";

        $resultados = $connection->search($sql, $tipos, $params);

        if(empty($resultados)) return null;

        foreach($resultados as $resultado) {
            $aluno = new Aluno($resultado["Email"], $resultado["Senha"]);
            
            $aluno->setIdUsuario($resultado["ID_Usuario"]);
            $aluno->setNome($resultado["Nome"]);
            $aluno->setTipoUsuario($resultado["Tipo_Usuario"]);
            $aluno->setLinkFoto($resultado["Link_Foto"]);
            $aluno->setStatusCadastro($resultado["Status_Cadastro"]);
            $aluno->setPreferencias();

            $aluno->setCidadeEstagio($resultado["Cidade_Estagio"]);
            $aluno->setTurnoDisponivel($resultado["Turno_Disponivel"]);
            $aluno->setStatusEstagio($resultado["Status_Estagio"]);
            $aluno->setModalidade($resultado["Modalidade"]);
            $aluno->setIdTurma($resultado["ID_Turma"]);

            $alunos[] = $aluno;
        }

        return $alunos;
    }

    // Getters e Setters

    // ID Turma
    public function getIdTurma() : int {
        return $this->idTurma;
    }

    public function setIdTurma($idTurma) : void {
        $this->idTurma = $idTurma;
    }

    // Turma
    public function geTurma() : Turma {
        return Turma::findTurma($this->idTurma);
    }

    // Cidade Estagio
    public function getCidadeEstagio() : string {
        return $this->cidadeEstagio;
    }

    public function setCidadeEstagio($cidadeEstagio) : void {
        $this->cidadeEstagio = $cidadeEstagio;
    }

    // Turno Disponivel
    public function getTurnoDisponivel() : string {
        return $this->turnoDisponivel;
    }

    public function setTurnoDisponivel($turnoDisponivel) : void {
        $this->turnoDisponivel = $turnoDisponivel;
    }

    // Status Estagio
    public function getStatusEstagio() : string {
        return $this->statusEstagio;
    }

    public function setStatusEstagio($statusEstagio) : void {
        $this->statusEstagio = $statusEstagio;
    }

    // Modalidade
    public function getModalidade() : string {
        return $this->modalidade;
    }

    public function setModalidade($modalidade) : void {
        $this->modalidade = $modalidade;
    }

}

?>