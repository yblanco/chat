VISTA DE USUARIO<br>
<?php
    foreach ($lista as $llave => $valor){
        echo "<i>".$llave."</i><br>";
        $campo="";
        foreach($valor as $llave2=>$valor2){
            $campo.="<b>".$llave2.": </b>".$valor2."<br>";
        }
        echo $conf->_link('usuario/view',$campo,array('id'=>$valor['pk_usu']));
    }
?>