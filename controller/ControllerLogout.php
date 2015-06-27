<?php
echo "logoutController"; 
$conf = new config();
session_destroy();
header('Location: '.$conf->link_header('home'));
?>