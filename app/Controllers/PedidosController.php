<?php
class PedidosController extends Controller{
    public function index() {
        $this->loadTemplate('pedido');
    }

    public function unico($param) {
        $idProduto = end($param);
        $produtos = new Produtos();
        $info = $produtos->selectProduct($idProduto);       
        $this->loadTemplate('pedido', $info);    
    }

    public function finalizar() {
        $pedidos = new Pedidos();
        $pedidos->finalizar($_POST);      
    }
}