<?php

$tituloPagina = 'Meus pedidos';

$pedidos = $pedidos ?? [];

$statusTexto = [
    'pendente' => 'Pendente',
    'pago' => 'Pago',
    'enviado' => 'Enviado',
    'entregue' => 'Entregue',
    'cancelado' => 'Cancelado'
];

?>

<section class="pagina pagina-pedidos">

    <div class="container">

        <div class="pagina-titulo">

            <h1>Meus pedidos</h1>

            <p>
                Acompanhe suas compras e confira o histórico dos seus pedidos.
            </p>

        </div>

        <?php if (empty($pedidos)): ?>

            <div class="vazio">

                <i class="fa-solid fa-box-open" aria-hidden="true"></i>

                <h2>Você ainda não realizou pedidos</h2>

                <p>
                    Que tal escolher alguns donuts deliciosos para começar?
                </p>

                <a
                    href="<?= e(url_site('loja', 'index')) ?>"
                    class="btn"
                >
                    Conhecer o cardápio
                </a>

            </div>

        <?php else: ?>

            <div class="pedidos-lista">

                <?php foreach ($pedidos as $pedido): ?>

                    <?php
                    $status = $pedido['status'] ?? 'pendente';

                    $classeStatus = 'status-' . $status;

                    $data = !empty($pedido['data'])
                        ? date('d/m/Y H:i', strtotime($pedido['data']))
                        : 'Data não informada';
                    ?>

                    <article class="painel pedido-card">

                        <div class="pedido-card-topo">

                            <div>
                                <span class="pedido-legenda">
                                    Pedido
                                </span>

                                <h2>
                                    #<?= (int) $pedido['id'] ?>
                                </h2>
                            </div>

                            <span class="pedido-status <?= e($classeStatus) ?>">
                                <?= e($statusTexto[$status] ?? ucfirst($status)) ?>
                            </span>

                        </div>

                        <div class="pedido-card-informacoes">

                            <div>
                                <span>Data da compra</span>
                                <strong><?= e($data) ?></strong>
                            </div>

                            <div>
                                <span>Pagamento</span>

                                <strong>
                                    <?= e(ucfirst($pedido['forma_pagamento'] ?? '')) ?>
                                </strong>
                            </div>

                            <div>
                                <span>Total</span>

                                <strong>
                                    <?= e(moeda((float) $pedido['valor_total'])) ?>
                                </strong>
                            </div>

                        </div>

                        <div class="pedido-card-rodape">

                            <a
                                href="<?= e(url_site('pedido', 'confirmacao', [
                                    'id' => (int) $pedido['id']
                                ])) ?>"
                                class="btn btn-pequeno"
                            >
                                Ver detalhes
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>