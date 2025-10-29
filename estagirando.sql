-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29/10/2025 às 04:34
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `estagirando`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno2`
--

CREATE TABLE `aluno2` (
  `ID_Aluno` int(11) NOT NULL,
  `Cidade_Estagio` varchar(40) DEFAULT NULL,
  `Turno_Disponivel` varchar(15) DEFAULT NULL,
  `Status_Estagio` varchar(30) DEFAULT NULL,
  `ID_Curso` int(11) DEFAULT NULL,
  `Modalidade` varchar(15) DEFAULT NULL,
  `Ano_Ingresso` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `curso`
--

CREATE TABLE `curso` (
  `ID_Curso` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `foto`
--

CREATE TABLE `foto` (
  `ID_Foto` int(11) NOT NULL,
  `Link_Foto` varchar(255) DEFAULT NULL,
  `Nome_Foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `preferencia`
--

CREATE TABLE `preferencia` (
  `ID_Preferencia` int(11) NOT NULL,
  `Descricao` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor2`
--

CREATE TABLE `professor2` (
  `ID_Professor` int(11) NOT NULL,
  `Status_Disponibilidade` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor_solicitacao_orientacao`
--

CREATE TABLE `professor_solicitacao_orientacao` (
  `ID_Professor` int(11) NOT NULL,
  `ID_Solicitacao_Orientacao` int(11) NOT NULL,
  `Status` varchar(30) DEFAULT NULL,
  `Resposta` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_orientacao`
--

CREATE TABLE `solicitacao_orientacao` (
  `ID_Solicitacao_Orientacao` int(11) NOT NULL,
  `Nome_Empresa` varchar(100) DEFAULT NULL,
  `Area_Atuacao` varchar(100) DEFAULT NULL,
  `Email_Empresa` varchar(100) DEFAULT NULL,
  `Cidade_Empresa` varchar(50) DEFAULT NULL,
  `Data_Inicio` date DEFAULT NULL,
  `Data_Termino` date DEFAULT NULL,
  `Modalidade` varchar(30) DEFAULT NULL,
  `Carga_Horaria_Semanal` int(11) DEFAULT NULL,
  `Turno` varchar(15) DEFAULT NULL,
  `Data_Envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `Status_Solicitacao_Orientacao` varchar(30) DEFAULT 'ativo',
  `ID_Aluno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario2`
--

CREATE TABLE `usuario2` (
  `ID_Usuario` int(11) NOT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  `Sobrenome` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Senha` varchar(255) DEFAULT NULL,
  `Tipo_Usuario` varchar(20) DEFAULT NULL,
  `Status_Cadastro` varchar(20) DEFAULT 'ativo',
  `ID_Foto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario_preferencia`
--

CREATE TABLE `usuario_preferencia` (
  `ID_Usuario` int(11) NOT NULL,
  `ID_Preferencia` int(11) NOT NULL,
  `Prefere` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `aluno2`
--
ALTER TABLE `aluno2`
  ADD PRIMARY KEY (`ID_Aluno`),
  ADD KEY `ID_Curso` (`ID_Curso`);

--
-- Índices de tabela `curso`
--
ALTER TABLE `curso`
  ADD PRIMARY KEY (`ID_Curso`);

--
-- Índices de tabela `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`ID_Foto`);

--
-- Índices de tabela `preferencia`
--
ALTER TABLE `preferencia`
  ADD PRIMARY KEY (`ID_Preferencia`);

--
-- Índices de tabela `professor2`
--
ALTER TABLE `professor2`
  ADD PRIMARY KEY (`ID_Professor`);

--
-- Índices de tabela `professor_solicitacao_orientacao`
--
ALTER TABLE `professor_solicitacao_orientacao`
  ADD PRIMARY KEY (`ID_Professor`,`ID_Solicitacao_Orientacao`),
  ADD KEY `ID_Solicitacao_Orientacao` (`ID_Solicitacao_Orientacao`);

--
-- Índices de tabela `solicitacao_orientacao`
--
ALTER TABLE `solicitacao_orientacao`
  ADD PRIMARY KEY (`ID_Solicitacao_Orientacao`),
  ADD KEY `ID_Aluno` (`ID_Aluno`);

--
-- Índices de tabela `usuario2`
--
ALTER TABLE `usuario2`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD KEY `ID_Foto` (`ID_Foto`);

--
-- Índices de tabela `usuario_preferencia`
--
ALTER TABLE `usuario_preferencia`
  ADD PRIMARY KEY (`ID_Usuario`,`ID_Preferencia`),
  ADD KEY `ID_Preferencia` (`ID_Preferencia`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `curso`
--
ALTER TABLE `curso`
  MODIFY `ID_Curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `foto`
--
ALTER TABLE `foto`
  MODIFY `ID_Foto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `preferencia`
--
ALTER TABLE `preferencia`
  MODIFY `ID_Preferencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `solicitacao_orientacao`
--
ALTER TABLE `solicitacao_orientacao`
  MODIFY `ID_Solicitacao_Orientacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario2`
--
ALTER TABLE `usuario2`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aluno2`
--
ALTER TABLE `aluno2`
  ADD CONSTRAINT `ID_Curso` FOREIGN KEY (`ID_Curso`) REFERENCES `curso` (`ID_Curso`) ON DELETE SET NULL,
  ADD CONSTRAINT `aluno2_ibfk_1` FOREIGN KEY (`ID_Aluno`) REFERENCES `usuario2` (`ID_Usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `aluno2_ibfk_2` FOREIGN KEY (`ID_Curso`) REFERENCES `curso` (`ID_Curso`) ON DELETE SET NULL;

--
-- Restrições para tabelas `professor2`
--
ALTER TABLE `professor2`
  ADD CONSTRAINT `professor2_ibfk_1` FOREIGN KEY (`ID_Professor`) REFERENCES `usuario2` (`ID_Usuario`) ON DELETE CASCADE;

--
-- Restrições para tabelas `professor_solicitacao_orientacao`
--
ALTER TABLE `professor_solicitacao_orientacao`
  ADD CONSTRAINT `professor_solicitacao_orientacao_ibfk_1` FOREIGN KEY (`ID_Professor`) REFERENCES `professor2` (`ID_Professor`) ON DELETE CASCADE,
  ADD CONSTRAINT `professor_solicitacao_orientacao_ibfk_2` FOREIGN KEY (`ID_Solicitacao_Orientacao`) REFERENCES `solicitacao_orientacao` (`ID_Solicitacao_Orientacao`) ON DELETE CASCADE;

--
-- Restrições para tabelas `solicitacao_orientacao`
--
ALTER TABLE `solicitacao_orientacao`
  ADD CONSTRAINT `solicitacao_orientacao_ibfk_1` FOREIGN KEY (`ID_Aluno`) REFERENCES `aluno2` (`ID_Aluno`) ON DELETE CASCADE;

--
-- Restrições para tabelas `usuario2`
--
ALTER TABLE `usuario2`
  ADD CONSTRAINT `usuario2_ibfk_1` FOREIGN KEY (`ID_Foto`) REFERENCES `foto` (`ID_Foto`);

--
-- Restrições para tabelas `usuario_preferencia`
--
ALTER TABLE `usuario_preferencia`
  ADD CONSTRAINT `usuario_preferencia_ibfk_1` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuario2` (`ID_Usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `usuario_preferencia_ibfk_2` FOREIGN KEY (`ID_Preferencia`) REFERENCES `preferencia` (`ID_Preferencia`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
