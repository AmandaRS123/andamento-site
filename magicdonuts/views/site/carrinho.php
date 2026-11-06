
<?php

$itens = $itens ?? [];
$subtotal = (float) ($subtotal ?? 0);
$quantidadeItens = (int) ($quantidadeItens ?? 0);

$faltaFreteGratis = max(0, MD_FRETE_GRATIS_A_PARTIR - $subtotal);

?>

<section class="pagina">
    <div class="container">

        <div class="pagina-titulo">
            <h1>Seu carrinho</h1>

            <?php if (!empty($itens)): ?>
                <p>
                    <?= $quantidadeItens ?>
                    <?= $quantidadeItens === 1 ? 'unidade' : 'unidades' ?>
                    no carrinho.
                </p>
            <?php endif; ?>
        </div>

        <?php if (empty($itens)): ?>

            <div class="vazio">
                <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>

                <h2>Seu carrinho está vazio</h2>

                <p>Escolha seus donuts favoritos no cardápio e eles aparecem aqui.</p>

                <a class="btn" href="<?= e(url_site('loja', 'index')) ?>">
                    Ver o cardápio
                </a>
            </div>

        <?php else: ?>

            <div class="duas-colunas">

                <form
                    class="painel"
                    id="form-carrinho"
                    method="post"
                    action="<?= e(url_site('carrinho', 'atualizar')) ?>"
                >
                    <?= csrf_campo() ?>

                    <ul class="itens-lista">

                        <?php foreach ($itens as $item): ?>

                            <?php
                            $idVariacao = (int) $item['variacao_id'];
                            $linkProduto = url_site('loja', 'produto', [
                                'id' => (int) $item['produto_id']
                            ]);
                            ?>

                            <li class="item-carrinho">

                                <a
                                    class="item-foto"
                                    href="<?= e($linkProduto) ?>"
                                    tabindex="-1"
                                    aria-hidden="true"
                                >
                                    <img
                                        src="<?= e(imagem_produto((int) $item['produto_id'])) ?>"
                                        alt=""
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </a>

                                <div class="item-info">

                                    <h3>
                                        <a href="<?= e($linkProduto) ?>">
                                            <?= e($item['produto_nome']) ?>
                                        </a>
                                    </h3>

                                    <p>
                                        Tamanho: <?= e($item['tamanho']) ?>
                                        &middot;
                                        <?= e(moeda($item['preco'])) ?> cada
                                    </p>

                                    <div class="item-controles">

                                        <div class="quantidade quantidade-pequena" data-stepper>

                                            <button
                                                type="button"
                                                data-step="-1"
                                                aria-label="Diminuir quantidade de <?= e($item['produto_nome']) ?>"
                                            >
                                                <i class="fa-solid fa-minus" aria-hidden="true"></i>
                                            </button>

                                            <input
                                                type="number"
                                                name="quantidade[<?= $idVariacao ?>]"
                                                value="<?= (int) $item['quantidade'] ?>"
                                                min="1"
                                                max="<?= (int) $item['limite'] ?>"
                                                inputmode="numeric"
                                                aria-label="Quantidade de <?= e($item['produto_nome']) ?>"
                                                data-auto-atualizar
                                            >

                                            <button
                                                type="button"
                                                data-step="1"
                                                aria-label="Aumentar quantidade de <?= e($item['produto_nome']) ?>"
                                            >
                                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                                            </button>

                                        </div>

                                        <button
                                            class="btn-texto"
                                            type="submit"
                                            name="variacao_id"
                                            value="<?= $idVariacao ?>"
                                            formaction="<?= e(url_site('carrinho', 'remover')) ?>"
                                            formnovalidate
                                        >
                                            Remover
                                        </button>

                                    </div>
                                </div>

                                <div class="item-total">
                                    <strong><?= e(moeda($item['subtotal'])) ?></strong>
                                </div>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                    <div class="carrinho-rodape">

                        <a href="<?= e(url_site('loja', 'index')) ?>">
                            &larr; Continuar comprando
                        </a>

                        <noscript>
                            <button class="btn btn-pequeno btn-contorno" type="submit">
                                Atualizar quantidades
                            </button>
                        </noscript>

                        <button
                            class="btn-texto"
                            type="submit"
                            formaction="<?= e(url_site('carrinho', 'limpar')) ?>"
                            formnovalidate
                            onclick="return confirm('Esvaziar o carrinho?');"
                        >
                            Esvaziar carrinho
                        </button>

                    </div>

                </form>

                <aside class="painel resumo lateral" aria-label="Resumo do pedido">

                    <h2>Resumo</h2>

                    <dl class="resumo-linhas">

                        <div class="resumo-linha">
                            <dt>Subtotal</dt>
                            <dd id="resumo-subtotal"><?= e(moeda($subtotal)) ?></dd>
                        </div>

                        <div class="resumo-linha">
                            <dt>Frete</dt>
                            <dd id="resumo-frete">a calcular</dd>
                        </div>

                        <div class="resumo-linha resumo-total">
                            <dt>Total</dt>
                            <dd id="resumo-total"><?= e(moeda($subtotal)) ?></dd>
                        </div>

                    </dl>

                    <?php if ($faltaFreteGratis > 0): ?>

                        <p class="dica" style="margin-top: 12px;">
                            Faltam <?= e(moeda($faltaFreteGratis)) ?> para o frete grátis.
                        </p>

                    <?php else: ?>

                        <p class="dica" style="margin-top: 12px;">
                            Seu pedido tem frete grátis.
                        </p>

                    <?php endif; ?>

                    <div class="frete-caixa">

                        <label for="cep-frete">Calcular frete</label>

                        <div class="frete-form">

                            <input
                                id="cep-frete"
                                type="text"
                                inputmode="numeric"
                                autocomplete="postal-code"
                                placeholder="00000-000"
                                maxlength="9"
                                data-mask="cep"
                            >

                            <button
                                class="btn btn-pequeno btn-contorno"
                                type="button"
                                id="btn-frete"
                            >
                                Calcular
                            </button>

                        </div>

                        <p
                            class="frete-resultado"
                            id="frete-resultado"
                            aria-live="polite"
                        ></p>

                    </div>

                    <div class="resumo-acoes">

                        <a
                            class="btn btn-bloco"
                            href="<?= e(url_site('pedido', 'checkout')) ?>"
                        >
                            Finalizar pedido
                        </a>

                    </div>

                    <?php if (!cliente_logado()): ?>

                        <p class="dica" style="margin-top: 12px;">
                            Você entra na sua conta ou cria uma na próxima etapa.
                        </p>

                    <?php endif; ?>

                </aside>

            </div>

        <?php endif; ?>

    </div>
</section>

<?php if (!empty($itens)): ?>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /* BOTÕES DE AUMENTAR E DIMINUIR */

    var form = document.getElementById('form-carrinho');
    var temporizador = null;

    document.querySelectorAll('[data-stepper]').forEach(function (stepper) {

        var input = stepper.querySelector('input[type="number"]');
        var botaoMenos = stepper.querySelector('button[data-step="-1"]');
        var botaoMais = stepper.querySelector('button[data-step="1"]');

        if (!input) return;

        function alterarQuantidade(passo) {

            var minimo = Number(input.min) || 1;
            var maximo = Number(input.max) || 999;
            var atual = Number(input.value) || minimo;

            var novaQuantidade = atual + passo;

            novaQuantidade = Math.max(minimo, Math.min(novaQuantidade, maximo));

            if (novaQuantidade === atual) return;

            input.value = novaQuantidade;

            input.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        }

        if (botaoMenos) {
            botaoMenos.addEventListener('click', function (evento) {
                evento.preventDefault();
                alterarQuantidade(-1);
            });
        }

        if (botaoMais) {
            botaoMais.addEventListener('click', function (evento) {
                evento.preventDefault();
                alterarQuantidade(1);
            });
        }

        input.addEventListener('change', function () {

            var minimo = Number(input.min) || 1;
            var maximo = Number(input.max) || 999;
            var valor = Number(input.value) || minimo;

            input.value = Math.max(minimo, Math.min(valor, maximo));

        });

    });


    /* ATUALIZA O CARRINHO AUTOMATICAMENTE */

    form.addEventListener('change', function (evento) {

        if (!evento.target.hasAttribute('data-auto-atualizar')) {
            return;
        }

        window.clearTimeout(temporizador);

        temporizador = window.setTimeout(function () {
            form.submit();
        }, 700);

    });


    /* CÁLCULO DE FRETE */

    var campoCep = document.getElementById('cep-frete');
    var botao = document.getElementById('btn-frete');
    var resultado = document.getElementById('frete-resultado');
    var linhaFrete = document.getElementById('resumo-frete');
    var linhaTotal = document.getElementById('resumo-total');
    var totalSemFrete = linhaTotal.textContent;

    function calcular() {

        var cep = campoCep.value.replace(/\D/g, '');

        if (cep.length !== 8) {
            resultado.textContent = 'Informe um CEP com 8 dígitos.';
            resultado.classList.add('erro');
            return;
        }

        botao.disabled = true;
        resultado.textContent = 'Calculando...';
        resultado.classList.remove('erro');

        fetch('<?= e(url_site('pedido', 'frete')) ?>&cep=' + cep, {
            credentials: 'same-origin'
        })

        .then(function (resposta) {
            return resposta.json();
        })

        .then(function (dados) {

            if (!dados.valido) {

                resultado.textContent = dados.mensagem || 'Não foi possível calcular o frete.';
                resultado.classList.add('erro');

                linhaFrete.textContent = 'a calcular';
                linhaTotal.textContent = totalSemFrete;

                return;
            }

            linhaFrete.textContent = dados.valor_fmt;
            linhaTotal.textContent = dados.total_fmt;

            resultado.textContent = dados.regiao + '. ' + dados.prazo_texto + '.';

        })

        .catch(function () {

            resultado.textContent = 'Não foi possível calcular o frete agora.';
            resultado.classList.add('erro');

        })

        .then(function () {
            botao.disabled = false;
        });

    }

    botao.addEventListener('click', calcular);

    campoCep.addEventListener('keydown', function (evento) {

        if (evento.key === 'Enter') {
            evento.preventDefault();
            calcular();
        }

    });

});
</script>

<?php endif; ?>
