<?php
    $conf = new config();
    if(isset($_GET['id']) && !empty($_GET['id'])){
        $userModel = new usuarioModel();
        $id = $_GET['id'];
        $where = array (
                        'AND'=> array('pk_usu'=>$id),
                    );
        $lista = $userModel->selectunico($where);
        $userModel->delete($lista);
        header("location:". $conf->_link_header('home'));
    }else{
        echo "ERROR";die;
    }
    
?>