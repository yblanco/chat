<?php
echo "LISTA";
$userModel = new usuarioModel();
$lista = $userModel->select("","pk_usu");
include($conf->validar('list','usuario'))
?>

