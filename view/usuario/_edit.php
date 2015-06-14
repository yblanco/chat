EDIT DE USUARIO<br>
<form name="usuario" method="post" >
    <input type="hidden" name="ok" value="true">
    <input type="hidden" name="pk_usu" value="<?php echo $id ?>">
    <label for="_ch_nomb">Nombre</label><br>
    <input type="text" name="ch_nomb" id="_ch_nomb" value="<?php echo $conf->post_campo('ch_nomb'); ?>"><br>
    
    <label for="_ch_ape">Apellido</label><br>
    <input type="text" name="ch_ape" id="_ch_ape" value="<?php echo $conf->post_campo('ch_ape'); ?>"><br>
    
    <label for="_ch_use">Usuario</label><br>
    <input type="text" name="ch_use" id="_ch_use" value="<?php echo $conf->post_campo('ch_use'); ?>"><br>
    
<!--    <label for="_ch_pass">Contraseña</label><br>
    <input type="password" name="ch_pass" id="_ch_pass"><br>
    
    <label for="_ch_pass">Introduzca de Nuevo su Contraseña</label><br>
    <input type="password" name="ch_pass2" id="_ch_pass2"><br>-->
    
    <input type="submit" name="_usuario" value="Guardar">
</form>