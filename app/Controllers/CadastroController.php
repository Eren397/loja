<?php
class CadastroController extends Controller{
    public function index() {
        $this->loadTemplate('cadastro');
    }

    public function insertUser() {
        $usuario = new Usuarios();
        $usuario->insertUser($_POST);
    }
}