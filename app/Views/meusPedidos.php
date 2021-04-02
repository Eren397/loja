<h1 class="m-pedidos__texto-inicial">Meus pedidos</h1>
<div class="m-pedidos__area">
    <?php
  
    $pedidos = new Pedidos();
    $pedidosInfo = $pedidos->listaPedidos($_SESSION['IDUSUARIO']);
    foreach($pedidosInfo as $chave => $pedido) {           
    ?>
    <table class="m-pedidos__table">
        <tbody>
            <tr>
                <th>Comprador</th>
                <td><?php echo $pedido['COMPRADOR']; ?></td>
            </tr>
            <tr>
                <th>Produto</th>
                <td><?php echo $pedido['PRODUTO']; ?></td>
            </tr>
            <tr>
                <th>Categoria</th>
                <td><?php echo $pedido['CATEGORIA']; ?></td>
            </tr>
            <tr>
                <th>Código do pedido</th>
                <td><?php echo $pedido['CODIGO_PEDIDO']; ?></td>
            </tr>
            <tr>
                <th>Data</th>
                <td><?php echo $pedido['DATA']; ?></td>
            </tr>
            <tr>
                <th>Forma de pagamento</th>
                <td><?php echo $pedido['FORMA_PAGAMENTO']; ?></td>
            </tr>
            <tr>
                <th>Quantidade solicitada</th>
                <td><?php echo $pedido['QUANTIDADE']; ?></td>
            </tr>
            <tr>
                <th>Valor total</th>
                <td>R$ <?php echo str_replace('.', ',', $pedido['VALOR_TOTAL']) ; ?></td>
            </tr>
            <tr>
                <th>Quantidade de parcelas</th>
                <td><?php echo $pedido['QNT_PARCELAS']; ?></td>
            </tr>
            <tr>
                <th>Valor de cada parcela</th>
                <td>R$ <?php echo str_replace('.', ',', $pedido['VALOR_PARCELAS']) ; ?></td>
            </tr>
            <tr>
                <th>Revendedor</th>
                <td><?php echo $pedido['REVENDEDOR']; ?></td>
            </tr>
            <tr>
                <th>Telefone</th>
                <td>Um SMS foi enviado para o número: <?php echo $dadosModel['TELEFONE']?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td>Mais informações sobre o pedido foram enviada para o email: <?php echo $dadosModel['EMAIL']?></td>
            </tr>
        </tbody>
    </table>  
   
    <?php
   
     }
    ?>
    
</div>