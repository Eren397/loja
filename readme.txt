Projeto Moobitoy:




Iniciar projeto:

Os scripts sql estão contidos em um arquivo chamado “banco.sql”. Eu decidi criar categorias para os produtos que seriam cadastrados, então para que tudo haja de acordo com o que foi pensado, eu também deixei os inserts da tabela categoria. Esses dados devem ser cadastrados no banco antes de começar a testar o sistema.

Após se cadastrar e acessar sua conta, será exibido o menu e a página home. Na opção de menu temos a página “torne-se um revendedor” ao clicar nela você será redirecionado para uma página com um formulário pedindo o cnpj e a partir daí será liberada a opção de “anunciar produtos”.

Todos os produtos serão listados e os dados como: quantidade, preço, nome do revendedor serão mostrados. Caso estoque chegue a 0 a opção de compra para o produto será cancelada. 

Testar o sistema utilizando duas ou mais contas, tanto como um usuário que é revendedor como um que não seja revendedor e ir comparando os resultados.


O que temos nesse sistema:


- URL amigável.
- Padrão MVC.
- Formulário de cadastro e login.
- Validação dos dados a serem cadastrados
- Método que verifica se o usuário já foi cadastrado, se seu usuário ou e-mail ou senha estão corretos.
- Opção de tornar-se um revendedor
- Cadastro dos produtos (após tornar-se revendedor).
- Listagem dos produtos cadastrados.
- Revendedor não pode comprar o próprio produto.
- Ferramenta de busca.
- Edição dos produtos (caso você seja o revendedor do mesmo).
- Recurso de inativação do produto.
- Usuário não poderá comprar além do que está disponível em estoque.
- Estoque decrementado após realizado o pedido.
- Cartão de crédito: Valor integral da venda, deve ser registrado o valor das parcelas;
-Débito: Valor da venda deve ter um desconto de 10%;
-Boleto bancário: Valor da venda deve ter um desconto de 5%.
- Dados do pedido são listados.
- Método que valida se o revendedor já é “revendedor”, impedindo o mesmo de se tornar revendedor duas vezes.
- Página que lista todos os pedidos realizados por determinado cliente.
- Rotas do sistema estão validadas
Exemplo: O acesso a página home só será permitido que a “session” tenha tido inicio.