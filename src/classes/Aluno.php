<?php

namespace App\Classes;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Classes\Usuario;

use App\BD\MySQL;

class Aluno extends Usuario {

    private string $cidadeEstagio;
    private string $turnoDisponivel;
    private string $statusEstagio;
    private string $modalidade;
    private int $idCurso;
    private int $anoIngresso;
    private array $cidadesEstagiar = [];

    public function __construct(string $email, string $senha) {
        parent::__construct($email, $senha);
    }

    // CRUD

    // Salvar
    public function salvarAluno(
        string $nome, 
        string $sobrenome,
        array $preferencias, 
        array $naoPreferencias, 
        int $anoIngresso,
        array $cidadesEstagiar,
        string $turnoDisponivel,
        string $statusEstagio,
        string $modalidade,
        int $idCurso
    ) : void {
        $idUsuario = parent::salvarUsuario($nome, $sobrenome, "aluno", 2, $preferencias, $naoPreferencias);

        $connection = new MySQL();

        $tipos = "issssii";
        $params = [$idUsuario, $cidadeEstagio, $turnoDisponivel, $statusEstagio, $modalidade, $anoIngresso, $idCurso];
        $sql = "INSERT INTO aluno2 (ID_Aluno, Cidade_Estagio, Turno_Disponivel, Status_Estagio, Modalidade, Ano_Ingresso, ID_Curso) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $connection->execute($sql, $tipos, $params);

        foreach($cidadesEstagiar as $index) {
            $tipos = "ii";
            $params = [$idUsuario, $index];
            $sql = "INSERT INTO usuario_cidade (ID_Usuario, ID_Cidade) VALUES (?, ?)";

            $conexao->execute($sql, $tipos, $params);
        }
    }

    // Atualizar
    public function atualizarAluno(
        string $nome, 
        string $sobrenome, 
        string $email, 
        array $preferencias, 
        array $naoPreferencias,
        int $anoIngresso,
        array $cidadesEstagiar,
        string $turnoDisponivel,
        string $statusEstagio,
        string $modalidade,
        int $idCurso
    ) : void {
        parent::atualizarUsuario($nome, $sobrenome, $email, $preferencias, $naoPreferencias);

        $connection = new MySQL();

        if(session_status() != 2) session_start();

        $tipos = "ssssiii";
        $params = [$cidadeEstagio, $turnoDisponivel, $statusEstagio, $modalidade, $anoIngresso, $idCurso, $_SESSION["idUsuario"]];
        $sql = "UPDATE aluno2 SET Cidade_Estagio = ?, Turno_Disponivel = ?, Status_Estagio = ?, Modalidade = ?, Ano_Ingresso = ?, ID_Curso = ? WHERE ID_Aluno = ?";

        $connection->execute($sql, $tipos, $params);

        $this->deletarCidadesEstagiar();
        $this->inserirCidadesEstagiar($cidadesEstagiar);
    }

    // Find Aluno
    public static function findAluno($idAluno) : Aluno {
        $usuario = parent::findUsuario($idAluno);

        if(empty($usuario)) return null;

        $connection = new MySQL();

        $tipos = "i";
        $params = [$idAluno];
        $sql = "SELECT a.*, c.Nome AS Nome_Curso FROM aluno2 a JOIN curso c ON c.ID_Curso = a.ID_Curso WHERE a.ID_Aluno = ?";

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];
        
        $aluno = new Aluno($usuario->getEmail(), $usuario->getSenha());
        
        $aluno->setIdUsuario( $usuario->getIdUsuario() );
        $aluno->setNome( $usuario->getNome() );
        $aluno->setSobrenome( $usuario->getSobrenome() );
        $aluno->setTipoUsuario( $usuario->getTipoUsuario() );
        $aluno->setIdFoto( $usuario->getIdFoto() );
        $aluno->setStatusCadastro( $usuario->getStatusCadastro() );
        $aluno->setPreferencias();

        $aluno->setTurnoDisponivel($resultado["Turno_Disponivel"]);
        $aluno->setStatusEstagio($resultado["Status_Estagio"]);
        $aluno->setModalidade($resultado["Modalidade"]);
        $aluno->setAnoIngresso($resultado["Ano_Ingresso"]);
        $aluno->setIdCurso($resultado["ID_Curso"]);
        $aluno->setCidadesEstagiar();

        return $aluno;
    }

    // Find All Alunos
    public static function findAllAlunos() : array {
        $connection = new MySQL();

        $alunos = [];

        $tipos = "";
        $params = [];
        $sql = "SELECT a.*, u.*, f.*, c.Nome AS Nome_Curso FROM aluno2 a 
        JOIN usuario2 u ON u.ID_Usuario = a.ID_Aluno 
        JOIN foto f ON f.ID_Foto = u.ID_Foto
        JOIN curso c ON c.ID_Curso = a.ID_Curso
        WHERE u.Status_Cadastro = 'ativo'";

        $resultados = $connection->search($sql, $tipos, $params);

        if(empty($resultados)) return [];

        foreach($resultados as $resultado) {
            $aluno = new Aluno($resultado["Email"], $resultado["Senha"]);
            
            $aluno->setIdUsuario($resultado["ID_Usuario"]);
            $aluno->setNome($resultado["Nome"]);
            $aluno->setSobrenome($resultado["Sobrenome"]);
            $aluno->setTipoUsuario($resultado["Tipo_Usuario"]);
            $aluno->setIdFoto($resultado["ID_Foto"]);
            $aluno->setStatusCadastro($resultado["Status_Cadastro"]);
            $aluno->setPreferencias();

            $aluno->setTurnoDisponivel($resultado["Turno_Disponivel"]);
            $aluno->setStatusEstagio($resultado["Status_Estagio"]);
            $aluno->setModalidade($resultado["Modalidade"]);
            $aluno->setAnoIngresso($resultado["Ano_Ingresso"]);
            $aluno->setIdCurso($resultado["ID_Curso"]);
            $aluno->setCidadesEstagiar();

            $alunos[] = $aluno;
        }

        return $alunos;
    }

    // Setta as cidades escolhidas pelo aluno
    public function setCidadesEstagiar() : void {
        $conexao = new MySQL();

        $tipos = "i";
        $params = [$this->idUsuario];
        $sql = "SELECT * FROM cidade c JOIN usuario_cidade uc ON uc.ID_Cidade = c.ID_Cidade WHERE uc.ID_Usuario = ? ORDER BY c.UF, c.Nome";

        $resultados = $conexao->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $this->cidadesEstagiar[$resultado["ID_Cidade"]] = ["nome" => $resultado["Nome"], "uf" => $resultado["UF"]];
        }
    }

    public function inserirCidadesEstagiar(array $cidadesEstagiar) : void {
        $connection = new MySQL();

        if(session_status() != 2) session_start();

        $tipos = "ii";

        foreach($cidadesEstagiar as $idCidade) {
            $params = [$_SESSION["idUsuario"], $idCidade];

            $sql = "INSERT INTO usuario_cidade (ID_Usuario, ID_Cidade) VALUES (?, ?)";

            $connection->execute($sql, $tipos, $params);
        }
    }

    public function deletarCidadesEstagiar() : void {
        $connection = new MySQL();

        if(session_status() != 2) session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];
        $sql = "DELETE FROM usuario_cidade WHERE ID_Usuario = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Getters e Setters

    // ID Curso
    public function getIdCurso() : int {
        return $this->idCurso;
    }

    public function setIdCurso($idCurso) : void {
        $this->idCurso = $idCurso;
    }

    // Curso
    public function getCurso() : Curso {
        return Curso::findCurso($this->idCurso);
    }

    // Ano Ingresso
    public function getAnoIngresso() : int {
        return $this->anoIngresso;
    }

    public function setAnoIngresso(int $anoIngresso) : void {
        $this->anoIngresso = $anoIngresso;
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

    // Cidades Estagiar
    public function getCidadesEstagiar() : array {
        return $this->cidadesEstagiar;
    }
}

?>