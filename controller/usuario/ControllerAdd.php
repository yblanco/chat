<?php
    $userModel = new usuarioModel();
    $conf = new config();
    if(isset($_POST) && isset($_POST['ok'])){
        if($_POST['ch_pass'] == $_POST['ch_pass2']){
            $_POST['ch_pass'] = lib::pass_encryp($_POST['ch_pass']);
            $id = $userModel->insertar($_POST);
            header("location:index.php?mod=usuario&act=view&id=".$id);
        }else{
            new msj(2);
        }
    }
         include $conf->validar('add','usuario');
    
?>