<?php
echo "logoutController";    
session_destroy();
header('Location: index.php');
?>