<div class="revendedor flex-center">
    <form action="revendedor/beReseller" method="POST" class="form">
        <h3 class="form__title"> Revendedor</h3>
        <input type="text" name="revendedor_cnpj" placeholder="CNPJ..." class="form__input">
        <input type="hidden" name="id_user" value="<?php echo $_SESSION['IDUSUARIO'];?>">
        <button type="submit" class="form__button" name="submit_revendedor">Ser Revendedor</button>
    </form>
</div>