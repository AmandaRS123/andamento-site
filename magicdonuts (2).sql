-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05-Nov-2026 às 18:13
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `magicdonuts`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `ativo`) VALUES
(8, 'Com recheio', 1),
(9, 'Sem recheio', 1),
(10, 'Especial', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cpf_cnpj` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id`, `nome`, `cpf_cnpj`, `telefone`, `email`, `endereco`) VALUES
(1, 'Mariana Oliveira', '12345678901', '(21) 98765-4321', 'mariana.oliveira@example.com', 'Rua das Flores, 125, Centro, Nova Iguaçu - RJ'),
(2, 'Beatriz Santos', '23456789012', '(21) 97654-3210', 'beatriz.santos@example.com', 'Rua do Sol, 240, Jardim Tropical, Nova Iguaçu - RJ'),
(3, 'Gabriel Costa', '34567890123', '(21) 96543-2109', 'gabriel.costa@example.com', 'Avenida Brasil, 580, Posse, Nova Iguaçu - RJ');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente_site`
--

CREATE TABLE `cliente_site` (
  `cpf` char(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha_hash` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `entrada_item`
--

CREATE TABLE `entrada_item` (
  `id` int(11) NOT NULL,
  `entrada_id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `custo_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `entrada_item`
--

INSERT INTO `entrada_item` (`id`, `entrada_id`, `variacao_id`, `quantidade`, `custo_unitario`) VALUES
(7, 6, 2, 30, 11.00),
(8, 6, 1, 60, 4.00),
(9, 7, 3, 60, 9.00),
(10, 7, 4, 100, 3.00),
(11, 8, 5, 20, 5.00),
(12, 8, 6, 30, 4.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `entrada_mercadoria`
--

CREATE TABLE `entrada_mercadoria` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `status` enum('rascunho','confirmar') NOT NULL DEFAULT 'rascunho',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `entrada_mercadoria`
--

INSERT INTO `entrada_mercadoria` (`id`, `fornecedor_id`, `data`, `status`, `valor_total`) VALUES
(6, 2, '2026-10-08', 'confirmar', 570.00),
(7, 1, '2026-10-08', 'confirmar', 840.00),
(8, 2, '2026-10-08', 'confirmar', 220.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `minimo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `estoque`
--

INSERT INTO `estoque` (`id`, `variacao_id`, `quantidade`, `minimo`) VALUES
(1, 2, 60, 0),
(2, 1, 120, 0),
(5, 3, 57, 0),
(6, 4, 95, 0),
(7, 5, 17, 0),
(8, 6, 24, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id`, `nome`, `cnpj`, `telefone`, `email`, `endereco`, `cep`, `ativo`) VALUES
(1, 'Jorge', '12.345.678/1234-12', '21 94321-6578', 'jorge1@gmail.com', 'Rua das Cerejeiras, nº 125, Nova Iguaçu', NULL, 1),
(2, 'Carlos Junior', '98.431.948/1142-99', '21 99987-3345', 'junin123@gmail.com', 'Av. Abílio Augusto Távora, 1.111 - Bairro da Luz, Nova Iguaçu', NULL, 1),
(3, 'Joana Rubens', '3434576523576', '4345433333', '091@gmail.com', 'Rua Beatriz, Valverde, Nova Iguaçu/RJ', '26290-672', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `movimento_estoque`
--

CREATE TABLE `movimento_estoque` (
  `id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `origem` enum('entrada','venda') NOT NULL,
  `observacao` varchar(255) DEFAULT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `movimento_estoque`
--

INSERT INTO `movimento_estoque` (`id`, `variacao_id`, `tipo`, `quantidade`, `origem`, `observacao`, `data`) VALUES
(1, 2, 'entrada', 30, 'entrada', 'Entrada de mercadoria nº 5', '2026-10-08 21:18:59'),
(2, 1, 'entrada', 60, 'entrada', 'Entrada de mercadoria nº 5', '2026-10-08 21:18:59'),
(3, 2, 'entrada', 30, 'entrada', 'Entrada de mercadoria nº 6', '2026-10-08 21:20:39'),
(4, 1, 'entrada', 60, 'entrada', 'Entrada de mercadoria nº 6', '2026-10-08 21:20:39'),
(5, 3, 'entrada', 60, 'entrada', 'Entrada de mercadoria nº 7', '2026-10-08 21:36:39'),
(6, 4, 'entrada', 100, 'entrada', 'Entrada de mercadoria nº 7', '2026-10-08 21:36:39'),
(7, 3, 'saida', 3, 'venda', 'Venda nº 4', '2026-10-08 21:46:15'),
(8, 4, 'saida', 5, 'venda', 'Venda nº 4', '2026-10-08 21:46:15'),
(9, 5, 'entrada', 20, 'entrada', 'Entrada de mercadoria nº 8', '2026-10-08 21:49:08'),
(10, 6, 'entrada', 30, 'entrada', 'Entrada de mercadoria nº 8', '2026-10-08 21:49:08'),
(11, 6, 'saida', 4, 'venda', 'Venda nº 5', '2026-10-08 21:49:33'),
(12, 5, 'saida', 3, 'venda', 'Saída referente à venda nº 6', '2026-10-29 20:06:25'),
(13, 6, 'saida', 2, 'venda', 'Saída referente à venda nº 11', '2026-10-29 21:38:59');

-- --------------------------------------------------------

--
-- Estrutura da tabela `nota_fiscal_entrada`
--

CREATE TABLE `nota_fiscal_entrada` (
  `id` int(11) NOT NULL,
  `entrada_id` int(11) NOT NULL,
  `modelo` varchar(5) NOT NULL,
  `serie` varchar(5) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `chave_acesso` varchar(44) NOT NULL,
  `data_emissao` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `nota_fiscal_venda`
--

CREATE TABLE `nota_fiscal_venda` (
  `id` int(11) NOT NULL,
  `venda_id` int(11) NOT NULL,
  `modelo` varchar(5) NOT NULL,
  `serie` varchar(5) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `data_emissao` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido`
--

CREATE TABLE `pedido` (
  `id` int(11) NOT NULL,
  `cliente_cpf` char(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pendente','pago','enviado','entregue','cancelado') NOT NULL DEFAULT 'pendente',
  `endereco_entrega` varchar(255) NOT NULL,
  `cep_entrega` varchar(9) DEFAULT NULL,
  `valor_frete` decimal(10,2) NOT NULL DEFAULT 0.00,
  `prazo_frete_dias` int(11) DEFAULT NULL,
  `forma_pagamento` enum('pix','cartao','boleto') NOT NULL DEFAULT 'pix',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pedido_item`
--

CREATE TABLE `pedido_item` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`id`, `categoria_id`, `nome`, `descricao`, `ativo`) VALUES
(1, 8, 'ChocoDream', NULL, 1),
(2, 10, 'Ninho com Morango', NULL, 1),
(3, 9, 'Donut tradicional', NULL, 1),
(4, 8, 'Donut arco-íris', NULL, 1),
(5, 8, 'Choco meio-amargo', NULL, 1),
(6, 9, 'Donut Fit', NULL, 1),
(7, 10, 'Oreo Crocante', NULL, 1),
(8, 10, 'Edição limitada', NULL, 1),
(30, 8, 'Donut fofo', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','vendedor') NOT NULL,
  `ativo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `perfil`, `ativo`) VALUES
(2, 'Amanda', 'amandaramossoares1@gmail.com', '$2y$10$lEGGxijfdNrt8NqafmgOYONZqhHHSvDUM2WNhCC/asTQ9zZ4svy2a', 'admin', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `variacao`
--

CREATE TABLE `variacao` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `tamanho` varchar(30) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `variacao`
--

INSERT INTO `variacao` (`id`, `produto_id`, `tamanho`, `sku`, `preco`) VALUES
(1, 30, 'Pequeno', 'DON-001', 6.00),
(2, 30, 'Grande', 'DON-002', 15.00),
(3, 6, 'Grande', 'DON-003', 13.00),
(4, 6, 'Pequeno', 'DON-004', 5.00),
(5, 4, 'Grande', 'DON-005', 10.00),
(6, 4, 'Médio', 'DON-006', 6.00);

-- --------------------------------------------------------

--
-- Estrutura da tabela `venda`
--

CREATE TABLE `venda` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `data` date NOT NULL,
  `status` enum('aberta','finalizada') NOT NULL DEFAULT 'aberta',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `nf_emitida` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `venda`
--

INSERT INTO `venda` (`id`, `usuario_id`, `cliente_id`, `data`, `status`, `valor_total`, `nf_emitida`) VALUES
(4, 2, 2, '2026-10-08', 'finalizada', 64.00, 0),
(5, 2, 3, '2026-10-08', 'finalizada', 24.00, 0),
(6, 2, 2, '2026-10-29', 'finalizada', 30.00, 0),
(11, 2, 1, '2026-10-29', 'finalizada', 12.00, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `venda_item`
--

CREATE TABLE `venda_item` (
  `id` int(11) NOT NULL,
  `venda_id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `venda_item`
--

INSERT INTO `venda_item` (`id`, `venda_id`, `variacao_id`, `quantidade`, `preco_unitario`) VALUES
(2, 4, 3, 3, 13.00),
(3, 4, 4, 5, 5.00),
(4, 5, 6, 4, 6.00),
(5, 6, 5, 3, 10.00),
(6, 11, 6, 2, 6.00);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cliente_site`
--
ALTER TABLE `cliente_site`
  ADD PRIMARY KEY (`cpf`),
  ADD UNIQUE KEY `uk_cliente_site_email` (`email`);

--
-- Índices para tabela `entrada_item`
--
ALTER TABLE `entrada_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_entrada_item_entrada` (`entrada_id`),
  ADD KEY `idx_entrada_item_variacao` (`variacao_id`);

--
-- Índices para tabela `entrada_mercadoria`
--
ALTER TABLE `entrada_mercadoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_entrada_fornecedor` (`fornecedor_id`),
  ADD KEY `idx_entrada_data` (`data`),
  ADD KEY `idx_entrada_status` (`status`);

--
-- Índices para tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variacao_id` (`variacao_id`);

--
-- Índices para tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `movimento_estoque`
--
ALTER TABLE `movimento_estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variacao_id` (`variacao_id`);

--
-- Índices para tabela `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entrada_id` (`entrada_id`),
  ADD UNIQUE KEY `chave_acesso` (`chave_acesso`),
  ADD KEY `idx_nf_entrada_data` (`data_emissao`);

--
-- Índices para tabela `nota_fiscal_venda`
--
ALTER TABLE `nota_fiscal_venda`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `venda_id` (`venda_id`),
  ADD UNIQUE KEY `numero` (`numero`),
  ADD KEY `idx_nf_venda_data` (`data_emissao`);

--
-- Índices para tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedido_cliente_cpf` (`cliente_cpf`);

--
-- Índices para tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pedido_item_pedido` (`pedido_id`),
  ADD KEY `idx_pedido_item_variacao` (`variacao_id`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produto_categoria` (`categoria_id`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices para tabela `variacao`
--
ALTER TABLE `variacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_variacao_produto` (`produto_id`);

--
-- Índices para tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_venda_usuario` (`usuario_id`),
  ADD KEY `idx_venda_cliente` (`cliente_id`),
  ADD KEY `idx_venda_data` (`data`),
  ADD KEY `idx_venda_status` (`status`);

--
-- Índices para tabela `venda_item`
--
ALTER TABLE `venda_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_venda_item_venda` (`venda_id`),
  ADD KEY `idx_venda_item_variacao` (`variacao_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `entrada_item`
--
ALTER TABLE `entrada_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `entrada_mercadoria`
--
ALTER TABLE `entrada_mercadoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `movimento_estoque`
--
ALTER TABLE `movimento_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `nota_fiscal_venda`
--
ALTER TABLE `nota_fiscal_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `variacao`
--
ALTER TABLE `variacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `venda_item`
--
ALTER TABLE `venda_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `entrada_item`
--
ALTER TABLE `entrada_item`
  ADD CONSTRAINT `fk_entrada_item_entrada` FOREIGN KEY (`entrada_id`) REFERENCES `entrada_mercadoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_entrada_item_variacao` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `entrada_mercadoria`
--
ALTER TABLE `entrada_mercadoria`
  ADD CONSTRAINT `fk_entrada_fornecedor` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `fk_estoque_variacao` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `movimento_estoque`
--
ALTER TABLE `movimento_estoque`
  ADD CONSTRAINT `movimento_estoque_ibfk_1` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`);

--
-- Limitadores para a tabela `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  ADD CONSTRAINT `fk_nf_entrada_entrada` FOREIGN KEY (`entrada_id`) REFERENCES `entrada_mercadoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `nota_fiscal_venda`
--
ALTER TABLE `nota_fiscal_venda`
  ADD CONSTRAINT `fk_nf_venda_venda` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_cliente_site` FOREIGN KEY (`cliente_cpf`) REFERENCES `cliente_site` (`cpf`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `pedido_item`
--
ALTER TABLE `pedido_item`
  ADD CONSTRAINT `fk_pedido_item_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pedido_item_variacao` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `variacao`
--
ALTER TABLE `variacao`
  ADD CONSTRAINT `fk_variacao_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `fk_venda_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venda_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `venda_item`
--
ALTER TABLE `venda_item`
  ADD CONSTRAINT `fk_venda_item_variacao` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_venda_item_venda` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
