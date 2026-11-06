<?php

require_once __DIR__ . '/SiteBaseController.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Carrinho.php';

class PedidoController extends SiteBaseController
{
    private PDO $conn;
    private Carrinho $carrinho;

    public function __construct()
    {
        $this->conn = Database::getConnection();
        $this->carrinho = new Carrinho();
    }

    /**
     * Exibe a página de checkout.
     */
    public function checkout(): void
    {
        if (!cliente_logado()) {
            $this->redirecionarSite('cliente', 'login');
            return;
        }

        $itens = $this->carrinho->listar();

        if (empty($itens)) {
            $this->redirecionarSite('carrinho', 'index');
            return;
        }

        $cpf = preg_replace(
            '/\D/',
            '',
            $_SESSION['cliente_cpf'] ?? ''
        );

        $stmt = $this->conn->prepare("
            SELECT *
            FROM cliente_site
            WHERE cpf = :cpf
            LIMIT 1
        ");

        $stmt->execute([':cpf' => $cpf]);

        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->renderSite('checkout', [
            'itens' => $itens,
            'subtotal' => $this->carrinho->subtotal(),
            'cliente' => $cliente
        ]);
    }

    /**
     * Registra o pedido e dá baixa no estoque.
     */
    public function finalizar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionarSite('carrinho', 'index');
            return;
        }

        if (!cliente_logado()) {
            $this->redirecionarSite('cliente', 'login');
            return;
        }

        if (function_exists('csrf_validar')) {
            csrf_validar();
        }

        $itens = $this->carrinho->listar();

        if (empty($itens)) {
            $this->redirecionarSite('carrinho', 'index');
            return;
        }

        $cpf = preg_replace(
            '/\D/',
            '',
            $_SESSION['cliente_cpf'] ?? ''
        );

        $endereco = trim($_POST['endereco'] ?? '');
        $numero = trim($_POST['numero'] ?? '');
        $complemento = trim($_POST['complemento'] ?? '');
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = strtoupper(trim($_POST['estado'] ?? ''));

        $cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');

        $formaPagamento = $_POST['forma_pagamento'] ?? 'pix';

        $frete = (float) ($_POST['valor_frete'] ?? 0);
        $prazoFrete = (int) ($_POST['prazo_frete_dias'] ?? 0);

        if (
            $endereco === '' ||
            $numero === '' ||
            $bairro === '' ||
            $cidade === '' ||
            strlen($cep) !== 8
        ) {
            $_SESSION['site_erro'] = 'Preencha corretamente os dados de entrega.';
            $this->redirecionarSite('pedido', 'checkout');
            return;
        }

        $formasPermitidas = ['pix', 'cartao', 'boleto'];

        if (!in_array($formaPagamento, $formasPermitidas, true)) {
            $formaPagamento = 'pix';
        }

        if (!is_finite($frete) || $frete < 0) {
            $frete = 0;
        }

        $enderecoCompleto = $endereco . ', nº ' . $numero;

        if ($complemento !== '') {
            $enderecoCompleto .= ', ' . $complemento;
        }

        $enderecoCompleto .= ', ' . $bairro;
        $enderecoCompleto .= ', ' . $cidade . ' - ' . $estado;

        $subtotal = 0;
        $itensValidados = [];

        try {
            $this->conn->beginTransaction();

            foreach ($itens as $item) {
                $variacaoId = (int) ($item['variacao_id'] ?? 0);
                $quantidade = (int) ($item['quantidade'] ?? 0);

                if ($variacaoId <= 0 || $quantidade <= 0) {
                    throw new RuntimeException('Item inválido no carrinho.');
                }

                $stmtEstoque = $this->conn->prepare("
                    SELECT
                        v.id,
                        v.preco,
                        COALESCE(e.quantidade, 0) AS estoque
                    FROM variacao v
                    LEFT JOIN estoque e
                        ON e.variacao_id = v.id
                    WHERE v.id = :id
                    FOR UPDATE
                ");

                $stmtEstoque->execute([
                    ':id' => $variacaoId
                ]);

                $variacao = $stmtEstoque->fetch(PDO::FETCH_ASSOC);

                if (!$variacao) {
                    throw new RuntimeException('Um produto do carrinho não está mais disponível.');
                }

                if ((int) $variacao['estoque'] < $quantidade) {
                    throw new RuntimeException('Estoque insuficiente para um dos produtos.');
                }

                $preco = (float) $variacao['preco'];

                $subtotal += $preco * $quantidade;

                $itensValidados[] = [
                    'variacao_id' => $variacaoId,
                    'quantidade' => $quantidade,
                    'preco_unitario' => $preco
                ];
            }

            $valorTotal = $subtotal + $frete;

            $stmtPedido = $this->conn->prepare("
                INSERT INTO pedido (
                    cliente_cpf,
                    endereco_entrega,
                    cep_entrega,
                    valor_frete,
                    prazo_frete_dias,
                    forma_pagamento,
                    valor_total
                ) VALUES (
                    :cpf,
                    :endereco,
                    :cep,
                    :frete,
                    :prazo,
                    :pagamento,
                    :total
                )
            ");

            $stmtPedido->execute([
                ':cpf' => $cpf,
                ':endereco' => $enderecoCompleto,
                ':cep' => $cep,
                ':frete' => $frete,
                ':prazo' => $prazoFrete > 0 ? $prazoFrete : null,
                ':pagamento' => $formaPagamento,
                ':total' => $valorTotal
            ]);

            $pedidoId = (int) $this->conn->lastInsertId();

            $stmtItem = $this->conn->prepare("
                INSERT INTO pedido_item (
                    pedido_id,
                    variacao_id,
                    quantidade,
                    preco_unitario
                ) VALUES (
                    :pedido_id,
                    :variacao_id,
                    :quantidade,
                    :preco
                )
            ");

            $stmtBaixaEstoque = $this->conn->prepare("
                UPDATE estoque
                SET quantidade = quantidade - :quantidade
                WHERE variacao_id = :variacao_id
                  AND quantidade >= :quantidade_validacao
            ");

            foreach ($itensValidados as $item) {
                $stmtItem->execute([
                    ':pedido_id' => $pedidoId,
                    ':variacao_id' => $item['variacao_id'],
                    ':quantidade' => $item['quantidade'],
                    ':preco' => $item['preco_unitario']
                ]);

                $stmtBaixaEstoque->execute([
                    ':quantidade' => $item['quantidade'],
                    ':variacao_id' => $item['variacao_id'],
                    ':quantidade_validacao' => $item['quantidade']
                ]);

                if ($stmtBaixaEstoque->rowCount() !== 1) {
                    throw new RuntimeException('Não foi possível atualizar o estoque.');
                }
            }

            $this->conn->commit();

            $this->carrinho->limpar();

            // Após registrar o pedido, direciona para o pagamento simulado.
            $this->redirecionarSite(
                'pedido',
                'pagamento',
                ['id' => $pedidoId]
            );

        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }

            $_SESSION['site_erro'] = $e instanceof RuntimeException
                ? $e->getMessage()
                : 'Não foi possível finalizar seu pedido. Tente novamente.';

            $this->redirecionarSite('pedido', 'checkout');
        }
    }

    /**
     * Exibe a página de pagamento simulado.
     */
    public function pagamento(): void
    {
        if (!cliente_logado()) {
            $this->redirecionarSite('cliente', 'login');
            return;
        }

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->renderSite('nao_encontrado');
            return;
        }

        $cpf = preg_replace(
            '/\D/',
            '',
            $_SESSION['cliente_cpf'] ?? ''
        );

        $stmt = $this->conn->prepare("
            SELECT *
            FROM pedido
            WHERE id = :id
              AND cliente_cpf = :cpf
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id,
            ':cpf' => $cpf
        ]);

        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            $this->renderSite('nao_encontrado');
            return;
        }

        // Se o pedido já foi pago, não permite pagar novamente.
        if ($pedido['status'] !== 'pendente') {
            $this->redirecionarSite(
                'pedido',
                'confirmacao',
                ['id' => $id]
            );
            return;
        }

        $this->renderSite('pagamento', [
            'pedido' => $pedido
        ]);
    }

    /**
     * Simula a confirmação do pagamento.
     * Não realiza nenhuma cobrança real.
     */
    public function simularPagamento(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirecionarSite('loja', 'index');
            return;
        }

        if (!cliente_logado()) {
            $this->redirecionarSite('cliente', 'login');
            return;
        }

        if (function_exists('csrf_validar')) {
            csrf_validar();
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->renderSite('nao_encontrado');
            return;
        }

        $cpf = preg_replace(
            '/\D/',
            '',
            $_SESSION['cliente_cpf'] ?? ''
        );

        $stmt = $this->conn->prepare("
            UPDATE pedido
            SET status = 'pago'
            WHERE id = :id
              AND cliente_cpf = :cpf
              AND status = 'pendente'
        ");

        $stmt->execute([
            ':id' => $id,
            ':cpf' => $cpf
        ]);

        if ($stmt->rowCount() !== 1) {
            $_SESSION['site_erro'] = 'Não foi possível simular o pagamento.';

            $this->redirecionarSite(
                'pedido',
                'pagamento',
                ['id' => $id]
            );
            return;
        }

        $_SESSION['site_sucesso'] = 'Pagamento simulado com sucesso!';

        $this->redirecionarSite(
            'pedido',
            'confirmacao',
            ['id' => $id]
        );
    }

    /**
     * Exibe a confirmação e os detalhes do pedido.
     */
    public function confirmacao(): void
    {
        if (!cliente_logado()) {
            $this->redirecionarSite('cliente', 'login');
            return;
        }

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $this->renderSite('nao_encontrado');
            return;
        }

        $cpf = preg_replace(
            '/\D/',
            '',
            $_SESSION['cliente_cpf'] ?? ''
        );

        $stmt = $this->conn->prepare("
            SELECT *
            FROM pedido
            WHERE id = :id
              AND cliente_cpf = :cpf
            LIMIT 1
        ");

        $stmt->execute([
            ':id' => $id,
            ':cpf' => $cpf
        ]);

        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pedido) {
            $this->renderSite('nao_encontrado');
            return;
        }

        $stmtItens = $this->conn->prepare("
            SELECT
                pi.quantidade,
                pi.preco_unitario,
                p.nome AS produto_nome,
                v.nome AS tamanho
            FROM pedido_item pi
            INNER JOIN variacao v
                ON v.id = pi.variacao_id
            INNER JOIN produto p
                ON p.id = v.produto_id
            WHERE pi.pedido_id = :pedido_id
        ");

        $stmtItens->execute([
            ':pedido_id' => $id
        ]);

        $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

        $this->renderSite('confirmacao', [
            'pedido' => $pedido,
            'itens' => $itens
        ]);
    }

    /**
     * Lista os pedidos realizados pelo cliente.
     */
    public function meus(): void
    {
        if (!cliente_logado()) {
            $this->redirecionarSite('cliente', 'login');
            return;
        }

        $cpf = preg_replace(
            '/\D/',
            '',
            $_SESSION['cliente_cpf'] ?? ''
        );

        $stmt = $this->conn->prepare("
            SELECT
                id,
                data,
                status,
                valor_total,
                forma_pagamento
            FROM pedido
            WHERE cliente_cpf = :cpf
            ORDER BY data DESC
        ");

        $stmt->execute([
            ':cpf' => $cpf
        ]);

        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->renderSite('meus_pedidos', [
            'pedidos' => $pedidos
        ]);
    }
}