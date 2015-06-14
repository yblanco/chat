<?php
class msj{
    
    private $msj;
    
    function __construct() {
        if(isset($_GET['m'])){
            $this->msj();
            echo $this->msj;
        }
    }
    
    function msj(){
        $m = (int) $_GET['m'];
        $this->msj = "Error: ";
        Switch($m){
            case 1:
                $this->msj.= "Usuario/Contraseña inválida<br>";
                break;
            default:
                $this->msj="";
        }
        
    }
}
?>

