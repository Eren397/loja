<h1>Sua busca</h1>
<div class="home__products">
<div class="product__items">

<?php


$res = $dadosModel;
;
foreach($res as $chave => $valor) {
   
?>
    <div class="product__card">
         <h5 class="product__name"><?php echo $valor['NOME']; ?></h5>
         <i class="fas fa-user-tag product__icon"></i>
         <span class="product__price">R$ <?php echo str_replace('.', ',', $valor['PRECO']); ?></span>
         <p class="product__desc"><?php echo $valor['DESCRICAO']; ?></p>
         <span class="product__cat">Categoria: <?php echo ucfirst(strtolower($valor['CATEGORIA'])); ?></span>
         <span class="product__qnt">Estoque: <?php echo $valor['ESTOQUE']; ?> </span> 
         <?php
            if(empty($_SESSION['REVENDEDOR_IDUSUARIO'])) {                
        ?>     
            <?php
            if($valor['ESTOQUE'] != '0'){
                
                if($valor['ESTADO'] == '0') {
            ?>
                <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                <a href="pedidos/unico/<?php echo $valor['IDPRODUTO']; ?>" class="product__buy">Comprar</a>
            <?php
                }else if($valor['ESTADO'] == '1') {
            ?>  
                <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                <span>Anúncio pausado</span>
            <?php
                }
            }else if($valor['ESTOQUE'] == '0') {
                if($valor['ESTADO'] == '0') {
                ?>
                    <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                    <span>Esgotado</span>
                <?php
                }else if($valor['ESTADO'] == '1') {
                ?>
                    <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                    <span>Anúncio pausado</span>
                <?php
                }                
            }
            ?>             
            
        <?php
            }else if (($_SESSION['REVENDEDOR_IDUSUARIO'] == $_SESSION['IDUSUARIO']) && ($_SESSION['REVENDEDOR_IDUSUARIO'] != $valor['IDUSUARIO']) ) {
            ?>
                <?php 
                if($valor['ESTOQUE'] != '0') {
                    if($valor['ESTADO'] == '0') {
                    ?>
                    <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                    <a href="pedidos/unico/<?php echo $valor['IDPRODUTO']; ?>" class="product__buy">Comprar</a>
                    <?php
                    }else if($valor['ESTADO'] == '1') {
                    ?>
                        <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                        <span>Anúncio pausado</span>
                    <?php
                    }
                }else if($valor['ESTOQUE'] == '0') {
                ?>
                    <?php
                    if($valor['ESTADO'] == '0') {
                    ?>
                        <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                        <span>Esgotado</span>
                    <?php
                    }else if($valor['ESTADO'] == '1') {
                    ?>
                        <span class="product__name-reseller">Revendedor: <?php echo $valor['REVENDEDOR']; ?></span>
                        <span>Anúncio pausado</span>
                    <?php
                    }
                    ?>
                <?php
                }
            }else {
            ?>
                <?php
                        if($valor['ESTADO'] == '0') {
                        ?>
                            <a href="home/desativar/<?php echo $valor['IDPRODUTO']; ?>" class="product__status">Desativar</a>
                        <?php
                        }else if($valor['ESTADO'] == '1') {
                        ?>
                             <a href="home/ativar/<?php echo $valor['IDPRODUTO']; ?>" class="product__status-actived">Ativar</a>
                        <?php
                        }
                        ?>
                        <!-- verificando estado inicial do produto -->
                        
                        <a href="home/deletarProduto/<?php echo $valor['IDPRODUTO']; ?>">Deletar</a>
                        <a href="produto/editarProduto/<?php echo $valor['IDPRODUTO']; ?>" class="product__editar">Editar</a>
                        <small class="produto__meu">Meu produto</small>
                    
            <?php
            }
            ?>
            <?php
           
         ?>
    </div>

<?php
}


?>
</div>
</div>