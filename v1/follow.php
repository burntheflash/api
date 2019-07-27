<?php
include("../config/config.php");
$db = new dbObj();
$conexao = $db->conString();
$req = $_SERVER["REQUEST_METHOD"];

//verifica a requisição
switch($req) {
    case 'GET':
        getFollowingUsers();
    break;

    case 'POST':
        followUser();
    break;

    case 'PUT':
        header("HTTP/1.0 405 Method Not Allowed");
    break;

    case 'DELETE':
        deleteConexao();
    break;

    default:
        
    break;
}

//busca um registro no banco de dados, baseado no :id
function getSparks($id=0) {
    global $conexao;
    $query = "SELECT * FROM sparks";
    if($id != 0) {
        $query.=" WHERE id='$id' LIMIT 1";
    }
    $resposta = array();
    $resultados = mysqli_query($conexao, $query);
    while( $resultado = mysqli_fetch_assoc($resultados) ) {
        $resposta[]=$resultado;
    }
    header("Content-Type: application/json");
    echo json_encode($resposta);
}

//busca um registro no banco de dados, baseado no :id
function getSparksFromUser($userID) {
    global $conexao;
    $query = "SELECT * FROM sparks WHERE usuario='$userID'";
    $resposta = array();
    $resultados = mysqli_query($conexao, $query);
    while( $resultado = mysqli_fetch_assoc($resultados) ) {
        $resposta[]=$resultado;
    }
    header("Content-Type: application/json");
    echo json_encode($resposta);
}

//insere um novo registro
function insertSparks() {
    global $conexao;
    $data = array(
        'usuario' => $_POST['usuario'],
        'midia' => $_FILES['midia'],
        'data' => $_POST['data'],
        'status' => $_POST['status']
    );
    $usuario=$data['usuario'];
    $midia=$data['midia'];
    $data=$data['data'];
    $status=$data['status'];

    $midia_temp = explode(".", $midia["name"]);
    $midia_renomeada = round(microtime(true)) . '.' . end($midia_temp);
    move_uploaded_file($midia["tmp_name"], "../uploads/" . $midia_renomeada);

    $query = "INSERT INTO sparks SET usuario='$usuario', midia='../uploads/$midia_renomeada', data='$data', status='$status'";
    if( mysqli_query($conexao, $query) ) {
        $resposta = array (
            'status' => 1,
            'status_message' => 'Inserido'
        );
    } else {
        $resposta = array(
            'status' => 0,
            'status_message'=> 'Erro' 
        );
    }
    header('Content-Type: application/json');
    echo json_encode($resposta);
}

?>