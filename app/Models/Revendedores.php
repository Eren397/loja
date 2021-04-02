<?php
class Revendedores {
    private $cnpj;
    
    public function getCNPJ() {
        return $this->cnpj;
    }

    public function setCNPJ($cnpj) {
        $this->cnpj = $cnpj;
    }

    public function beReseller($dadosReseller, $userName) {
       
        $this->setCNPJ($dadosReseller['revendedor_cnpj']);
        $cnpj = addslashes(trim(strip_tags($this->getCNPJ())));
        $idUser = $dadosReseller['id_user'];

        if(empty($cnpj)) {
            die('Informe seu CNPJ');
        }else if(!preg_match('/^[0-9]{14,14}$/', $cnpj)) {
            die('CNPJ inválido. Preencha com apenas 14 digitos');
        }else {
            $conn = Connection::getConn();
            $sql = 'SELECT IDREVENDEDOR FROM REVENDEDORES WHERE CNPJ = :c ;';
            $sql = $conn->prepare($sql);
            $sql->bindValue(':c', $cnpj);
            $sql->bindValue(':id', $idUser);
            $exec = $sql->execute();
            if($exec) {
                die('Esse CNPJ já foi cadastrado ou Você já é um revendedor');
            }else {
                $conn = Connection::getConn();
                $sql =  'INSERT INTO REVENDEDORES(NOME_REVENDEDOR,CNPJ, IDUSUARIO) VALUES(:n ,:c, :id)';
                $sql = $conn->prepare($sql);
                $sql->bindValue(':n', $userName);
                $sql->bindValue(':c', $cnpj);
                $sql->bindValue(':id', $idUser);
                $res = $sql->execute();            
                if($res) {
                    header('Location: /moobitoy/home');                
                }else {
                    die('Falha ao cadastrar como revendedor. Tenve novamente');
                }
            }      
        }       
    }
    //Buscar qual usuario é revendedor

    public function findReseller($idUser) {
        $conn = Connection::getConn();
        $sql = 'SELECT R.IDUSUARIO
                FROM USUARIOS U 
                INNER JOIN REVENDEDORES R 
                ON (U.IDUSUARIO = :i) AND (R.IDUSUARIO = :d);';
        $sql = $conn->prepare($sql);
        $sql->bindValue(':i', $idUser);
        $sql->bindValue(':d', $idUser);
        $res = $sql->execute();
        if($res) {
            $reseller = $sql->fetch(PDO::FETCH_ASSOC);    
            if(!empty($reseller)) {
                return $reseller;
            }       
        }
      
    }

    //buscar id do revendedor

    public function idReseller($id) {
        $conn = Connection::getConn();
        $sql = 'SELECT R.IDREVENDEDOR FROM REVENDEDORES R 
                INNER JOIN USUARIOS U 
                ON (U.IDUSUARIO = :i) AND(R.IDUSUARIO = :d);';
        $sql = $conn->prepare($sql);
        $sql->bindValue(':i', $id);
        $sql->bindValue(':d', $id);
        $res = $sql->execute();
        if($res) {
            $idReseller = $sql->fetch(PDO::FETCH_ASSOC);
            return $idReseller;
        }

    }
}