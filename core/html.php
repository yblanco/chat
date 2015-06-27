<?php
class html{
    
    public function link_header($url,$para=""){
        $url = split("/", $url);
        $p="";
        if($para!=""){
            foreach($para as $key => $val){
                $p.=$key.$val."&";
            }
            $p=  substr($p, 0, -1);
        }
           $url_return="";
        foreach($url as $ur){
            $url_return.= $ur."/";
        }
        return "/".$url_return.$p;
    }
    
    
    /*Función Privada que genera attributos en html
     *---Recibe 2 parametro:
     *-----array de parametros
    */
    private function attr($array){
        $attr = "";
        if(is_array($array)){
            foreach($array as $key => $val){
                $attr.= $key."= '".$val."'";
            }
            return $attr;
        }else{
            return "";
        }
    }
    public function _link($url, $nombre,$para="", $attr=""){
        
        $link = "<a href='".$this->link_header($url,$para)."' ".$this->attr($attr).">";
        $link.=$nombre;
        $link.="</a>";
        return $link;
    }
    
    
    /*Función Privada que verifica el tipo de recurso y retorna la etiqueta indicada
     *---Recibe 2 parametro:
     *-----Tipo de recurso.
     *-----Valor del recurso.
    */
    private function tipoderecurso($tipo, $recurso){
        switch ($tipo){
            case "css":
                ?>
                    <link rel="stylesheet" type="text/css" href="<?php echo $this->getUrlCss().$recurso; ?>"/>
                <?php
                break;
            case "js":
                ?>
                    <script ype="text/javascript" src="<?php echo $this->getUrlJs().$recurso; ?>"></script>
                <?php
                break;
            case "img":
                ?>
                    <img src="<?php echo $this->getUrlimg().$recurso;?>"  alt="Imágen" />
                <?php
            default:
                break;
        
        }
    }
    
    /*Función que genera las etiquetas de recursos
     *---Recibe 2 parametros:
     *-----Valor del recurso.
     *-----Tipo del recurso.(css,js,img)    
    */
    public function resource($valor, $tipo){
        if(isset($valor)){
            if(is_array($valor)){
                foreach($valor as $recurso){
                    $this->tipoderecurso($tipo, $recurso);
                }
            }else{      
                $this->tipoderecurso($tipo, $valor);
            }
        }
    }
    
    
    /*Función que inserta el cerrar sesión
     *---Retorna: el htlm de cerrar sesión
    */
    public function logoutlink(){
        $link = "";
        if(isset($_SESSION['user'])){
            $link = "<a href='_log'>".LOGOUT."</a>";    
            $link = $this->_link("_log", LOGOUT);
        }
        return $link;
    }
    
    /*Función que inserta el html inicial
     *---Recibe 3 paramtros:
     *-----Nombre de la app.
     *-----Nombre de Archivo css o Array de nombres
     *-----Nombre de Archivo js o Array de nombres
    */
    public function head($app, $conten, $charset, $css="", $js=""){
        ?>
        <head>
            <meta http-equiv="Content-Type" content= <?php echo $conten ?> charset="<?php echo $charset; ?>" />
            <title><?php echo $app; ?></title>
            <link rel="shortcut icon" href="<?php echo $this->getUrlfavicon(); ?>favicon.ico"/>
            <?php
               $this->resource($css, "css");
               $this->resource($js, "js");
            ?>
        </head> 
        <?php
        return true;
    }
    
    
    /*Funcion para setear valores en los add
     * --Recibe: 
     * ----string de nombre de campo
     * --Retorna:
     * ----string de valor
     * 
     */
    private function post_campo($campo){        
        if(isset($_POST) && isset($_POST[$campo])){
                return $_POST[$campo];
        }else{
            return;
        }
    }
    
    private function obtenervalor($valores, $campo){
        if(is_array($valores)){
            if(isset($valores[$campo])){    
                return $valores[$campo];
            }else{
                return "";
            }
        }else{
            return $valores;
        }
    }
    
    private function options($array){
        return "<option value=''>nombre</option>";
    }
    
    protected function form($nombre,$op){
        if($op == 1){
            return "<form method='POST' name='".$nombre."'>";
        }else{
            return"</form>";
        }
    }
    
    protected function abel($nombre, $label){
        return $label == "" ? "" : "<label for='".$nombre."'>".$label."</label>";
    }
    
    protected function input($tipo, $nombre, $value="", $attr="", $lista=""){
        $valor = $this->obtenervalor($value, $nombre);
        switch($tipo){
            case "select":
                $select = "<select name='".$nombre."' id='".$nombre."' >";
                $select.= $this->options($lista);
                $select.="</select>";
                return $select;
            default:
                return "<input type='".$tipo."' name='".$nombre."' id='".$nombre."'  value='".$valor."' ".$this->attr($attr)." />";
        }
        
    }
    
    }
?>
    
