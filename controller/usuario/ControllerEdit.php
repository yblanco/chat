<?php
    $userModel = new usuarioModel();
    $conf = new config();
    if(isset($_POST) && isset($_POST['ok'])){
        $id = $_POST['pk_usu'];
        $userModel->update($_POST); 
        header("location:".$conf->link_header('usuario/view',array('id'=>$id)));
    }
    
    if(isset($_GET['id']) && !empty($_GET['id'])){        
        $id = $_GET['id'];
        $where = array (
                        'AND'=> array('pk_usu'=>$id),
                    );
        $userModel->selectunico($where);
    }else{
        echo "ERROR";die;
    }
    include $conf->validar('edit','usuario');
    
?>