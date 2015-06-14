<?php
echo "LISTA";
$userModel = new usuarioModel();
$lista = $userModel->select();
var_dump($lista);
?>

