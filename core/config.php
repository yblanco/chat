<?php 
    include 'config.inic.php';
    function __autoload($class) {
        if(file_exists("core/".$class.".php")){
            require_once("core/".$class.".php");
        }elseif(file_exists("model/".$class.".php")){
            require_once("model/".$class.".php");
        }else{
            echo "CLASE NO ENCONTRADA".$class;die;
        }
    }
    
    class config extends lib{
        
        private $permitido;
        
        function __construct() {
            $this->permitido=array('usuario'=>array('add')); //sacar esto de aquí
        }
        
        public static function getNombre(){
            Return APP;
        }
        //Url estatica
        public static function getUrl(){
            return _URL;
        }
               
        public function getUrlController(){
            return "controller/";
        }
        
        
        public function getUrlView(){
            return "view/";
        }
        
        public function getUrlimg(){
            return "web/img/";
        }
        
        public function getUrlfavicon(){
            return "web/img/favicon/";
        }
        
        public function getUrlCss(){
            return "/web/css/";
        }
        
        public function getUrlJs(){
            return "/web/js/";
        }
        
        public function allow(){
            $flag=false;
            $msj=4;
            if(isset($_GET) && isset($_GET['mod']) && isset($_GET['act'])){
                foreach($this->permitido as $key=>$value){
                    if($_GET['mod']==$key){
                        foreach($value as $val){
                            if($_GET['act']==$val){
                                $flag = true;
                            }elseif(!isset($_SESSION['user'])){
                                new msj($msj);
                            }
                        }
                    }
                }
            }
            if(isset($_SESSION['user'])){
                //Falta validar la url y los permisos de la sesión
                $flag = true;
            }
            return $flag;
        }
    }    
        
    $conf = new config();
    
?> 	
