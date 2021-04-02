<?php
class HomeController extends Controller{
    public function index() {
        $revendedores = new Revendedores();
        $revendedor = $revendedores->findReseller($_SESSION['IDUSUARIO']);
        if($revendedor != null) {
            $_SESSION['REVENDEDOR_IDUSUARIO'] = $revendedor['IDUSUARIO'];
        }     
        $this->loadTemplate('home');       
    }
  

    public function logout() {
        $usuario = new Usuarios();
        $usuario->logout();
    }

    public function deletarProduto($params) {
        $idProduto = end($params);
        $produtos = new Produtos();
        $produtos->deletarProduto($idProduto);
    }

    public function desativar($params) {
        $idProduto = end($params);
        $produtos = new Produtos();
        $produtos->desativarProduto($idProduto);
    }

    public function ativar($params) {
        $idProduto = end($params);
        $produtos = new Produtos();
        $produtos->ativarProduto($idProduto);        
    }

    public function buscar() {         
        $produtos = new Produtos();
        $dadosPesquisa = $produtos->buscarPorProdutos($_GET);        
        $this->loadTemplate('resultadoBusca', $dadosPesquisa);
    }
}