<div class="pedido">
    <div class="pedido__card">
        <span class="pedido__revendedor"><?php echo $dadosModel['REVENDEDOR']; ?></span>
        <h1><?php echo $dadosModel['NOME']; ?></h1>
        <span>R$ <?php echo  str_replace('.', ',', $dadosModel['PRECO']) ; ?></span>
        <p><?php echo $dadosModel['DESCRICAO']; ?></p>
        <span class="product__cat">Categoria: <?php echo $dadosModel['CATEGORIA']; ?></span>
        <span class="product__qnt-single">Estoque: <?php echo $dadosModel['ESTOQUE'];?></span>
    </div>
    <div>
        <form action="pedidos/finalizar" method="POST" class="form">
            <h3 class="form__title">Pagamento</h3>
            
            <select name="forma_pagamento" class="categoria__select formaPagamento">
                <option value="DEBITO" class="categoria__option">Debito</option>
                <option value="CREDITO" class="categoria__option">Credito</option>                
                <option value="BOLETO" class="categoria__option">Boleto</option>
            </select>
            <label class="label">Quantidade:</label>
            <select name="quantidade" class="categoria__select" >
            <?php
         
                for($i = 1; $i <= 20; $i++) {
            ?>
                <option value="<?php echo $i; ?>" class="categoria__option"><?php echo $i; ?></option>
            <?php
                }
            ?>
            </select>         
            <select name="parcelas" class="categoria__select parcelas">
                <?php
                
                for($i = 1; $i <= 12; $i++) {
                ?>
                    <option value="<?php echo $i; ?>" class="categoria__option"><?php echo $i; ?>x</option>
                <?php
                }
                ?>
            </select>
            <label class="label" >Valor total: R$ <?php echo str_replace('.', ',', $dadosModel['PRECO']) ; ?></label>
            <input type="hidden" name="valor_total" value="<?php echo $dadosModel['PRECO']; ?>">
            <input type="hidden" name="data_pedido" value="<?php echo date('Y-m-d'); ?>">
            <input type="hidden" name="id_user" value="<?php echo $_SESSION['IDUSUARIO']; ?>">
            <input type="hidden" name="id_produto" value="<?php echo $dadosModel['IDPRODUTO']; ?>">
            <button type="submit" class="form__button" name="submit_comprar">Finalizar</button>            
        </form>
    </div>

</div>