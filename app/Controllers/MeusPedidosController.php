<?php
class MeusPedidosController extends Controller {
    public function index() {
        
        $pedidos = new Pedidos();
        $userInfo = $pedidos->EmailSMS($_SESSION['IDUSUARIO']);
        $this->loadTemplate('meusPedidos', $userInfo); 
        
    }

}