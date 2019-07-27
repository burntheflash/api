<?php
include("../config/config.php");
$db = new dbObj();
$conexao = $db->conString();
$req = $_SERVER["REQUEST_METHOD"];

//verifica a requisição
switch($req) {
    case 'GET':
        if( !empty($_GET["id"]) ) { //se existe um id, lista apenas o evento deste id
            $id = intval($_GET["id"]);
            getEventos($id);
        } else { //ou então lista todos os eventos
            getEventos();
        }
    break;

    case 'POST':
        insertEventos();
    break;

    case 'PUT':
        updateEventos();
    break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}

//busca um registro no banco de dados, baseado no :id
function getEventos($id=0) {
    global $conexao;
    $query = "SELECT * FROM eventos";
    if($id != 0) {
        $query.=" WHERE id='$id' LIMIT 1";
    } else if ( !empty($_GET["c_id"]) ) {
        $casaID = intval($_GET["c_id"]);
        getEventosFromCasa($casaID);
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
function getEventosFromCasa($casaID) {
    global $conexao;
    $query = "SELECT * FROM eventos WHERE casa='$casaID'";
    $resposta = array();
    $resultados = mysqli_query($conexao, $query);
    while( $resultado = mysqli_fetch_assoc($resultados) ) {
        $resposta[]=$resultado;
    }
    header("Content-Type: application/json");
    echo json_encode($resposta);
}

//insere um novo registro
function insertEventos() {
    global $conexao;
    $data = array(
        'casa' => $_POST['casa'],
        'titulo' => $_POST['titulo'],
        'local' => $_POST['local'],
        'data' => $_POST['data'],
        'horario' => $_POST['horario'],
        'descricao' => $_POST['descricao']
    );
    $casa=$data['casa'];
    $titulo=$data['titulo'];
    $local=$data['local'];
    $data=$data['data'];
    $horario=$data['horario'];
    $descricao=$data['descricao'];
    $query = "INSERT INTO eventos SET casa='$casa', titulo='$titulo', local='$local', data='$data', horario='$horario', descricao='$descricao'";
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

//atualiza um novo registro
function updateEventos($id) {
    global $conexao;
    $data = array(
        'casa' => $_POST['casa'],
        'titulo' => $_POST['titulo'],
        'local' => $_POST['local'],
        'data' => $_POST['data'],
        'horario' => $_POST['horario'],
        'descricao' => $_POST['descricao']
    );
    $casa=$data['casa'];
    $titulo=$data['titulo'];
    $local=$data['local'];
    $data=$data['data'];
    $horario=$data['horario'];
    $descricao=$data['descricao'];
    $query = "UPDATE eventos SET casa='$casa', titulo='$titulo', local='$local', data='$data', horario='$horario', descricao='$descricao' WHERE id='$id'";
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

//deleta um registro
function deleteEventos($id) {
    global $conexao;
    $query = "DELETE FROM eventos WHERE id=$id";
    $resposta = array();
    $resultados = mysqli_query($conexao, $query);
    header("Content-Type: application/json");
    echo json_encode($resposta);
}

?>