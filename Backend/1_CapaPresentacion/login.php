<?php
include '../2_CapaNegocio/usuario.php';
include 'JsonResponse.php';

//SUCCESS
define("HTTP_OK",200);
//FAILED
define("BAD_REQUEST", 400);
define("INTERNAL_SERVER_ERROR", 500);
define("UNAUTHORIZED", 401);

// Variables reutilizables
$datos = json_decode(file_get_contents('php://input'), true);
$usuario = new usuario();
$response = new JsonResponse();

//Método POST para verificar las credenciales, si identifica que no estan pasando todos los 
//parámetros del registro, entonces sabe que lo quese busca es verificar credenciales
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(!empty($datos['email']) && !empty($datos['password'])){
        $usuario->setEmail($datos['email']);
        $usuario->setPassword($datos['password']);

        $correoUsuario = $datos['email'];
        if(substr($correoUsuario, -9) != '@uacam.mx'){
            $response->failed("Recuerda que solo se aceptan correos con dominio: @uacam.mx", BAD_REQUEST);
        }else{
            $usuarioData = $usuario->iniciarSesion($datos['email'], $datos['password']);

            if(!empty($usuarioData)){
                if($usuarioData == 1){
                    $response->failed("Contraseña incorrecta, verifica tus credenciales", UNAUTHORIZED);
                }else if ($usuarioData == 2){
                    $response->failed("El correo proporcionado no ha sido registrado", UNAUTHORIZED);
                }else{
                    $response->success("Bienvenido!", $usuarioData, HTTP_OK);
                } 
            }
        }
    }else{
        $response->failed("Rellena los campos para poder iniciar sesión", BAD_REQUEST);    
    }
}
?>