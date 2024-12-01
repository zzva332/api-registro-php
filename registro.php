<?php
require_once __DIR__ . '/servicios/usuarioService.php';
header("Content-Type: application/json");
$response = new stdClass;
$response->code = -1;
$response->message = [];

// obtiene los datos de entrada y los convierte de json a un objeto modificable
$json_text = file_get_contents('php://input');
$json = json_decode($json_text, false);
$error = false;

// validacion de que si tenga formato json
if($json == null){
    array_push($response->message, 'Se requiere email y password');
    echo json_encode($response);
    return;
}

// validacion de email y password que esten presentes
if (empty($json->email)){
    array_push($response->message, "email es requerido");
    $error = true;
}
if (empty($json->password)){
    array_push($response->message, "password es requerido");
    $error = true;
}

// validacion de que el email no este registrado en db
$userService = new UsuarioService();
$exists = $userService->exist_email($json->email);
if($exists){
    array_push($response->message, "El email ya se encuentra registrado");
    $error = true;
}
// si hay errores los muestra en pantalla en formato json
if($error){
    echo json_encode($response);
    return;
}

// crear el nuevo registro y valida que sea exitoso
$result = $userService->insertar_registro($json);
if(!$result){
    array_push($response->message, 'Hubo un problema al realizar el registro');
} else {
    $response->code = 0;
    array_push($response->message, 'Registro de nuevo usuario satisfactorio');
}
echo json_encode($response);