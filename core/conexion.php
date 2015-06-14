<?php

class conexion extends ip{
    static $host=DB_HOST;
    static $port= DB_PORT;
    static $bd= DB_NAME;
    static $user=DB_USER;
    static $password=DB_PASS;
    private $conn;
    
    public function conectar(){
       
            $conectar = "host=".self::$host." port=".self::$port." dbname=".self::$bd." user=".self::$user." password=".self::$password; 
            $this->conn=  pg_connect($conectar) or die("<b>Error de Conexión</b>");

            if($this->conn != true || $this->conn == NULL){
                echo "No se pudo conectar: ".pg_last_error($this->conn);die; // LOG ERROR
            }else{
                echo "<br>Se conectó el IP: ".$this->ObtenerIP()."<br>"; // LOG AUDI
            }

        return $this->conn;
    }
    public function desconectar(){
        if(!pg_close($this->conn)){
            echo "No se pudo desconectar: " .pg_last_error($this->conn);die; // LOG ERROR
        }else{
            echo "<br>Se Desconectó el IP: ".$this->ObtenerIP()."<br>"; //LOG AUDI
        }
        
    }
}