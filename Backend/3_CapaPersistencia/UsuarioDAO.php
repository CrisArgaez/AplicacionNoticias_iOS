<?php

require_once "../4_CapaDatos/Conexion.php";
require_once "../2_CapaNegocio/usuario.php";

class UsuarioDAO{
    private $DataSource;

    public function __construct(){
        $this->DataSource = new DataSource();
    }

    //CONSULTAR USUARIO POR ID
    public function consultar($id){
        $sql = "SELECT * FROM usuario WHERE id = :id";
        $values = [
            ':id' => $id
        ];
        return $this->DataSource->ejecutarConsulta($sql, $values);
    }

    //REGISTRAR A UN NUEVO USUARIO EN LA BASE DE DATOS
    public function crear(Usuario $usuario) {
        $sql = "
            INSERT INTO usuario
            (
                nombre_completo,
                email,
                username,
                password,
                pais_origen
            )
            VALUES
            (
                :nombre_completo,
                :email,
                :username,
                :password,
                :pais_origen
            )
        ";
        $values = [
            ':nombre_completo' => $usuario->getNombreCompleto(),
            ':email' => $usuario->getEmail(),
            ':username' => $usuario->getUsername(),
            ':password' => $usuario->getPassword(),
            ':pais_origen' => $usuario->getPaisOrigen()
        ];
        return $this->DataSource->ejecutarActualizacion($sql, $values);
    }

    //ACTUALIZAR INFORMACION DE UN USUARIO ESPECÍFICO
    public function actualizar($id, Usuario $usuario) {
        // Obtener el registro actual
        $usuarioDAO = new UsuarioDAO();
        $usuarioActualArray = $usuarioDAO->consultar($id);
    
        // Asumimos que solo hay un resultado en el array y lo tomamos
        $usuarioActual = $usuarioActualArray[0];
        
        // Usar los valores existentes si no se proporcionan nuevos
        $nombre_completo = $usuario->getNombreCompleto() ?? $usuarioActual['nombre_completo'];
        $email = $usuario->getEmail() ?? $usuarioActual['email'];
        $username = $usuario->getUsername() ?? $usuarioActual['username'];
        $password = $usuario->getPassword() ?? $usuarioActual['password'];
        $pais_origen = $usuario->getPaisOrigen() ?? $usuarioActual['pais_origen'];
    
        $sql = "UPDATE usuario 
                SET nombre_completo = :nombre_completo, 
                    email = :email, 
                    username = :username, 
                    password = :password, 
                    pais_origen = :pais_origen 
                WHERE id = :id";
                
        $values = [
            ':nombre_completo' => $nombre_completo,
            ':email' => $email,
            ':username' => $username,
            ':password' => $password,
            ':pais_origen' => $pais_origen,
            ':id' => $id
        ];
    
        return $this->DataSource->ejecutarActualizacion($sql, $values);
    }

    //ELIMINAR REGISTRO DE USUARIO 
	public function eliminar($id){
        $sql = "DELETE FROM usuario WHERE id = :id";
        $values = [
            ':id' => $id
        ];
        return $this->DataSource->ejecutarActualizacion($sql, $values);
    }

    //VERIFICAR INICIO DE SESIÓN
    public function verificarCredenciales($email, $password){
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $values = [
            ':email' => $email,
        ];

        $dataUsuario = $this->DataSource->ejecutarConsulta($sql, $values);

        if(!empty($dataUsuario)){
            $clave_encriptacion = "CalaComandanteCronkiLoopsy121103"; //Tamaño 32 bytes para AES-256

            //Proceso de desencriptacion 

            // Decodificar y separar el texto cifrado y el IV 
            list($cifrado_original, $iv) = explode('::', base64_decode($dataUsuario[0]['password']), 2); 
            
            // Descifrar los datos 
            $passwordReal = openssl_decrypt($cifrado_original, 'aes-256-cbc', $clave_encriptacion, 0, $iv);

            if($password == $passwordReal){
                return $dataUsuario;
            }else{
                return 1;
            }
        }else{
            return 2;
        }
    }

    //VERIFICACIÓN PARA NO REGISTRAR 2 CORREOS IGUALES
    public function verificarCorreoRepetido($email){
        $sql = "SELECT * FROM usuario WHERE email = :email";
        $values = [
            ':email' => $email,
        ];
        return $this->DataSource->ejecutarConsulta($sql, $values);
    }
    
}