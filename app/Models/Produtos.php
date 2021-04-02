<?php
require_once('Revendedores.php');

class Produtos {
    private $nomeProduto;
    private $precoProduto;    
    private $descricaoProduto;

    public function getNomeProduto() {
        return $this->nomeProduto;
    }
    public function setNomeProduto($nomeProduto) {
        $this->nomeProduto = $nomeProduto;
    }

    public function getPrecoProduto() {
        return $this->precoProduto;
    }
    
    public function setPrecoProduto($precoProduto) {
        $this->precoProduto = $precoProduto;
    }

    public function getDescricaoProduto() {
        return  $this->descricaoProduto;
    }
    public function setDescricaoProduto($descricaoProduto) {
        $this->descricaoProduto = $descricaoProduto;
    }

    public function setProducts($produtosInfo) {      
        $this->setNomeProduto($produtosInfo['nome_produto']);
        $this->setPrecoProduto($produtosInfo['preco_produto']);
        $this->setDescricaoProduto($produtosInfo['descricao_produto']);

        $nome = addslashes(trim(strip_tags(ucwords($this->getNomeProduto()))));
        $preco = addslashes(trim(strip_tags($this->getPrecoProduto())));
        $preco = str_replace(',', '.', $preco);
        $desc = addslashes(trim(strip_tags(ucfirst($this->getDescricaoProduto()))));
        $categoria = $produtosInfo['categoria'];
        $idUser = $produtosInfo['id_user'];

        if(empty($nome) || empty($preco) || empty($desc)){
            echo 'Preecha todos os campos do produto';
        }else if(!preg_match('/^[a-zA-ZÁ-Úá-ú0-9]*/', $nome)) {
            echo 'Nome Inválido';
        }else {
            $revendedores = new Revendedores();
            $idRevendedor = $revendedores->idReseller($idUser);
            $idRevendedor = $idRevendedor['IDREVENDEDOR'];

            $conn = Connection::getConn();
            $conn->beginTransaction();
            $sql = 'INSERT INTO PRODUTOS(NOME, PRECO, DESCRICAO, IDREVENDEDOR, IDCATEGORIA)
             VALUES(:n,:p,:d,:ir,:ic);';
            $sql = $conn->prepare($sql);
            $sql->bindValue(':n', $nome);
            $sql->bindValue(':p', $preco);
            $sql->bindValue(':d', $desc);
            $sql->bindValue(':ir', $idRevendedor);
            $sql->bindValue(':ic', $categoria);            
            $res = $sql->execute();
            $lastIdProduct = $conn->lastInsertId();
            if(!$res) {
                die('Erro ao cadastrar produtos');
            }  
             
            $estadoInicial = '0';
            $sql1 = 'INSERT INTO STATUS_PRODUTOS(ESTADO, IDPRODUTO) VALUES(:es, :id);';
            $sql1 = $conn->prepare($sql1);
            $sql1->bindValue(':es', $estadoInicial);
            $sql1->bindValue(':id', $lastIdProduct);
            $exec = $sql1->execute();
            if(!$exec) {
                $conn->rollBack();
                die('Erro ao definir estado do produto');
            }
            
            
            $sql2 = 'INSERT INTO ENTRADAS(IDPRODUTO, QUANTIDADE_PRODUTO) VALUES(:id, :q);';
            $sql2 = $conn->prepare($sql2);
            $sql2->bindValue(':id', $lastIdProduct);
            $sql2->bindValue(':q', $produtosInfo['quantidade']);
            $exe = $sql2->execute();
            if(!$exe) {
                $conn->rollBack();
                die('Erro ao cadastrar quantidade');
            }
            $conn->commit();
            header('Location: /moobitoy/home');         
        }
    }
    //listar todos os produtos

    public function selectProducts() {
        $conn = Connection::getConn();
        $sql = "SELECT  P.IDREVENDEDOR,
                R.IDUSUARIO,
                U.NOME AS 'REVENDEDOR',
                P.IDPRODUTO,
                P.NOME,
                P.PRECO,
                P.DESCRICAO,
                C.CATEGORIA,
                E.QUANTIDADE_PRODUTO AS 'ESTOQUE',
                S.ESTADO
                FROM PRODUTOS P 
                INNER JOIN REVENDEDORES R 
                ON P.IDREVENDEDOR = R.IDREVENDEDOR
                INNER JOIN USUARIOS U 
                ON U.IDUSUARIO = R.IDUSUARIO
                INNER JOIN CATEGORIAS C 
                ON C.IDCATEGORIA = P.IDCATEGORIA
                INNER JOIN ENTRADAS E 
                ON E.IDPRODUTO = P.IDPRODUTO
                INNER JOIN STATUS_PRODUTOS S 
                ON S.IDPRODUTO = P.IDPRODUTO;";
        $sql = $conn->prepare($sql);
        $sql->execute();
        $products = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }

    //Buscar um produto especifico
    public function selectProduct($idProduto) {
        $conn = Connection::getConn();
        $sql = "SELECT
                P.IDPRODUTO, 
                P.IDREVENDEDOR,
                R.IDUSUARIO,
                U.NOME AS 'REVENDEDOR',
                P.NOME,
                P.PRECO,
                P.DESCRICAO,
                C.CATEGORIA,
                C.IDCATEGORIA,
                E.QUANTIDADE_PRODUTO AS 'ESTOQUE'
                FROM PRODUTOS P
                INNER JOIN REVENDEDORES R 
                ON R.IDREVENDEDOR = P.IDREVENDEDOR AND IDPRODUTO = :id
                INNER JOIN USUARIOS U 
                ON U.IDUSUARIO = R.IDUSUARIO
                INNER JOIN CATEGORIAS C 
                ON C.IDCATEGORIA = P.IDCATEGORIA
                INNER JOIN ENTRADAS E 
                ON E.IDPRODUTO = P.IDPRODUTO;";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $idProduto); 
        $sql->execute();
        $info = $sql->fetch(PDO::FETCH_ASSOC);
        return $info;    
    }

    //Editar um produto

    function editarProduto($idProduto, $novosDados) {             
        if(isset($_POST['submit_edit'])) {
            $conn = Connection::getConn();   
            $conn->beginTransaction();
            $sql = 'UPDATE PRODUTOS SET NOME = :n,
                    PRECO = :p,
                    DESCRICAO = :d,
                    IDCATEGORIA = :i
                    WHERE IDPRODUTO = :id;';
            $sql = $conn->prepare($sql);
            $sql->bindValue(':n', $novosDados['nome_produto']);
            $sql->bindValue(':p', $novosDados['preco_produto']);
            $sql->bindValue(':d', $novosDados['descricao_produto']);
            $sql->bindValue(':i', $novosDados['categoria']);
            $sql->bindValue(':id', $idProduto);
            $exec = $sql->execute();
            if(!$exec) {
                die('Erro ao atualizar produtos');
            }
            $sql1 = 'UPDATE ENTRADAS SET QUANTIDADE_PRODUTO = :q 
                            WHERE IDPRODUTO = :id;';
            $sql1 = $conn->prepare($sql1);
            $sql1->bindValue(':q', $novosDados['quantidade']);
            $sql1->bindValue(':id', $idProduto);
            $exec1 = $sql1->execute();
            if(!$exec1) {
                $conn->rollBack();
                die('Erro ao atualizar a quantidade de produtos');
            }
            $conn->commit();
            header('Location: /moobitoy/home');            
        }       
    }

    //Deletar produto

    public function deletarProduto($idProduto) {
        $conn = Connection::getConn();
        $sql = 'DELETE FROM PRODUTOS WHERE IDPRODUTO = :id;';
        $sql = $conn->prepare($sql);
        $sql->bindValue(':id', $idProduto);
        $sql->execute();
        header('Location: /moobitoy/home');
    }

    

    public function desativarProduto($idProduto) {
        $conn = Connection::getConn();
        $sql = 'UPDATE STATUS_PRODUTOS SET ESTADO = :es WHERE IDPRODUTO = :id;';
        $sql = $conn->prepare($sql);
        $novoStatus = '1';
        $sql->bindValue(':es', $novoStatus);
        $sql->bindValue(':id', $idProduto);
        $sql->execute();   
        header('Location: /moobitoy/home');
    }

    public function  ativarProduto($idProduto) {
        $conn = Connection::getConn();
        $sql = 'UPDATE STATUS_PRODUTOS SET ESTADO = :es WHERE IDPRODUTO = :id;';
        $sql = $conn->prepare($sql);
        $novoStatus = '0';
        $sql->bindValue(':es', $novoStatus);
        $sql->bindValue(':id', $idProduto);
        $sql->execute();   
        header('Location: /moobitoy/home');
    }

    public function buscarPorProdutos($valorInput) {
        $conn = Connection::getConn();
        $sql = "SELECT  P.IDREVENDEDOR,
                    R.IDUSUARIO,
                    U.NOME AS 'REVENDEDOR',
                    P.IDPRODUTO,
                    P.NOME,
                    P.PRECO,
                    P.DESCRICAO,
                    C.CATEGORIA,
                    E.QUANTIDADE_PRODUTO AS 'ESTOQUE',
                    S.ESTADO
                    FROM PRODUTOS P 
                    INNER JOIN REVENDEDORES R 
                    ON P.IDREVENDEDOR = R.IDREVENDEDOR
                    INNER JOIN USUARIOS U 
                    ON U.IDUSUARIO = R.IDUSUARIO
                    INNER JOIN CATEGORIAS C 
                    ON C.IDCATEGORIA = P.IDCATEGORIA
                    INNER JOIN ENTRADAS E 
                    ON E.IDPRODUTO = P.IDPRODUTO
                    INNER JOIN STATUS_PRODUTOS S 
                    ON S.IDPRODUTO = P.IDPRODUTO
                    WHERE P.NOME LIKE :p;";
        $sql = $conn->prepare($sql);
        $sql->bindValue(':p','%'.$valorInput['pesquisa'].'%');
        $sql->execute();
        $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
        return $dados;       
    }
   
}