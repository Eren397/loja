<?php
class RevendedorController extends Controller{
    public function index() {
        $this->loadTemplate('revendedor');
    }   

    public function beReseller() {
        $revendedor = new Revendedores();
        $revendedor->beReseller($_POST, $_SESSION['NOME']);
    }
}