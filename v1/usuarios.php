<?php
include("../config/config.php");
$db = new dbObj();
$conexao = $db->conString();
$req = $_SERVER["REQUEST_METHOD"];

//verifica a requisição
switch($req) {
    case 'GET':
        if( !empty($_GET["id"]) ) { //se existe um id, lista apenas o usuario deste id
            $id = intval($_GET["id"]);
            getUsuarios($id);
        } else { //ou então lista todos os usuarios
            getUsuarios();
        }
    break;

    case 'POST':
        insertUsuarios();
    break;

    case 'PUT':
        updateUsuarios();
    break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}

//busca um registro no banco de dados, baseado no :id
function getUsuarios($id=0) {
    global $conexao;
    $query = "SELECT * FROM usuarios";
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

//insere um novo registro
function insertUsuarios() {
    global $conexao;
    $data = array(
        'usuario' => $_POST['usuario'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'],
        'status' => $_POST['status'],
        'foto' => $_FILES['foto']
    );
    $usuario=$data['usuario'];
    $nome=$data['nome'];
    $email=$data['email'];
    $senha=$data['senha'];
    $status=$data['status'];
    $foto=$data['foto'];

    $foto_temp = explode(".", $foto["name"]);
    $foto_renomeada = round(microtime(true)) . '.' . end($foto_temp);
    move_uploaded_file($foto["tmp_name"], "../uploads/" . $foto_renomeada);

    $query = "INSERT INTO usuarios SET usuario='$usuario', nome='$nome', email='$email', senha='$senha', status='$status', foto='../uploads/$foto_renomeada'";
    if( mysqli_query($conexao, $query) ) {
        $resposta = array (
            'status' => 1,
            'status_message' => 'Inserido'
        );
    } else {
        $resposta = array(
            'status' => 0,
            'status_message'=> 'Erro',
            'code' => 'Erro: ' . $query
        );
    }
    header('Content-Type: application/json');
    echo json_encode($resposta);
}

//atualiza um novo registro
function updateUsuarios($id) {
    global $conexao;
    $data = array(
        'usuario' => $_POST['usuario'],
        'nome' => $_POST['nome'],
        'email' => $_POST['email'],
        'senha' => $_POST['senha'],
        'status' => $_POST['status'],
        'foto' => $_FILES['foto']
    );
    $usuario=$data['usuario'];
    $nome=$data['nome'];
    $email=$data['email'];
    $senha=$data['senha'];
    $status=$data['status'];
    $foto=$data['foto'];

    $foto_temp = explode(".", $foto["name"]);
    $foto_renomeada = round(microtime(true)) . '.' . end($foto_temp);
    move_uploaded_file($foto["tmp_name"], "../uploads/" . $foto_renomeada);

    $query = "UPDATE usuarios SET usuario=$usuario, nome=$nome, email=$email, senha=$senha, status=$status, foto=$foto_renomeada WHERE id='$id'";
    if( mysqli_query($conexao, $query) ) {
        $resposta = array (
            'status' => 1,
            'status_message' => 'Atualizado'
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