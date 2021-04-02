<?php
class Core {
    public function __construct() { 
        $this->getUrl();
    }

    public function getUrl() {
        if(isset($_GET['page']) && !empty($_GET['page'])) {
            $url = $_GET['page'];
            $url = explode('/', $url);
            $controller = ucfirst($url[0].'Controller');
            array_shift($url);

            if(isset($url[0]) && !empty($url[0])) {
                $function = $url[0];
            }else {
                $function = 'index';
            }   
            
            if(count($url) > 0) {
                $params = $url;
            }
        }else {
            $controller = 'LoginController';
            $function = 'index';
        }
        

        if(!class_exists($controller)) {
            $controller = 'NotFoundController';
        }
        //validando rotas de acordo com acesso

        if(!isset($_SESSION['IDUSUARIO']) && $controller == 'HomeController') {
            header('Location: /moobitoy/login');
        }else if(!isset($_SESSION['IDUSUARIO']) && $controller == 'RevendedorController') {
            header('Location: /moobitoy/login');
        }else if(!isset($_SESSION['IDUSUARIO']) && $controller == 'ProdutoController') {
            header('Location: /moobitoy/login');
        }else if(isset($_SESSION['IDUSUARIO']) && $controller ==  'ProdutoController' && empty($_SESSION['REVENDEDOR_IDUSUARIO'])) {
            header('Location: /moobitoy/home');
        }else if(!isset($_SESSION['IDUSUARIO']) && $controller == 'PedidosController') {
            header('Location: /moobitoy/login');
        }else if(!isset($_SESSION['IDUSUARIO']) && $controller == 'MeusPedidosController') {
            header('Location: /moobitoy/login');
        }

        //verificando por parametros na url
        if(!isset($params)) {
            $params = null;
        }
        
        call_user_func_array(array(new $controller, $function), array($params));
        
    }
}