<?php
class usuarioModel extends model{

    private $tabla = "usuario";
    private $esquema = "registro";
    private $pk = "pk_usu";
    private $clase = "usuarioModel";
    
    function __construct(){
        parent::__construct($this->tabla, $this->esquema, $this->pk, $this->clase);
    }
    function __destruct() {
        parent::__destruct();
    }
    
    protected function tag(){
        return array(
            'pk_usu'=>'ID',
            'ch_nomb'=>'Nombre',
            'ch_ape'=>'Apellido',
            'ch_use'=>'Usuario',
            'ch_pass'=>'Contraseña',
            'bo_vis'=>'Borrado',
            'ch_ipr'=>'IP Registro',
            'ch_ipl'=>'IP Login',
            'dt_lal'=>'Fecha Úlima Conexión', 
        );
    }
    
    public function login($objeto){
        $result = $this->selectunico($objeto);
        var_dump($result);
        if($result == NULL){
            new msj(1);
            header("Location:".$_SERVER['REQUEST_URI']);
        }else{
            $this->update($this->data);
            $_SESSION['user'] =$result;
            header('location:'.$_SERVER['REQUEST_URI']);
        } 
    }
}
?>