<?php
    $userModel = new usuarioModel();
    if(isset($_POST)){
        echo "";
    }else{
         include $conf->validar('add','usuario');
    }
    
?>