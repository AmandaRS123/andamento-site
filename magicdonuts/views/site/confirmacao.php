<?php

$tituloPagina = 'Pedido confirmado';

$pedido = $pedido ?? [];
$itens = $itens ?? [];

$status = $pedido['status'] ?? 'pendente';

$statusTexto = [
    'pendente' => 'Aguardando pagamento',
    'pago' => 'Pagamento aprovado',
    'enviado' => 'Pedido enviado',
    'entregue' => 'Pedido entregue',
    'cancelado' => 'Pedido cancelado'
];

?>

<section class="pagina pagina-confirmacao">

    <div class="container">

        <div class="confirmacao-card">

            <div class="confirmacao-icone">
                <i class="fa-solid fa-check" aria-hidden="true"></i>
            </div>

            <h1>Pedido registrado!</h1>

            <p>
                Obrigado por comprar na Magic Donuts!
                Recebemos seu pedido e estamos preparando tudo com carinho.
            </p>

            <div class="confirmacao-numero">
                <span>Número do pedido</span>

                <strong>
                    #<?= (int) ($pedido['id'] ?? 0) ?>
                </strong>
            </div>

            <div class="confirmacao-status">
                <span>Status do pedido</span>

                <strong>
                    <?= e($statusTexto[$status] ?? ucfirst($status)) ?>
                </strong>
            </div>

        </div>

        <div class="painel confirmacao-detalhes">

            <h2>Detalhes da compra</h2>

            <div class="detalhes-pedido">

                <div>
                    <span>Data</span>

                    <strong>
                        <?= !empty($pedido['data'])
                            ? e(date('d/m/Y H:i', strtotime($pedido['data'])))
                            : 'Não informada' ?>
                    </strong>
                </div>

                <div>
                    <span>Pagamento</span>

                    <strong>
                        <?= e(strtoupper($pedido['forma_pagamento'] ?? '')) ?>
                    </strong>
                </div>

                <div>
                    <span>Endereço de entrega</span>

                    <strong>
                        <?= e($pedido['endereco_entrega'] ?? '') ?>
                    </strong>
                </div>

                <div>
                    <span>CEP</span>

                    <strong>
                        <?= e($pedido['cep_entrega'] ?? '') ?>
                    </strong>
                </div>

            </div>

            <h3>Produtos</h3>

            <?php foreach ($itens as $item): ?>

                <div class="checkout-produto">

                    <div>
                        <strong><?= e($item['produto_nome']) ?></strong>

                        <small>
                            <?= (int) $item['quantidade'] ?> unidade(s)
                            · <?= e($item['tamanho']) ?>
                        </small>
                    </div>

                    <strong>
                        <?= e(moeda(
                            (float) $item['preco_unitario'] *
                            (int) $item['quantidade']
                        )) ?>
                    </strong>

                </div>

            <?php endforeach; ?>

            <dl class="resumo-linhas">

                <div class="resumo-linha">
                    <dt>Frete</dt>

                    <dd>
                        <?= e(moeda((float) ($pedido['valor_frete'] ?? 0))) ?>
                    </dd>
                </div>

                <div class="resumo-linha resumo-total">
                    <dt>Total</dt>

                    <dd>
                        <?= e(moeda((float) ($pedido['valor_total'] ?? 0))) ?>
                    </dd>
                </div>

            </dl>

        </div>

        <div class="confirmacao-acoes">

            <a
                href="<?= e(url_site('pedido', 'meus')) ?>"
                class="btn"
            >
                Acompanhar meus pedidos
            </a>

            <a
                href="<?= e(url_site('loja', 'index')) ?>"
                class="btn btn-contorno"
            >
                Voltar para a loja
            </a>

        </div>

    </div>

</section>