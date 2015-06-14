<?php
class msj{
    
    private $msj;
    private $error;
    
    function __construct($val ="") {
        $this->establecer();
        
        if($val == ""){
            if(isset($_GET['m'])){
                $m = (int) $_GET['m'];
                $this->msj($m);
                echo $this->msj;
            }
        }else{
            $m = $val;
            $this->msj($m);
            echo $this->msj;
        }
        
    }
    
    private function establecer(){
        $this->error = "ERROR: ";
    }
    
    private function msj($m){
        
        Switch($m){
            case 1:
                $this->msj= $this->error."Usuario/Contraseña inválida<br>";
                break;
            case 2:
                $this->msj= $this->error."Las Contraseñas no coinciden<br>";
                break;
            default:
                $this->msj="";
        }
        
    }
}
?>

