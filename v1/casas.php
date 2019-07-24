<?php
include("../config/config.php");
$db = new dbObj();
$conexao = $db->conString();
$req = $_SERVER["REQUEST_METHOD"];

//verifica a requisição
switch($req) {
    case 'GET':
        if( !empty($_GET["id"]) ) { //se existe um id, lista apenas a casa deste id
            $id = intval($_GET["id"]);
            getCasas($id);
        } else { //ou então lista todos os casas
            getCasas();
        }
    break;

    case 'POST':
        insertCasas();
    break;

    case 'PUT':
        updateCasas();
    break;

    default:
        header("HTTP/1.0 405 Method Not Allowed");
    break;
}

//busca um registro no banco de dados, baseado no :id
function getCasas($id=0) {
    global $conexao;
    $query = "SELECT * FROM casas";
    if($id != 0) {
        $query.=" WHERE id='$id' LIMIT 1";
    }
    $resposta = array();
    $resultados = mysqli_query($conexao, $query);
    while( $resultado=mysqli_fetch_assoc($resultados) ) {
        $resposta[]=$resultado;
    }
    header("Content-Type: application/json");
    echo json_encode($resposta);
}

//insere um novo registro
function insertCasas() {
    global $conexao;
    $data = array(
        'nome' => $_POST['nome'],
        'endereco' => $_POST['endereco'],
        'telefone' => $_POST['telefone'],
        'logo' => $_FILES['logo'],
        'site' => $_POST['site'],
        'facebook' => $_POST['facebook'],
        'instagram' => $_POST['instagram']
    );
    $nome=$data['nome'];
    $endereco=$data['endereco'];
    $telefone=$data['telefone'];
    $logo=$data['logo'];
    $site=$data['site'];
    $facebook=$data['facebook'];
    $instagram=$data['instagram'];

    $logo_temp = explode(".", $logo["name"]);
    $logo_renomeada = round(microtime(true)) . '.' . end($logo_temp);
    move_uploaded_file($logo["tmp_name"], "../uploads/" . $logo_renomeada);

    $query = "INSERT INTO casas SET nome='$nome', endereco='$endereco', telefone='$telefone', logo='$logo_renomeada', site='$site', facebook='$facebook', instagram='$instagram'";
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
function updateCasas($id) {
    global $conexao;
    $data = array(
        'nome' => $_POST['nome'],
        'endereco' => $_POST['endereco'],
        'telefone' => $_POST['telefone'],
        'logo' => $_POST['logo'],
        'site' => $_POST['site'],
        'facebook' => $_POST['facebook'],
        'instagram' => $_POST['instagram']
    );
    $nome=$data['nome'];
    $endereco=$data['endereco'];
    $telefone=$data['telefone'];
    $logo=$data['logo'];
    $site=$data['site'];
    $facebook=$data['facebook'];
    $instagram=$data['instagram'];

    $logo_temp = explode(".", $logo["name"]);
    $logo_renomeada = round(microtime(true)) . '.' . end($logo_temp);
    move_uploaded_file($logo["tmp_name"], "../uploads/" . $logo_renomeada);

    $query = "UPDATE casas SET nome=$nome, endereco='$endereco', telefone='$telefone', logo='$logo_renomeada', site='$site', facebook='$facebook', instagram='$instagram' WHERE id='$id'";
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