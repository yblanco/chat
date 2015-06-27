VISTA DE USUARIO<br>
<?php
    foreach ($userModel->data as $llave => $valor){
        echo "<b>".$userModel->getLabel($llave).": </b>".$valor."<br>";
    }
    echo $conf->_link('usuario/delete','Eliminar',array('id'=>$id))."<br>";
    echo $conf->_link('usuario/edit','editar',array('id'=>$id));
?>