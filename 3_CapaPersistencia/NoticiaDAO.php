<?php

require_once "../4Capa_Datos/Conexion.php";
require_once "../2Capa_Negocio/noticia.php";

class NoticiaDAO{
    private $DataSource;

    public function __construct(){
        $this->DataSource = new DataSource();
    }

    //CONSULTAR NOTICIA POR ID
    public function consultar($id){
        $sql = "SELECT * FROM noticias WHERE id = :id";
        $values = [
            ':id' => $id
        ];
        return $this->DataSource->ejecutarConsulta($sql, $values);
    }

    //AGREGAR UNA NUEVA NOTICIA
    public function crear(Noticia $noticia) {
        $sql = "INSERT INTO noticias (titulo, autor, fecha_publicacion, pais, descripcion, categoría, cantidad_likes, cantidad_dislikes, ciudad, imagen_principal) 
                VALUES (:titulo, :autor, :fecha_publicacion, :pais, :descripcion, :categoría, :cantidad_likes, :cantidad_dislikes, :ciudad, :imagen_principal)";
                
        $values = [
            ':titulo' => $noticia->getTitulo(),
            ':autor' => $noticia->getAutor(),
            ':fecha_publicacion' => $noticia->getFechaPublicacion(),
            ':pais' => $noticia->getPais(),
            ':descripcion' => $noticia->getDescripcion(),
            ':categoría' => $noticia->getCategoría(),
            ':cantidad_likes' => $noticia->getCantidadLikes(),
            ':cantidad_dislikes' => $noticia->getCantidadDislikes(),
            ':ciudad' => $noticia->getCiudad(),
            ':imagen_principal' => $noticia->getImagenPrincipal()
        ];
        
        return $this->DataSource->ejecutarActualizacion($sql, $values);
    }
    
    //ACTUALIZAR NOTICIA POR ID
    public function actualizar($id, Noticia $noticia) {
        // Obtener el registro actual
        $noticiaDAO = new NoticiaDAO();
        $noticiaActualArray = $noticiaDAO->consultar($id);
    
        // El noticia actual es el que obtenemos al consultar en la base el id que quiere realizar la actualización
        $noticiaActual = $noticiaActualArray[0];
        
        // Usar los valores existentes si no se proporcionan nuevos
        $titulo = $noticia->getTitulo() ?? $noticiaActual['titulo'];
        $autor = $noticia->getAutor() ?? $noticiaActual['autor'];
        $fecha_publicacion = $noticia->getFechaPublicacion() ?? $noticiaActual['fecha_publicacion'];
        $pais = $noticia->getPais() ?? $noticiaActual['pais'];
        $descripcion = $noticia->getDescripcion() ?? $noticiaActual['descripcion'];
        $categoría = $noticia->getCategoría() ?? $noticiaActual['categoría'];
        $cantidad_likes = $noticia->getCantidadLikes() ?? $noticiaActual['cantidad_likes'];
        $cantidad_dislikes = $noticia->getCantidadDislikes() ?? $noticiaActual['cantidad_dislikes'];
        $ciudad = $noticia->getCiudad() ?? $noticiaActual['ciudad'];
        $imagen_principal = $noticia->getImagenPrincipal() ?? $noticiaActual['imagen_principal'];
    
        $sql = "UPDATE noticias 
                SET titulo = :titulo, 
                    autor = :autor, 
                    fecha_publicacion = :fecha_publicacion, 
                    pais = :pais, 
                    descripcion = :descripcion, 
                    categoría = :categoría, 
                    cantidad_likes = :cantidad_likes, 
                    cantidad_dislikes = :cantidad_dislikes, 
                    ciudad = :ciudad, 
                    imagen_principal = :imagen_principal 
                WHERE id = :id";
                
        $values = [
            ':titulo' => $titulo,
            ':autor' => $autor,
            ':fecha_publicacion' => $fecha_publicacion,
            ':pais' => $pais,
            ':descripcion' => $descripcion,
            ':categoría' => $categoría,
            ':cantidad_likes' => $cantidad_likes,
            ':cantidad_dislikes' => $cantidad_dislikes,
            ':ciudad' => $ciudad,
            ':imagen_principal' => $imagen_principal,
            ':id' => $id
        ];
    
        return $this->DataSource->ejecutarActualizacion($sql, $values);
    }
     
    //ELIMINAR UNA NOTICIA ESPECÍFICA POR ID
	public function eliminar($id){
        $sql = "DELETE FROM noticias WHERE id = :id";
        $values = [
            ':id' => $id
        ];
        return $this->DataSource->ejecutarActualizacion($sql, $values);
    }
    
}