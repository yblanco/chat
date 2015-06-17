<?php
    
    if(isset($_GET['id']) && !empty($_GET['id'])){
        $userModel = new usuarioModel();
        $id = $_GET['id'];
        $where = array (
                        'AND'=> array('pk_usu'=>$id),
                    );
        $lista = $userModel->selectunico($where);
        $userModel->delete($lista);
        header("location:index.php");
    }else{
        echo "ERROR";die;
    }
    
?>