<?php

$tituloPagina = 'Finalizar pedido';

$itens = $itens ?? [];
$subtotal = (float) ($subtotal ?? 0);
$cliente = $cliente ?? [];

$valorFrete = 0;

?>

<section class="pagina pagina-checkout">

    <div class="container">

        <div class="pagina-titulo">
            <h1>Finalizar pedido</h1>
            <p>Estamos quase lá! Confira seus produtos e informe os dados de entrega.</p>
        </div>

        <?php if (empty($itens)): ?>

            <div class="vazio">
                <h2>Seu carrinho está vazio</h2>

                <a class="btn" href="<?= e(url_site('loja', 'index')) ?>">
                    Voltar para a loja
                </a>
            </div>

        <?php else: ?>

            <form
                method="post"
                action="<?= e(url_site('pedido', 'finalizar')) ?>"
                class="checkout-layout"
            >

                <?= csrf_campo() ?>

                <div class="checkout-formularios">

                    <section class="painel">
                        <h2>
                            <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                            Endereço de entrega
                        </h2>

                        <div class="campo">
                            <label for="cep">CEP</label>

                            <input
                                type="text"
                                id="cep"
                                name="cep"
                                placeholder="00000-000"
                                maxlength="9"
                                inputmode="numeric"
                                autocomplete="postal-code"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="endereco">Rua / Avenida</label>

                            <input
                                type="text"
                                id="endereco"
                                name="endereco"
                                value="<?= e($cliente['endereco'] ?? '') ?>"
                                maxlength="150"
                                autocomplete="address-line1"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="numero">Número</label>

                            <input
                                type="text"
                                id="numero"
                                name="numero"
                                maxlength="15"
                                required
                            >
                        </div>

                        <div class="campo">
                            <label for="complemento">Complemento</label>

                            <input
                                type="text"
                                id="complemento"
                                name="complemento"
                                maxlength="80"
                                placeholder="Apartamento, bloco, referência..."
                            >
                        </div>

                        <div class="campo">
                            <label for="bairro">Bairro</label>

                            <input
                                type="text"
                                id="bairro"
                                name="bairro"
                                maxlength="80"
                                required
                            >
                        </div>

                        <div class="checkout-duas-colunas">

                            <div class="campo">
                                <label for="cidade">Cidade</label>

                                <input
                                    type="text"
                                    id="cidade"
                                    name="cidade"
                                    maxlength="80"
                                    required
                                >
                            </div>

                            <div class="campo">
                                <label for="estado">Estado</label>

                                <input
                                    type="text"
                                    id="estado"
                                    name="estado"
                                    maxlength="2"
                                    placeholder="UF"
                                    required
                                >
                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn btn-contorno btn-pequeno"
                            id="buscar-cep"
                        >
                            Buscar endereço pelo CEP
                        </button>

                        <p id="resultado-cep" class="frete-resultado" aria-live="polite"></p>

                    </section>

                    <section class="painel">

                        <h2>
                            <i class="fa-solid fa-credit-card" aria-hidden="true"></i>
                            Forma de pagamento
                        </h2>

                        <div class="pagamento-opcoes">

                            <label class="pagamento-opcao">
                                <input
                                    type="radio"
                                    name="forma_pagamento"
                                    value="pix"
                                    checked
                                >

                                <span>
                                    <i class="fa-brands fa-pix" aria-hidden="true"></i>

                                    <strong>PIX</strong>

                                    <small>Pagamento via PIX</small>
                                </span>
                            </label>

                            <label class="pagamento-opcao">
                                <input
                                    type="radio"
                                    name="forma_pagamento"
                                    value="cartao"
                                >

                                <span>
                                    <i class="fa-solid fa-credit-card" aria-hidden="true"></i>

                                    <strong>Cartão</strong>

                                    <small>Pagamento com cartão</small>
                                </span>
                            </label>

                            <label class="pagamento-opcao">
                                <input
                                    type="radio"
                                    name="forma_pagamento"
                                    value="boleto"
                                >

                                <span>
                                    <i class="fa-solid fa-barcode" aria-hidden="true"></i>

                                    <strong>Boleto</strong>

                                    <small>Pagamento por boleto</small>
                                </span>
                            </label>

                        </div>

                    </section>

                </div>

                <aside class="painel resumo checkout-resumo">

                    <h2>Resumo do pedido</h2>

                    <div class="checkout-produtos">

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
                                    <?= e(moeda($item['subtotal'])) ?>
                                </strong>

                            </div>

                        <?php endforeach; ?>

                    </div>

                    <dl class="resumo-linhas">

                        <div class="resumo-linha">
                            <dt>Subtotal</dt>
                            <dd><?= e(moeda($subtotal)) ?></dd>
                        </div>

                        <div class="resumo-linha">
                            <dt>Frete</dt>
                            <dd>A calcular</dd>
                        </div>

                        <div class="resumo-linha resumo-total">
                            <dt>Total parcial</dt>
                            <dd><?= e(moeda($subtotal)) ?></dd>
                        </div>

                    </dl>

                    <input
                        type="hidden"
                        name="valor_frete"
                        value="<?= e((string) $valorFrete) ?>"
                    >

                    <button type="submit" class="btn btn-bloco">
                        Confirmar pedido
                    </button>

                    <a
                        href="<?= e(url_site('carrinho', 'index')) ?>"
                        class="auth-voltar"
                    >
                        Voltar ao carrinho
                    </a>

                </aside>

            </form>

        <?php endif; ?>

    </div>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const botao = document.getElementById('buscar-cep');

    if (!botao) return;

    botao.addEventListener('click', async function () {

        const cep = document.getElementById('cep').value.replace(/\D/g, '');
        const resultado = document.getElementById('resultado-cep');

        if (cep.length !== 8) {
            resultado.textContent = 'Digite um CEP válido com 8 números.';
            return;
        }

        resultado.textContent = 'Buscando endereço...';
        botao.disabled = true;

        try {

            const resposta = await fetch(
                '<?= e(url_site('cep', 'buscar')) ?>&cep=' + cep
            );

            const dados = await resposta.json();

            if (dados.erro) {
                resultado.textContent = 'CEP não encontrado.';
                return;
            }

            document.getElementById('endereco').value = dados.logradouro || '';
            document.getElementById('bairro').value = dados.bairro || '';
            document.getElementById('cidade').value = dados.cidade || '';
            document.getElementById('estado').value = dados.estado || '';

            resultado.textContent = 'Endereço encontrado!';

        } catch (erro) {

            resultado.textContent = 'Não foi possível consultar o CEP.';

        } finally {

            botao.disabled = false;

        }

    });

});
</script>