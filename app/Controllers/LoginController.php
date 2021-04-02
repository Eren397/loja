<?php
class LoginController extends Controller{
    public function index() {
        $this->loadTemplate('login');
    }

    public function login() {
        $usuario = new Usuarios();
        $usuario->login($_POST);
    }
}