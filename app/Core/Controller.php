<?php
class Controller {
    public $dados;

    public function __construct() {
        $this->dados = array();
    }

    public function loadTemplate($name, $dadosModel = array()) {
        $this->dados = $dadosModel;
        require_once("app/layout/main.php");
    }

    public function renderView($name, $dadosModel = array()) {
        require_once("app/Views/$name.php");
    }
}