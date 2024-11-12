<?php
include '../2Capa_Negocio/usuario.php';
include 'JsonResponse.php';

//SUCCESS
define("HTTP_OK",200);
define("HTTP_CREATED", 201);
define("HTTP_ACCEPTED", 202);

//FAILED
define("BAD_REQUEST", 400);
define("INTERNAL_SERVER_ERROR", 500);
define("UNAUTHORIZED", 401);

// Variables reutilizables
$datos = json_decode(file_get_contents('php://input'), true);
$usuario = new Usuario();
$response = new JsonResponse();

// Método GET para obtener a un usuario en específico
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {  //obtener el parametro id
        $data = $usuario->consultarUsuario($_GET['id']);
        $response->success("Consulta exitosa", $data, HTTP_OK);
    } else {
        $response->failed("ID de usuario no proporcionado.", BAD_REQUEST);
    }
}

//Método POST para registrar a un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($datos['nombre_completo']) && !empty($datos['email']) && !empty($datos['username']) && !empty($datos['password']) && !empty($datos['pais_origen'])){
        
        $correoUsuario = $datos['email'];//Identificar si el dominio del correo es correcto
        $contraseñaUsuario =  $datos['password'];//Dato a codificar

        if(verificarCorreo($correoUsuario) == 'Verificado'){
            if(verificarContraseña($contraseñaUsuario) == 'Verificado'){
                //Método POST para registrar a un nuevo usuario
                $usuario->setNombreCompleto($datos['nombre_completo']);
                $usuario->setEmail($datos['email']);
                $usuario->setUsername($datos['username']);
                $usuario->setPaisOrigen($datos['pais_origen']);

                //Proceso de encriptación de contraseña
                $clave_encriptacion = "CalaComandanteCronkiLoopsy121103"; //Tamaño 32 bytes para AES-256

                // Cifrado
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
                $contraseñaCifrada = openssl_encrypt($contraseñaUsuario, 'aes-256-cbc', $clave_encriptacion, 0, $iv); 
                $contraseñaCifrada = base64_encode($contraseñaCifrada . '::' . $iv);

                //Registramos la contraseña ya cifrada como parámetro del objeto que crearemos
                $usuario->setPassword($contraseñaCifrada);

                $estadoOperacion = $usuario->registrarUsuario();

                header('Content-Type: application/json');
                if($estadoOperacion == 1){
                    $response->success("Usuario registrado de manera exitosa", null, HTTP_CREATED);
                }else{
                    $response->failed("Ups! Algo ha salido mal", INTERNAL_SERVER_ERROR);
                }
            }else{
                $response->failed(verificarContraseña($contraseñaUsuario), BAD_REQUEST);
            }    
        }
        else{
            $response->failed(verificarCorreo($correoUsuario), BAD_REQUEST);
        }

    }else{
        $response->failed("Rellena todos los datos solicitados", BAD_REQUEST);
    }
}

//Método PUT para actualizar un registro de usuario específico
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if(isset($datos['id'])){
        $usuario->setNombreCompleto($datos['nombre_completo']);
        $usuario->setEmail($datos['email']);
        $usuario->setUsername($datos['username']);
        $usuario->setPassword($datos['password']);
        $usuario->setPaisOrigen($datos['pais_origen']);

        $estadoOperacion = $usuario->actualizarUsuario($datos['id']);
        if($estadoOperacion == 1){
            $response->success("Usuario actualizado de manera exitosa", null, HTTP_OK);
        }else{
            $response->failed("Ups! Algo ha salido mal", INTERNAL_SERVER_ERROR);
        }
    }else{
        $response->failed("ID de usuario no proporcionado.", BAD_REQUEST);
    }
}

//Método DELETE para eliminar a un usuario en específico
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if(isset($datos['id'])){
        $estadoOperacion = $usuario ->eliminarUsuario($datos['id']);
        if($estadoOperacion >= 1){
            $response->success("Usuario eliminado de manera exitosa", null, HTTP_ACCEPTED);
        }else{
            $response->failed("Ups! Algo ha salido mal", INTERNAL_SERVER_ERROR);
        }
    }else{
        $response->failed("ID de usuario no proporcionado.", BAD_REQUEST);
    }
}

function verificarContraseña($contraseña) {
    // Verificar que la contraseña tenga más de 8 caracteres
    if (strlen($contraseña) <= 8) {
        return "La contraseña debe tener más de 8 caracteres.";
    }

    // Verificar que contenga al menos una mayúscula
    if (!preg_match('/[A-Z]/', $contraseña)) {
        return "La contraseña debe contener al menos una letra mayúscula.";
    }

    // Verificar que contenga al menos un carácter especial
    if (!preg_match('/[\W_]/', $contraseña)) {
        return "La contraseña debe contener al menos un carácter especial.";
    }

    // Verificar que contenga al menos un número
    if (!preg_match('/[0-9]/', $contraseña)) {
        return "La contraseña debe contener al menos un número.";
    }

    return "Verificado";
}

function verificarCorreo($correoUsuario) {

    $usuario = new usuario();

    // Verificar que solo hay una arroba (@) en todo el string
    if (substr_count($correoUsuario, '@') !== 1) {
        return "El correo debe contener solo una arroba (@).";
    }

    //Verificar si el correo no es repetido
    if(!empty($usuario->verificarCorreo($correoUsuario))){
        return "El correo ya se encuentra registrado";
    }

    return "Verificado";
}
?>
