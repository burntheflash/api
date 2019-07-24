<?php

include("../conexao.php");

$db = new dbObj();
$conexao = $db->conString();
$req = $_SERVER["REQUEST_METHOD"];
$rota = substr( $_SERVER['REQUEST_URI'], -8 );
$id = intval($_GET["id"]);

//verifica a requisição
switch($req) {
    case 'GET':
    if( !empty($rota) ) {
        if( $rota == 'usuarios' ) {
            getContent();
        } 
        else if ( $rota == 'usuarios' && isset($id) ) {
            getContent($id);
        }
    }
    break;
    default:
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}

//busca um registro no banco de dados, baseado no :id
function getContent($id=0) {
    global $conexao;
    global $rota;
    global $id;
    $query = "SELECT * FROM $rota ";
    if( isset($id) ) {
        $query.="WHERE id=$id LIMIT 1";
    }
    $resposta = array();
    $resultados = mysqli_query($conexao, $query);
    while( $resultado = mysqli_fetch_assoc($resultados) ) {
        $resposta[]=$resultado;
    }
    header("Content-Type: application/json");
    echo json_encode($resposta);
    // print_r($resposta);
}

?>