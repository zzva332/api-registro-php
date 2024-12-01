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

// si hay errores los muestra en pantalla en formato json
if($error){
    echo json_encode($response);
    return;
}


// se valida el usuario y password para mirar si existe  y sino mostrar mensaje
$userService = new UsuarioService();
$result = $userService->get_registro($json->email, $json->password);
if($result == null){
    array_push($response->message, 'usuarios y password invalidos');
} else {
    $response->code = 0;
    array_push($response->message, 'login es satisfactorios');
}
echo json_encode($response);