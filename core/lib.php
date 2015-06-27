<?php
session_start();    
class lib extends html{
    private $mod;        
    
    //Devuelve Url del login.
    public function login(){
        require_once ($this->getUrlController()."ControllerLogin.php");
        include($this->validar('login', false));
        return true;
    }
    public function logout(){
        require_once ($this->getUrlController()."ControllerLogout.php");
        return true;
    }
    
    public function pass_encryp($pass){
        $pass_encrypt = TWEED.$pass.TWEED;
        $pass_encrypt = base64_encode($pass_encrypt.TWEED);
        $pass_encrypt = md5($pass_encrypt.TWEED);
        $pass_encrypt = sha1($pass_encrypt.TWEED);
        $pass_encrypt = base64_decode($pass_encrypt.TWEED);
        $pass_encrypt = md5($pass_encrypt.TWEED);
        return $pass_encrypt;
    }

    //devuelve url del controlador
    public function modulo(){
        if(isset($_GET['logout']) && $_GET['logout']=="log"){
                    $this->logout();
        }else{  
            $user_session = isset($_SESSION['user']) ? $_SESSION['user'] : "";
            if(isset($_GET['mod']) && isset($_GET['act']) && $_GET['act'] != "" && $_GET['mod']!= ""){
                $accion = $_GET["act"];
                $modulos = $_GET['mod'];
                $dr =  $this->getUrlController().$modulos."/Controller".ucfirst(strtolower($accion)).".php";
            } else {
                $accion = "Home";
                $dr =  $this->getUrlController()."Controller".ucfirst(strtolower($accion)). ".php";
            }
            if (is_file($dr)) {
                $conf = new config();
                require_once($dr);
            } else {
                require_once($this->error());
            }
        }   
    }
    
    //Retorna el controllador de error
    private function error() {
        return $this->getUrlController()."ControllerError.php";
    }

    /*valida si el módulo se incluyó bien.
     * --Recibe:
     * ----tipo: string, nombre de la vista
     * ----control: string nombre del modulo
     * --Retorna:
     * ----url de la vista.
    */
    private function validar($tipo, $control){
        if(!$control){
            $url = $this->getUrlView().$tipo.".php";
        }else{
            $url = $this->getUrlView().$control.'/_'.$tipo.".php";
        }
        
        if(is_file($url)){
            return $url; 
        }else{
            require_once $this->error();
            return false;
        } 
    }    
    
}