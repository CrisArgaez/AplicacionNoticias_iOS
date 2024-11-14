<?php
include '../3_CapaPersistencia/NoticiaDAO.php';

class Noticia {
    private $id;
    private $titulo;
    private $autor;
    private $fecha_publicacion;
    private $pais;
    private $descripcion;
    private $categoría;
    private $cantidad_likes;
    private $cantidad_dislikes;
    private $ciudad;
    private $imagen_principal;

    //Constructor de la noticia
    public function __construct($id = null) {
        if ($id != null) {//Si el id no es nulo, la noticia ya existe y se recupera los datos
            $noticiaDAO = new NoticiaDAO();
            $noticia = $noticiaDAO->buscar($id);

            $this->id = $noticia['id'];
            $this->titulo = $noticia['titulo'];
            $this->autor = $noticia['autor'];
            $this->fecha_publicacion = $noticia['fecha_publicacion'];
            $this->pais = $noticia['pais'];
            $this->descripcion = $noticia['descripcion'];
            $this->categoría = $noticia['categoría'];
            $this->cantidad_likes = $noticia['cantidad_likes'];
            $this->cantidad_dislikes = $noticia['cantidad_dislikes'];
            $this->ciudad = $noticia['ciudad'];
            $this->imagen_principal = $noticia['imagen_principal'];
        }
    }

    //Métodos del objeto noticia
    public function consultarNoticia($id){
        $noticiaDAO = new NoticiaDAO();
        return $noticiaDAO->consultar($id);
    }
    
    public function crearNoticia(){
        $noticiaDAO = new NoticiaDAO();
        return $noticiaDAO->crear($this);
    }

    public function actualizarNoticia($id){
        $noticiaDAO = new NoticiaDAO();
        return $noticiaDAO->actualizar($id, $this);
    }

    public function eliminarNoticia($id){
        $noticiaDAO = new NoticiaDAO();
        return $noticiaDAO->eliminar($id);
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function getFechaPublicacion() {
        return $this->fecha_publicacion;
    }

    public function getPais() {
        return $this->pais;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getCategoría() {
        return $this->categoría;
    }

    public function getCantidadLikes() {
        return $this->cantidad_likes;
    }

    public function getCantidadDislikes() {
        return $this->cantidad_dislikes;
    }

    public function getCiudad() {
        return $this->ciudad;
    }

    public function getImagenPrincipal() {
        return $this->imagen_principal;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setAutor($autor) {
        $this->autor = $autor;
    }

    public function setFechaPublicacion($fecha_publicacion) {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    public function setPais($pais) {
        $this->pais = $pais;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setCategoría($categoría) {
        $this->categoría = $categoría;
    }

    public function setCantidadLikes($cantidad_likes) {
        $this->cantidad_likes = $cantidad_likes;
    }

    public function setCantidadDislikes($cantidad_dislikes) {
        $this->cantidad_dislikes = $cantidad_dislikes;
    }

    public function setCiudad($ciudad) {
        $this->ciudad = $ciudad;
    }

    public function setImagenPrincipal($imagen_principal) {
        $this->imagen_principal = $imagen_principal;
    }
}

?>
