<?php
include '../3_CapaPersistencia/UsuarioDAO.php';

class Usuario {
    private $id;
    private $nombre_completo;
    private $email;
    private $username;
    private $password;
    private $pais_origen;

    public function __construct($id = null) {
        if ($id != null) {
            $usuarioDAO = new UsuarioDAO();
            $usuario = $usuarioDAO->buscar($id);
            $this->id = $usuario['id'];
            $this->nombre_completo = $usuario['nombre_completo'];
            $this->email = $usuario['email'];
            $this->username = $usuario['username'];
            $this->password = $usuario['password'];
            $this->pais_origen = $usuario['pais_origen'];
        }
    }

    public function consultarUsuario($id){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->consultar($id);
    }
    
    public function registrarUsuario(){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->crear($this);
    }

    public function actualizarUsuario($id){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->actualizar($id, $this);
    }

    public function eliminarUsuario($id){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->eliminar($id);
    }

    public function iniciarSesion($email, $password){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->verificarCredenciales($email, $password);
    }

    public function verificarCorreo($email){
        $usuarioDAO = new UsuarioDAO();
        return $usuarioDAO->verificarCorreoRepetido($email);
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombreCompleto() {
        return $this->nombre_completo;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getPaisOrigen() {
        return $this->pais_origen;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNombreCompleto($nombre_completo) {
        $this->nombre_completo = $nombre_completo;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setPaisOrigen($pais_origen) {
        $this->pais_origen = $pais_origen;
    }
}
?>
