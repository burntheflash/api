<?php 

Class dbObj{
    var $servidor = "burntheflash.com";
    var $usuario = "danos547_danos54";
    var $senha = "!@12qw21";
    var $banco = "danos547_sparks";
    var $conn;

    function conString() {
        $con = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->banco) or die("Erro de conexão: " . mysqli_connect_error());
        if(mysqli_connect_errno()) {
            print_f("Erro: ", mysqli_connect_error());
            exit();
        } else {
            $this->conn = $con;
        }
        return $this->conn;
    }
}

?>