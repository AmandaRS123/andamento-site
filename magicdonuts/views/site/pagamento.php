<?php

$tituloPagina = 'Pagamento do pedido';

$pedido = $pedido ?? [];

$formaPagamento = $pedido['forma_pagamento'] ?? 'pix';
$valorTotal = (float) ($pedido['valor_total'] ?? 0);

$formas = [
    'pix' => 'PIX',
    'cartao' => 'Cartão',
    'boleto' => 'Boleto'
];

?>

<section class="pagina pagina-pagamento">

    <div class="container">

        <div class="pagina-titulo">
            <h1>Pagamento</h1>
            <p>Estamos quase finalizando sua compra!</p>
        </div>

        <div class="pagamento-card">

            <div class="pagamento-icone">
                <i class="fa-solid fa-credit-card" aria-hidden="true"></i>
            </div>

            <span class="pagamento-etiqueta">
                Simulação de pagamento
            </span>

            <h2>Pedido #<?= (int) ($pedido['id'] ?? 0) ?></h2>

            <p>
                Esta é uma etapa demonstrativa.
                Nenhum pagamento real será realizado.
            </p>

            <div class="pagamento-valor">

                <span>Valor do pedido</span>

                <strong><?= e(moeda($valorTotal)) ?></strong>

            </div>

            <div class="pagamento-metodo">

                <span>Forma de pagamento selecionada</span>

                <strong>
                    <?= e($formas[$formaPagamento] ?? 'Não identificada') ?>
                </strong>

            </div>

            <?php if ($formaPagamento === 'pix'): ?>

                <div class="pagamento-instrucao">

                    <i class="fa-brands fa-pix" aria-hidden="true"></i>

                    <h3>Pagamento via PIX</h3>

                    <p>
                        Em uma loja real, aqui seria apresentado o QR Code
                        ou o código PIX para pagamento.
                    </p>

                    <div class="pix-ficticio">
                        PIX DEMONSTRATIVO
                    </div>

                </div>

            <?php elseif ($formaPagamento === 'cartao'): ?>

                <div class="pagamento-instrucao">

                    <i class="fa-solid fa-credit-card" aria-hidden="true"></i>

                    <h3>Pagamento com cartão</h3>

                    <p>
                        Em uma loja real, o pagamento seria processado
                        por uma plataforma segura.
                    </p>

                    <div class="pagamento-ficticio">
                        <i class="fa-solid fa-lock" aria-hidden="true"></i>
                        Transação demonstrativa
                    </div>

                </div>

            <?php elseif ($formaPagamento === 'boleto'): ?>

                <div class="pagamento-instrucao">

                    <i class="fa-solid fa-barcode" aria-hidden="true"></i>

                    <h3>Pagamento via boleto</h3>

                    <p>
                        Em uma loja real, seria gerado um boleto bancário
                        com código de barras.
                    </p>

                    <div class="pagamento-ficticio">
                        BOLETO DEMONSTRATIVO
                    </div>

                </div>

            <?php endif; ?>

            <form
                method="post"
                action="<?= e(url_site('pedido', 'simularPagamento')) ?>"
            >

                <?= csrf_campo() ?>

                <input
                    type="hidden"
                    name="id"
                    value="<?= (int) ($pedido['id'] ?? 0) ?>"
                >

                <button type="submit" class="btn btn-bloco">
                    <i class="fa-solid fa-check" aria-hidden="true"></i>
                    Simular pagamento aprovado
                </button>

            </form>

            <small class="pagamento-aviso">
                Esta ação altera apenas o status demonstrativo do pedido.
                Nenhum valor será cobrado.
            </small>

        </div>

    </div>

</section>