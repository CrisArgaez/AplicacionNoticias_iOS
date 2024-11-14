<?php
include '../2_CapaNegocio/noticia.php';
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
$noticia = new Noticia();
$response = new JsonResponse();

// Método GET para obtener a un noticia en específico
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {  //obtener el parametro id
        $data = $noticia->consultarNoticia($_GET['id']);
        $response->success("Consulta exitosa", $data, HTTP_OK);
    } else {
        $response->failed("ID de noticia no proporcionado.", BAD_REQUEST);
    }
}

//Método POST para registrar a un nuevo noticia
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($datos['titulo']) && !empty($datos['autor']) && !empty($datos['fecha_publicacion']) && !empty($datos['pais']) && !empty($datos['descripcion']) && !empty($datos['categoría']) && !empty($datos['ciudad']) && !empty($datos['imagen_principal'])) {
        
        // Validaciones específicas pueden ser agregadas aquí si es necesario
        
        // Crear una instancia de la clase Noticia y configurar sus propiedades
        $noticia = new Noticia();
        $noticia->setTitulo($datos['titulo']);
        $noticia->setAutor($datos['autor']);
        $noticia->setFechaPublicacion($datos['fecha_publicacion']);
        $noticia->setPais($datos['pais']);
        $noticia->setDescripcion($datos['descripcion']);
        $noticia->setCategoría($datos['categoría']);
        $noticia->setCiudad($datos['ciudad']);
        $noticia->setImagenPrincipal($datos['imagen_principal']);

        // Establecer valores opcionales de likes y dislikes
        $noticia->setCantidadLikes($datos['cantidad_likes'] ?? 0);
        $noticia->setCantidadDislikes($datos['cantidad_dislikes'] ?? 0);
        
        // Utilizar el método crearNoticia para registrar la nueva noticia
        $estadoOperacion = $noticia->crearNoticia();

        if ($estadoOperacion == 1) {
            $response->success("Noticia registrada de manera exitosa", null, HTTP_CREATED);
        } else {
            $response->failed("Ups! Algo ha salido mal", INTERNAL_SERVER_ERROR);
        }

    } else {
        $response->failed("Rellena todos los datos solicitados", BAD_REQUEST);
    }
}

//Método PUT para actualizar un registro de noticia específico
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if(isset($datos['id'])){
        $noticia->setNombreCompleto($datos['nombre_completo']);
        $noticia->setEmail($datos['email']);
        $noticia->setUsername($datos['username']);
        $noticia->setPassword($datos['password']);
        $noticia->setPaisOrigen($datos['pais_origen']);

        $estadoOperacion = $noticia->actualizarnoticia($datos['id']);
        if($estadoOperacion == 1){
            $response->success("noticia actualizado de manera exitosa", null, HTTP_OK);
        }else{
            $response->failed("Ups! Algo ha salido mal", INTERNAL_SERVER_ERROR);
        }
    }else{
        $response->failed("ID de noticia no proporcionado.", BAD_REQUEST);
    }
}

//Método DELETE para eliminar a un noticia en específico
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if(isset($datos['id'])){
        $estadoOperacion = $noticia->eliminarNoticia($datos['id']);
        if($estadoOperacion >= 1){
            $response->success("Noticia eliminada de manera exitosa", null, HTTP_ACCEPTED);
        }else{
            $response->failed("Ups! Algo ha salido mal", INTERNAL_SERVER_ERROR);
        }
    }else{
        $response->failed("ID de noticia no proporcionado.", BAD_REQUEST);
    }
}

?>
