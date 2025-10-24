<?php

namespace App\Classes;

use App\BD\MySQL;

class SolicitacaoOrientacao {

    private int $idSolicitacaoOrientacao;

    private string $areaAtuacao;
    private string $turno;
    private string $modalidade;
    private int $cargaHorariaSemanal;
    private int $idAluno;

    // Atributos Empresa
    private string $nomeEmpresa;
    private string $emailEmpresa;
    private string $cidadeEmpresa;

    // Atributos Datas
    private string $dataInicio;
    private string $dataTermino;

    public function __construct(
        string $areaAtuacao,
        string $turno,
        string $modalidade,
        int $cargaHorariaSemanal,
        int $idAluno,
        string $nomeEmpresa,
        string $emailEmpresa,
        string $cidadeEmpresa,
        string $dataInicio,
        string $dataTermino,
    ) {
        $this->areaAtuacao = $areaAtuacao;
        $this->turno = $turno;
        $this->modalidade = $modalidade;
        $this->cargaHorariaSemanal = $cargaHorariaSemanal;
        $this->idAluno = $idAluno;
                                                                                                   
        $this->nomeEmpresa = $nomeEmpresa;
        $this->emailEmpresa = $emailEmpresa;
        $this->cidadeEmpresa = $cidadeEmpresa;

        $this->dataInicio = $dataInicio;
        $this->dataTermino = $dataTermino;
    }

    // CRUD

    // Salvar
    public function salvarSolicitacaoOrientacao() : void {
        $connection = new MySQL();

        session_start();

        $tipos = "ssssissssssi";

        $params = [$this->nomeEmpresa, $this->emailEmpresa, $this->cidadeEmpresa, $this->modalidade, $this->cargaHorariaSemanal,
        $this->turno, $this->areaAtuacao, $this->dataInicio, $this->dataTermino, $_SESSION["idUsuario"]];

        $sql = "INSERT INTO Solicitacao_Orientacao (Nome_Empresa, Email_Empresa, Cidade_Empresa, Modalidade, Carga_Horaria_Semanal, Turno, Area_Atuacao, 
        Data_Inicio, Data_Termino, ID_Aluno) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $connection->execute($sql, $tipos, $params);
    }

    // 'Excluir'
    public static function desativarSolicitacaoOrientacao($idSolicitacaoOrientacao) {
        $connection = new MySQL();

        $tipos = "i";
        $params = [$idSolicitacaoOrientacao];
        $sql = "UPDATE Solicitacao_Orientacao SET Status_Solicitacao_Orientacao = 'inativo' WHERE ID_Solicitacao_Orientacao = ?";

        $connection->execute($sql, $tipos, $params);
    }

    // Find Solicitação de Orientação
    public static function findSolicitacaoOrientacao($idSolicitacaoOrientacao) : SolicitacaoOrientacao {
        $connection = new MySQL();

        session_start();
        
        $tipos = "ii";
        $params = [$idSolicitacaoOrientacao, $_SESSION["idUsuario"]];

        // SQL para o aluno
        $sql = "SELECT * FROM solicitacao_orientacao so WHERE so.ID_Solicitacao_Orientacao = ? AND so.ID_Aluno = ? AND so.Status_Solicitacao_Orientacao = 'ativo'";

        // SQL para o professor
        if($_SESSION["tipoUsuario"] == "professor") {
            $sql = "SELECT * FROM solicitacao_orientacao so 
            JOIN professor_solicitacao_orientacao pso ON pso.ID_Solicitacao_Orientacao = so.ID_Solicitacao_Orientacao 
            WHERE so.ID_Solicitacao_Orientacao = ? AND pso.ID_Professor = ? 
            AND so.Status_Solicitacao_Orientacao == 'ativo' AND pso.Status == 'aguardando resposta'";
        }

        $resultados = $connection->search($sql, $tipos, $params);

        $resultado = $resultados[0];

        $so = new SolicitacaoOrientacao(
            $resultado["Area_Atuacao"],
            $resultado["Turno"],
            $resultado["Modalidade"],
            $resultado["Carga_Horaria_Semanal"],
            $resultado["ID_Aluno"],
            $resultado["Nome_Empresa"],
            $resultado["Email_Empresa"],
            $resultado["Cidade_Empresa"],
            $resultado["Data_Inicio"],
            $resultado["Data_Termino"],
            $resultado["Data_Envio"]
        );

        $so->setIdSolicitacaoOrientacao($resultado["ID_Solicitacao_Orientacao"]);

        return $so;
    }

    // Find All Solicitação de Orientação
    public static function findAllSolicitacaoOrientacao() : array {
        $connection = new MySQL();

        $sos = [];

        session_start();

        $tipos = "i";
        $params = [$_SESSION["idUsuario"]];

        // SQL para o aluno
        $sql = "SELECT * FROM solicitacao_orientacao so WHERE so.ID_Aluno = ?";

        // SQL para o professor
        if($_SESSION["tipoUsuario"] == "professor") {
            $sql = "SELECT * FROM solicitacao_orientacao so 
            JOIN professor_solicitacao_orientacao pso ON pso.ID_Solicitacao_Orientacao = so.ID_Solicitacao_Orientacao 
            WHERE pso.ID_Professor = ?";
        }

        $resultados = $connection->search($sql, $tipos, $params);

        foreach($resultados as $resultado) {
            $so = new SolicitacaoOrientacao(
                $resultado["Area_Atuacao"],
                $resultado["Turno"],
                $resultado["Modalidade"],
                $resultado["Carga_Horaria_Semanal"],
                $resultado["ID_Aluno"],
                $resultado["Nome_Empresa"],
                $resultado["Email_Empresa"],
                $resultado["Cidade_Empresa"],
                $resultado["Data_Inicio"],
                $resultado["Data_Termino"],
                $resultado["Data_Envio"]
            );

            $so->setIdSolicitacaoOrientacao($resultado["ID_Solicitacao_Orientacao"]);

            $sos[] = $so;
        }

        return $sos;
    }

    // Getters e Setters

    // ID da Solicitação de Orientação
    public function getIdSolicitacaoOrientacao() : int {
        return $this->idSolicitacaoOrientacao;
    }

    public function setIdSolicitacaoOrientacao(int $idSolicitacaoOrientacao) : void {
        $this->idSolicitacaoOrientacao = $idSolicitacaoOrientacao;
    }

    // ID do Aluno
    public function getIdAluno() : int {
        return $this->idAluno;
    }

    public function setIdAluno(int $idAluno) : void {
        $this->idAluno = $idAluno;
    }

    // Aluno
    public function getAluno() : Aluno {
        return Aluno::findAluno($this->idAluno);
    }

    // Área de Atuação
    public function getAreaAtuacao() : string {
        return $this->areaAtuacao;
    }

    public function setAreaAtuacao(string $areaAtuacao) : void {
        $this->areaAtuacao = $areaAtuacao;
    }

    // Turno
    public function getTurno() : string {
        return $this->turno;
    }

    public function setTurno(string $turno) : void {
        $this->turno = $turno;
    }

    // Modalidade
    public function getModalidade() : string {
        return $this->modalidade;
    }

    public function setModalidade(string $modalidade) : void {
        $this->modalidade = $modalidade;
    }

    // Carga Horária Semanal
    public function getCargaHorariaSemanal() : int {
        return $this->cargaHorariaSemanal;
    }

    public function setCargaHorariaSemanal(int $cargaHorariaSemanal) : void {
        $this->cargaHorariaSemanal = $cargaHorariaSemanal;
    }

    // Nome da Empresa
    public function getNomeEmpresa() : string {
        return $this->nomeEmpresa;
    }

    public function setNomeEmpresa(string $nomeEmpresa) : void {
        $this->nomeEmpresa = $nomeEmpresa;
    }

    // Email da Empresa
    public function getEmailEmpresa() : string {
        return $this->emailEmpresa;
    }

    public function setEmailEmpresa(string $emailEmpresa) : void {
        $this->emailEmpresa = $emailEmpresa;
    }

    // Cidade da Empresa
    public function getCidadeEmpresa() : string {
        return $this->cidadeEmpresa;
    }

    public function setCidadeEmpresa(string $cidadeEmpresa) : void {
        $this->cidadeEmpresa = $cidadeEmpresa;
    }

    // Data de Início
    public function getDataInicio() : string {
        return $this->dataInicio;
    }

    public function setDataInicio(string $dataInicio) : void {
        $this->dataInicio = $dataInicio;
    }

    // Data de Término
    public function getDataTermino() : string {
        return $this->dataTermino;
    }

    public function setDataTermino(string $dataTermino) : void {
        $this->dataTermino = $dataTermino;
    }

    // Data Enviado
    public function getDataEnviado() : string {
        return $this->dataEnviado;
    }

    public function setDataEnviado(string $dataEnviado) : void {
        $this->dataEnviado = $dataEnviado;
    }
}

?>