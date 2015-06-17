<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php 
        require_once('core/config.php');     
        $conf->head($conf->getNombre(),"text/html", "UTF-8","","js.js");
    ?>
    <body>
        <?php 
            $cargar = $conf->allow();
//            if(isset($_SESSION['user'])){
            if($cargar){
                $conf->modulo();
                
                echo $conf->_link('home','INICIO')."<br>";
                echo $conf->_link('usuario/list','Lista')."<br>";
                echo $conf->logoutlink()."<br>";
            }else{
                $conf->login();   
                echo $conf->_link('usuario/add','Registrate');
            }
            
        ?>
    </body>
</html>