<div class="produto flex-center">
    <form action="<?php if(isset($dadosModel['IDPRODUTO'])){echo 'produto/editarProduto/'.$dadosModel['IDPRODUTO'];}else{echo 'produto/setProducts';}?>" method="POST" class="form">
        <h3 class="form__title"><?php if(isset($dadosModel['IDPRODUTO'])){echo 'Editar';}else{echo 'Publicar';}?></h3>
        <input type="text" value="<?php if(isset($dadosModel['NOME'])){echo $dadosModel['NOME'];} ?>"  name="nome_produto" placeholder="Nome ..." class="form__input">
        <input type="text" value="<?php if(isset($dadosModel['PRECO'])){echo $dadosModel['PRECO'];} ?>" name="preco_produto" placeholder="Preço: 14,15" class="form__input">
        <label class="label">Categoria:</label>
        <select name="categoria" class="categoria__select">
            <option value="<?php if(isset($dadosModel['IDCATEGORIA']) && $dadosModel['IDCATEGORIA'] == '1'){echo $dadosModel['IDCATEGORIA'];}else{echo '1';} ?>" class="categoria__option">Bonecos</option>
            <option value="<?php if(isset($dadosModel['IDCATEGORIA']) && $dadosModel['IDCATEGORIA'] == '2'){echo $dadosModel['IDCATEGORIA'];}else{echo '2';} ?>" class="categoria__option">Bonecas</option>
            <option value="<?php if(isset($dadosModel['IDCATEGORIA']) && $dadosModel['IDCATEGORIA'] == '3'){echo $dadosModel['IDCATEGORIA'];}else{echo '3';} ?>" class="categoria__option">Carrinhos</option>
        </select>   
        <label class="label">Quantidade:</label>
        <select name="quantidade" class="categoria__select">
            <?php
            for($i = 1; $i <= 100; $i++) {
            ?>
                <option class="categoria__option" value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php
            }
            ?>
        </select>
        <textarea name="descricao_produto" placeholder="Descrição ..." class="form__input"><?php if(isset($dadosModel['DESCRICAO'])){echo $dadosModel['DESCRICAO'];}?></textarea>
        <input type="hidden" name="id_user" value="<?php echo $_SESSION['IDUSUARIO'];?>">
        <button type="submit" class="form__button" name="<?php if(isset($dadosModel['IDPRODUTO'])){echo 'submit_edit';}else{echo 'submit_produto';} ?>"><?php if(isset($dadosModel['IDPRODUTO'])){echo 'Editar';}else{echo 'Publicar';}?></button>
    </form>
</div>

