<?php
require_once('Revendedores.php');

class Usuarios {
    //attributes
    private $nomeCadastro;
    private $usuarioCadastro;
    private $emailCadastro;
    private $telefoneCadastro;
    private $senhaCadastro;
    private $confirmarSenhaCadastro;

    //atributos login
    private $usuarioLogin;
    private $senhaLogin;

    //getters and setters
    public function getNomeCadastro() {
        return $this->nomeCadastro;
    }
    public function setNomeCadastro($nome) {
        $this->nomeCadastro = $nome;
    }

    public function getUsuarioCadastro() {
        return $this->usuarioCadastro;
    }
    public function setUsuarioCadastro($usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function getEmailCadastro() {
        return $this->emailCadastro;
    }
    public function setEmailCadastro($emailCadastro){
        $this->emailCadastro = $emailCadastro;
    }

    public function getTelefoneCadastro() {
        return $this->telefoneCadastro;
    }

    public function setTelefoneCadastro($telefoneCadastro) {
        $this->telefoneCadastro = $telefoneCadastro;
    }

    public function getSenhaCadastro() {
        return $this->senhaCadastro;
    }
    public function setSenhaCadastro($senhaCadastro) {
        $this->senhaCadastro = $senhaCadastro;
    }

    public function getConfirmarSenhaCadastro() {
        return $this->confirmarSenhaCadastro;
    }
    public function setConfirmarSenhaCadastro($confirmarSenhaCadastro) {
        $this->confirmarSenhaCadastro = $confirmarSenhaCadastro;
    }

    public function getUsuarioLogin() {
        return $this->usuarioLogin;
    }
    public function setUsuarioLogin($usuarioLogin) {
        $this->usuarioLogin = $usuarioLogin;
    }

    public function getSenhaLogin() {
        return $this->senhaLogin;
    }

    public function setSenhaLogin($senhaLogin) {
        $this->senhaLogin = $senhaLogin;
    }

    //inserção e verificação

    public function insertUser($dadosNewUser) {
        //verificando se o usuário realizou a requisição

        if(isset($_POST['submit_cadastro'])) {
             //definindo os valores
            $this->setNomeCadastro($dadosNewUser['nome_cadastro']);
            $this->setUsuarioCadastro($dadosNewUser['usuario_cadastro']);
            $this->setEmailCadastro($dadosNewUser['email_cadastro']);
            $this->setSenhaCadastro($dadosNewUser['senha_cadastro']);
            $this->setConfirmarSenhaCadastro($dadosNewUser['senha_cadastro2']);
            $this->setTelefoneCadastro($dadosNewUser['telefone_cadastro']);

            //filtrando os valores
            $nomeCadastro = addslashes(ucfirst(trim(strip_tags($this->getNomeCadastro())))); 
            $usuarioCadastro = addslashes(trim(strip_tags($this->getUsuarioCadastro()))); 
            $emailCadastro = addslashes(trim(strip_tags($this->getEmailCadastro())));
            $telefoneCadastro = addslashes(trim(strip_tags($this->getTelefoneCadastro()))) ;
            $senha = addslashes(strip_tags($this->getSenhaCadastro()));
            $confirmarSenha = addslashes(strip_tags($this->getConfirmarSenhaCadastro()));
            
            if(empty($nomeCadastro) || empty($usuarioCadastro) || empty($emailCadastro) || empty($senha) || empty($confirmarSenha || empty($telefoneCadastro))) {
                die('Preencha todos os campos');

            }else if(!preg_match('/^[0-9]*$/', $telefoneCadastro)) {
                die('Número de telefone inválido');
            }else if(!preg_match('/^[\w]*$/', $usuarioCadastro)){
                die('Usuário inválido');
            }else if(!filter_var($emailCadastro, FILTER_VALIDATE_EMAIL)){
                die('E-mail inválido');
            }else if(!preg_match('/^[a-zA-Zá-úÁ-Ú0-9]*$/', $senha)) {
                die('Senha inválida. Apenas caracteres alfanumérico são permitidos');
            }else if($senha != $confirmarSenha) {
                die('As senhas devem ser iguais');
            }else { 
                $conn = Connection::getConn();
                $sql = 'SELECT IDUSUARIO FROM USUARIOS WHERE USUARIO = :u OR EMAIL = :e';
                $sql = $conn->prepare($sql);
                $sql->bindValue(':u', $usuarioCadastro);
                $sql->bindValue(':e', $emailCadastro);
                $sql->execute();
                if($sql->rowCount() > 0) {
                    die('Nome de usuário ou endereço de email já foram cadastrados. Tente novamente.');
                }else {
                        $conn = Connection::getConn();
                        $sql = 'INSERT INTO USUARIOS(NOME, USUARIO, EMAIL, TELEFONE, SENHA) VALUES (:n, :u, :e, :t, :s);';
                        $sql = $conn->prepare($sql);
                        $sql->bindValue(':n', $nomeCadastro);
                        $sql->bindValue(':u', $usuarioCadastro);
                        $sql->bindValue(':e', $emailCadastro);
                        $sql->bindValue(':t', $telefoneCadastro);
                        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                        $sql->bindValue(':s', $senhaHash);
                        $res = $sql->execute();
                    if($res) {
                       header('Location: /moobitoy/login?=cadastrado');
                    }else {
                        die('Erro ao cadastrar');
                    }
                }         
            }
        }else {
            header('Location: /moobitoy/cadastro');
        }
    } 

    public function login($dadosUser) {
     
        if(isset($_POST['submit_login'])) {
            $this->setUsuarioLogin($dadosUser['usuario_login']);
            $this->setSenhaLogin($dadosUser['senha_login']);

            $usuarioLogin = addslashes(trim($this->getUsuarioLogin()));
            $senhaLogin = addslashes($this->getSenhaLogin());       

            if(empty($usuarioLogin) || empty($senhaLogin)) {
                die('Preencha todos os campos');
                
            }else {
                $conn = Connection::getConn();
                $sql = 'SELECT * FROM USUARIOS WHERE USUARIO = :u OR EMAIL = :e;';
                $sql = $conn->prepare($sql);
                $sql->bindValue(':u', $usuarioLogin);
                $sql->bindValue(':e', $usuarioLogin);
                $sql->execute();
                
                if($sql->rowCount() > 0) {                                     
                    $userInfo = $sql->fetch();
                    $passwordCheck = password_verify($senhaLogin, $userInfo['SENHA']);

                    if($passwordCheck) {                        
                        session_start();                        
                        $_SESSION['IDUSUARIO'] = $userInfo['IDUSUARIO'];
                        $_SESSION['NOME'] = $userInfo['NOME'];
                        $_SESSION['REVENDEDOR_IDUSUARIO'] = array();
                        header('Location: /moobitoy/home');
                    }else {
                        die('Senha incorreta');
                    }                   
                }else {
                    die('Usuário não cadastrado');
                }
                
            }
        }else {
            header('Location: /moobitoy/login/');
        }
        
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /moobitoy/login');
    }
}
