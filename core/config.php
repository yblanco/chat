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
            return "web/css/";
        }
        
        public function getUrlJs(){
            return "web/js/";
        }
    }    
        
    $conf = new config();
    
?> 	
