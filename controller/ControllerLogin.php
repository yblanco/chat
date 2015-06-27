<?php //
    $login = array('user'=>'ch_use','pass'=>'ch_pass'); //por si los campos se llaman diferente
    $msj = new msj();   
    if(isset($_POST) && isset($_POST['ok']) && $_POST['ok']== true){
        $userModel = new usuarioModel();
        $ch_use = $_POST['user'];
        $ch_pass = lib::pass_encryp($_POST['pass']);
        $where = array (
                    'AND'=> array($login['user']=>$ch_use,$login['pass']=>$ch_pass),
                    'MIDDLE'=>'AND',
                );
        $userModel->login($where);
    }
   
?>
