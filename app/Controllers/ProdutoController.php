<?php
class ProdutoController extends Controller{
    public function index() {
        $this->loadTemplate('produto');
    }

    public function setProducts() {
        $produtos = new Produtos();
        $produtos->setProducts($_POST);
    }

    public function editarProduto($params) {
        $idProduto = end($params);
        $produtos = new Produtos();
        $produto = $produtos->selectProduct($idProduto);
        $produtos->editarProduto($idProduto, $_POST);
        $this->loadTemplate('produto', $produto);    
       
    }

 
        
}