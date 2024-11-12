<?php

include 'EnvLoader.php';

class DataSource{
    private $conexion;
    private $cadenaParaConexion;
    public function __construct(){
        try{
            $host = EnvLoader::get('DB_HOST');
            $dbname = EnvLoader::get('DB_DATABASE');
            $user = EnvLoader::get('DB_USERNAME');
            $password = EnvLoader::get('DB_PASSWORD');
            
            $this->cadenaParaConexion = "mysql:host=$host;dbname=$dbname";
            $this->conexion = new PDO($this->cadenaParaConexion, $user, "$password");
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }

    public function ejecutarConsulta($sql = "", $values = []){
        if($sql != ""){
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute($values);
            return $consulta->fetchAll(PDO::FETCH_ASSOC);
        }else{
            return 0;
        }
    }

    public function ejecutarActualizacion($sql = "", $values = []){
        if($sql != ""){
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute($values);
            return $consulta->rowCount();
        }else{
            return 0;
        }
    }

    public function getConexion(){
        return $this->conexion;
    }
}
?>