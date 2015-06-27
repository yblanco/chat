ADD DE USUARIO<br>
<?php
$form = new form($userModel);
echo $form->_formulario();
echo $form->hidden('ok', true);
echo $form->label('ch_nomb');     
echo $form->text('ch_nomb');
echo "<br>";
echo $form->label('ch_ape');     
echo $form->text('ch_ape');
echo "<br>";
echo $form->label('ch_use');     
echo $form->text('ch_use');
echo "<br>";
echo $form->label('ch_pass');     
echo $form->text('ch_pass');
echo "<br>";
echo $form->label('ch_pass2', 'Introduzca de Nuevo su Contraseña');     
echo $form->text('ch_pass2');
echo "<br>";
echo $form->submit('Guardar');
echo $form->formulario_();
?>