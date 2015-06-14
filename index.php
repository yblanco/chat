<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php 
        require_once('core/config.php');     
        $conf->head($conf->getNombre(),"text/html", "UTF-8","","js.js");
    ?>
    <body>
        <?php 
            if(isset($_SESSION['user'])){
                $conf->modulo();
            }else{
                $conf->login();   
            }
        ?>
    </body>
</html>