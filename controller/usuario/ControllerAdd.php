<?php
    $userModel = new usuarioModel();
    $conf = new config();
    if(isset($_POST) && isset($_POST['ok'])){
        if($_POST['ch_pass'] == $_POST['ch_pass2']){
            $_POST['ch_pass'] = lib::pass_encryp($_POST['ch_pass']);
            $id = $userModel->insertar($_POST);
            $where = array (
                    'AND'=> array('pk_usu'=>$id),
                    'MIDDLE'=>'AND',
                );
            $userModel->login($where);
            header("location:".$conf->link_header('usuario/view',array('id'=>$id)));
        }else{
            new msj(2);
        }
    }elseif(isset($_SESSION['user'])){
        header("location:". $conf->link_header(3));
    }
    include $conf->validar('add','usuario');
   
    
?>