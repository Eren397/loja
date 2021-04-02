<div class="flex-center">
    <form action="cadastro/insertUser" method="POST" class="form">
        <h3 class="form__title">Cadastro</h3>
        <input type="text" name="nome_cadastro" placeholder="Nome ..." class="form__input">
        <input type="text" name="usuario_cadastro" placeholder="Usuário ..." class="form__input">
        <input type="text" name="email_cadastro" placeholder="E-mail ..." class="form__input">
        <input type="text" name="telefone_cadastro" placeholder="Telefone ..." class="form__input">
        <input type="password" name="senha_cadastro" placeholder="Senha ..." class="form__input">
        <input type="password" name="senha_cadastro2" placeholder="Repita sua senha ..." class="form__input">
        <button type="submit" class="form__button" name="submit_cadastro">Cadastrar</button>
        <a href="login" class="form__link-cadastro">Entrar</a>
    </form>
</div>  