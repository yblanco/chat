<?php
session_start();    
class lib{
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
            $user_session = $_SESSION['user'];
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
    
    
    
    /*Función Privada que verifica el tipo de recurso y retorna la etiqueta indicada
     *---Recibe 2 parametro:
     *-----Tipo de recurso.
     *-----Valor del recurso.
    */
    private function tipoderecurso($tipo, $recurso){
        switch ($tipo){
            case "css":
                ?>
                    <link rel="stylesheet" type="text/css" href="<?php echo $this->getUrlCss().$recurso; ?>"/>
                <?php
                break;
            case "js":
                ?>
                    <script ype="text/javascript" src="<?php echo $this->getUrlJs().$recurso; ?>"></script>
                <?php
                break;
            case "img":
                ?>
                    <img src="<?php echo $this->getUrlimg().$recurso;?>"  alt="Imágen" />
                <?php
            default:
                break;
        
        }
    }
    
    /*Función que genera las etiquetas de recursos
     *---Recibe 2 parametros:
     *-----Valor del recurso.
     *-----Tipo del recurso.(css,js,img)    
    */
    public function resource($valor, $tipo){
        if(isset($valor)){
            if(is_array($valor)){
                foreach($valor as $recurso){
                    $this->tipoderecurso($tipo, $recurso);
                }
            }else{      
                $this->tipoderecurso($tipo, $valor);
            }
        }
    }
    
    
    /*Función que inserta el cerrar sesión
     *---Retorna: el htlm de cerrar sesión
    */
    public function logoutlink(){
        $link = "<a href='index.php?logout=log'>".LOGOUT."</a>";
        return $link;
    }
    
    /*Función que inserta el html inicial
     *---Recibe 3 paramtros:
     *-----Nombre de la app.
     *-----Nombre de Archivo css o Array de nombres
     *-----Nombre de Archivo js o Array de nombres
    */
    public function head($app, $conten, $charset, $css="", $js=""){
        ?>
        <head>
            <meta http-equiv="Content-Type" content= <?php echo $conten ?> charset="<?php echo $charset; ?>" />
            <title><?php echo $app; ?></title>
            <link rel="shortcut icon" href="<?php echo $this->getUrlfavicon(); ?>favicon.ico"/>
            <?php
               $this->resource($css, "css");
               $this->resource($js, "js");
            ?>
        </head> 
        <?php
        return true;
    }
    
    
}