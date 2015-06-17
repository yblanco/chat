<?php
echo "LISTA";
$userModel = new usuarioModel();
$lista = $userModel->select("","pk_usu");
$userModel->_delete(1);
include($conf->validar('list','usuario'))
?>

