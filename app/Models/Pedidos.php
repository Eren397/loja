<?php
class Pedidos {
    private $dataPedido;
    private $formaPagamento;
    private $total;
    private $parcelas;
    private $quantidade;

    public function getDataPedido() {
        return $this->dataPedido;
    }
    public function setDataPedido($dataPedido) {
        $this->dataPedido = $dataPedido;
    }

    public function getFormaPagamento() {
        return $this->formaPagamento;
    }
    public function setFormaPagamento($formaPagamento) {
        $this->formaPagamento = $formaPagamento;
    }
    public function getTotal() {
        return $this->total;
    }
    public function setTotal($total) {
        $this->total = $total;
    }
    public function getParcelas() {
        return $this->parcelas;
    }
    public function setParcelas($parcelas) {
        $this->parcelas = $parcelas;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }
    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

   

    public function finalizar($pedidoInfo) {        
        $this->setDataPedido($pedidoInfo['data_pedido']);
        $this->setFormaPagamento($pedidoInfo['forma_pagamento']);
        $this->setTotal($pedidoInfo['valor_total']);
        $this->setParcelas($pedidoInfo['parcelas']);
        $this->setQuantidade($pedidoInfo['quantidade']);

        $dataPedido = $this->getDataPedido();
        $formaPagamento = $this->getFormaPagamento();
        $parcelas = (int)$this->getParcelas();
        $total =  (float)$this->getTotal();        
        $quantidade = (int)$this->getQuantidade();
        $idUser = $pedidoInfo['id_user'];
        $idProduto = $pedidoInfo['id_produto'];   
        $totalAtual = $total * $quantidade;      
        $valorParcelas = $totalAtual / $parcelas;

        //Atribuindo desconto de acordo com a forma de pagamento
        if($formaPagamento == 'DEBITO') {
            $desconto = $totalAtual * 0.1;
            $totalAtual = $totalAtual - $desconto;    
            $valorParcelas = $totalAtual;       
        }else if($formaPagamento ==  'BOLETO') {
            $desconto = $totalAtual * 0.05;
            $totalAtual = $totalAtual - $desconto; 
            $valorParcelas = $totalAtual;             
        }
        //Atribuindo desconto de acordo com a forma de pagamento
        //verificando disponibilidade da quantidade em estoque em relação ao que foi solicitado pelo cliente
        $qntDisponivel = $this->qntDisponivel($idProduto);
        
        if($quantidade <= $qntDisponivel) {
            $conn = Connection::getConn();
            $conn->beginTransaction();
            $sql = 'INSERT INTO PEDIDOS(DATA_PEDIDO, FORMA_PAGAMENTO, VALOR_TOTAL_VENDA, PARCELAS, VALOR_PARCELAS, IDUSUARIO)
                    VALUES (:d, :fp, :vt, :p, :vp, :id);';
            $sql = $conn->prepare($sql);
            $sql->bindValue(':d', $dataPedido);
            $sql->bindValue(':fp', $formaPagamento);
            $sql->bindValue(':vt', $totalAtual);
            $sql->bindValue(':p', $parcelas);
            $sql->bindValue(':vp', $valorParcelas);
            $sql->bindValue(':id', $idUser);
    
            $exec = $sql->execute();
            $lastIdPedido = $conn->lastInsertId();
            if(!$exec) {
                die('Erro ao realizar pedido');
            }
            $sql1 = 'INSERT INTO PRODUTOS_PEDIDOS(IDPEDIDO, IDPRODUTO, QUANTIDADE) VALUES(:ip, :id, :q);';
            $sql1 = $conn->prepare($sql1);
            $sql1->bindValue(':ip', $lastIdPedido);
            $sql1->bindValue(':id', $idProduto);
            $sql1->bindValue(':q', $quantidade);
            $exec1 = $sql1->execute();
            if(!$exec1) {
                $conn->rollBack();
                die('Erro ao informar quantidade');
            }
            $conn->commit();    
            $this->controlarEstoque($idProduto,  $quantidade);
            $this->EmailSMS($idUser);
            header('Location: /moobitoy/meusPedidos');
            
        }else {
            die('Não temos produtos suficientes disponiveis');
        }      
    }

    public function EmailSMS($idUser) {
        $conn = Connection::getConn();
        $sql = 'SELECT TELEFONE, EMAIL FROM USUARIOS 
        WHERE IDUSUARIO = :id;';
        $sql =  $conn->prepare($sql);
        $sql->bindValue(':id', $idUser);
        $sql->execute();
        $userInfo = $sql->fetch(PDO::FETCH_ASSOC); 
        return $userInfo;
    }
  
    //listar todos os pedidos do cliente x

    public function listaPedidos($idUser) {
        $conn = Connection::getConn();
        $sql = "SELECT U.NOME AS 'COMPRADOR',  
                Q.NOME AS 'PRODUTO',
                C.CATEGORIA,    
                P.IDPEDIDO AS 'CODIGO_PEDIDO',
                P.DATA_PEDIDO AS 'DATA',
                P.FORMA_PAGAMENTO,
                W.QUANTIDADE,
                P.VALOR_TOTAL_VENDA AS 'VALOR_TOTAL',
                P.PARCELAS AS 'QNT_PARCELAS',
                P.VALOR_PARCELAS, 
                R.NOME_REVENDEDOR AS 'REVENDEDOR'             
            FROM PEDIDOS P
            INNER JOIN USUARIOS U             
            ON U.IDUSUARIO = :id AND P.IDUSUARIO = :id 
            INNER JOIN CATEGORIAS C
            INNER JOIN PRODUTOS Q
            ON C.IDCATEGORIA = Q.IDCATEGORIA
            INNER JOIN PRODUTOS_PEDIDOS W 
            ON W.IDPEDIDO = P.IDPEDIDO
            INNER JOIN REVENDEDORES R 
            ON Q.IDREVENDEDOR = R.IDREVENDEDOR  AND Q.IDPRODUTO = W.IDPRODUTO;";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $idUser);
        $sql->execute();
        $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $dados;       
    }

    //atualizando estoque de acordo com os pedidos solicitados

    public function controlarEstoque($idProduto, $qntSolicitada) {       
        $conn = Connection::getConn();
        $sql = 'SELECT QUANTIDADE_PRODUTO FROM ENTRADAS WHERE IDPRODUTO = :id;';
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $idProduto);
        $sql->execute();
        $qntAtual = $sql->fetch(PDO::FETCH_ASSOC);
        $qntAtual = (int)$qntAtual['QUANTIDADE_PRODUTO'];        
        $novaQuantidade = $qntAtual - $qntSolicitada;       

        $sql1 = 'UPDATE ENTRADAS SET QUANTIDADE_PRODUTO = :q
        WHERE IDPRODUTO = :id;';
        $sql1 = $conn->prepare($sql1);
        $sql1->bindValue(':q', $novaQuantidade);
        $sql1->bindValue(':id', $idProduto);
        $sql1->execute();
    }

    public function qntDisponivel($idProduto) {
        $conn = Connection::getConn();
        $sql = 'SELECT QUANTIDADE_PRODUTO FROM ENTRADAS WHERE IDPRODUTO = :id;';
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $idProduto);
        $sql->execute();
        $qntAtual = $sql->fetch(PDO::FETCH_ASSOC);
        $qntAtual = (int)$qntAtual['QUANTIDADE_PRODUTO']; 
        return $qntAtual;
    }
}