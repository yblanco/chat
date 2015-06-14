<?php
class usuarioModel extends model{

    private $tabla = "usuario";
    private $esquema = "registro";
    private $pk = "pk_usu";
    
    function __construct(){
        parent::__construct($this->tabla, $this->esquema, $this->pk);
    }
    function __destruct() {
        parent::__destruct();
    }    
    
    public function login($objeto){
        $result = $this->selectunico($objeto);
        if($result == NULL){
            header("Location:index.php?m=1");
        }else{
            $_SESSION['user'] =$result;
            /*Aquí va un reload de la pàgina*/
        }
        
    }
}
?>