<?php //
$msj = new msj();
    if(isset($_POST) && isset($_POST['ok']) && $_POST['ok']== true){
        $userModel = new usuarioModel();
        $ch_use = $_POST['user'];
        $ch_pass = lib::pass_encryp($_POST['pass']);
        $where = array (
                    'AND'=> array('ch_use'=>$ch_use,'ch_pass'=>$ch_pass),
                    'MIDDLE'=>'AND',
                );
        $userModel->login($where);
    }
   
?>
