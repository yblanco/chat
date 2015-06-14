<?php
   
class model extends conexion{
    private $tabla;
    private $esquema;
    private $pk;
    private $estructura;
    static $conn;
    
    
    function __construct($tabla, $esquema, $pk) {
        $this->tabla=(string) $tabla;
        $this->esquema=(string) $esquema;
        $this->pk = (string) $pk;
        $this->conn = $this->conectar();    
        $this->estructura = $this->getColumnas();   
    }
    
    function __destruct() {
        $this->desconectar();
    }
    
    /*Obtiene las columnas de la tabla
     *---Devuelve un Arreglo numérico con las columnas
     *      */
    private function getColumnas(){
        $sql = "SELECT column_name
                	FROM information_schema.columns
                        WHERE table_schema = '".$this->esquema."' AND table_name = '".$this->tabla."'";
        $sqlexecute = pg_exec($this->conn, $sql);
        $sqlresult = $this->estructura($sqlexecute);
        return $sqlresult;
    }
    
    /*Convierte el resultado de la consulta de las columnas en un array
     * --Recibe:
     * ----Resource id, del pg_exec;
     *      */
    private function estructura($columnas){
        $i=0;
        while($column = pg_fetch_array($columnas)){
            $this->estructura[$i] = $column[0]    ;
            $i++;
        } 
        return $this->estructura;
    }
    
    /*Convierte la estructura de la tabla en un string de nombre de columnas
     * --Recibe:
     * ----Arreglo de la estructura.
     * --Retorna:
     * ----String de columnas en formato: "columna1,columna2,columnan...
     *      */
    private function sqlcolumna($columnas){
        $nc = count($columnas);
        $i = 1;
        $sql="";
        foreach($columnas as $columna){
            if($nc == $i){
                $sql.= $columna;
            }else{
                $sql.= $columna.",";
            }
            $i++;
        }
        return $sql;
    }
    
       
    //Inicializa transacción(begin):
    public function iniciar(){
        $sql = "BEGIN";
        try {
            $sqlexecute = pg_query($sql);
            if(!$sqlexecute){
                throw new Exception("ERROR: ".pg_last_error());
            }
        }catch(Exception $e){
            echo $e; //LOG ERROR
        }
    }
    
    
    private function validacolumn($colum){
        if(in_array($colum, $this->estructura)){
            return true;
        }else{
            echo "COLUMNA <b>".$colum." </b>NO ENCONTRADA";die;
        }
    }

    
    /*Validar valor a insertar o Buscar
     * --Recibe:
     * ----string: nombre de la columna
     * --Retorna
     * --string: patron.
     *      */
    private function validavalor($columna,$valor){
        $type = $this->get_type($columna);
        $where=addslashes($valor);
        if(strpos(" ".$type,'int')!=false || strpos($type,'double')!=false){
            echo "ERROR: ".$valor." NO ES VALIDO PARA LA COLUMNA ".$columna." DE TIPO ".$type."<br>"; //die;
        }else{
            $where = Htmlspecialchars($where);
            return "'".$where."'";
        }
        
        
    }
    
    /*Comillas Inteligenes
     * --Recibe:
     * ----String: Valor del campo
     * --Retorna:
     * ----Valor reemplazadp
     * -Falta por desarrollar la validación de la columna.
     */
    private function comillas($where, $column){
        $pattern = array("insert","select","update","delete","drop","create","alter");
        if(is_numeric($where)){
            return "'".$where."'";
        }else{
            return $this->validavalor($column,$where);
        }
    }
    
    


    /*Obtener el valor por defecto de una columna
     * --Recibe:
     * ----Nombre de la columna
     * --Retorna:
     * ----Valor por defecto
     */
    private function getvaluesdef($column) {
        $sql = "SELECT column_default
                	FROM information_schema.columns
                        WHERE table_schema = '".$this->esquema."' 
                          AND table_name = '".$this->tabla."'
                          AND column_name = '".$column."'";
        $sqlexecute = pg_exec($this->conn, $sql);
        $sqlresult = pg_fetch_row($sqlexecute);
        pg_free_result($sqlexecute);
        return $sqlresult[0];
    }
    /*Obtener si una columna es null
     * --Recibe:
     * ----Nombre de la columna
     * --Retorna:
     * ----boolean
     */
    private function getis_null($column) {
        $sql = "SELECT is_nullable
                	FROM information_schema.columns
                        WHERE table_schema = '".$this->esquema."' 
                          AND table_name = '".$this->tabla."'
                          AND column_name = '".$column."'";
        $sqlexecute = pg_exec($this->conn, $sql);
        $sqlresult = pg_fetch_row($sqlexecute);
        pg_free_result($sqlexecute);
        if($sqlresult[0] == "NO"){
            $return = false;
        }else{
            return true;
        }
        return  $return;
        
    }
    /*Obtener si una columna es null
     * --Recibe:
     * ----Nombre de la columna
     * --Retorna:
     * ----boolean
     */
    private function get_type($column) {
        $sql = "SELECT data_type
                	FROM information_schema.columns
                        WHERE table_schema = '".$this->esquema."' 
                          AND table_name = '".$this->tabla."'
                          AND column_name = '".$column."'";       
        $sqlexecute = pg_exec($this->conn, $sql);
        $sqlresult = pg_fetch_row($sqlexecute);
        pg_free_result($sqlexecute);
        return strtolower($sqlresult[0]);
    }
    
    /*Retorna el valor por defecto en caso que se esté insertando null o sea pk
     *--Recibe:
     *----Nombre de la Columna (String).
     *----Valor de la Columna.
     *--Retorna:
     *----Valor por defecto o valor insertado, según coresponda.
     */
    private function valuesdef($column, $value){
        
        if($column == $this->pk || $value == ""){
            if(!$this->getis_null($column) && $column!=$this->pk){
                echo "ERROR: ".$column." NO PUEDE SER NULL"; die;
            }    
            if($column == $this->pk){
                $query = $this->getvaluesdef($column);
            }else{
                $query = $this->getvaluesdef($column);
            }
        }else{
            $query = $this->comillas($value, $column);
        }
        return $query;
    }
    
    /*Retorna el resultado de ejecución
      *--Recibe:
     *----string sql
     *--Retorna:
     *----id de sql
     *      */
    
    private function ejecutarsql($sql){
        try{
            $sqlexecute = pg_exec($this->conn, $sql); 
            if(!$sqlexecute){
                throw new Exception("ERROR: ".pg_last_error());
            }
        }catch(Exception $e){
            echo $e; //ESTO SE INSERTARÁ EN EL LOG ERROR
        }
        return $sqlexecute;
    }

    /*Function para ordenar los resultados de la consulta
     * --Recibe:
     * ----array
     * --Retorna:
     * ----arreglo de resultado.
     */
    
    private function ordenarresultado($resulta){
        foreach ($resulta as $key => $value){
            if(!is_numeric($key)){
                $array[$key] = $value;
            }
        }
        return $array;
    }

    
    /*Function para devolver los resultados de la consulta
     * --Recibe:
     * ----id de sql.
     * --Retorna:
     * ----arreglo de resultado.
     */
    
    private function resultado($idsql){
        if($idsql!=false){
            $array = "";
            $cont =0;
            while ($resulta = pg_fetch_array($idsql)){
                $array[$cont] = $this->ordenarresultado($resulta);
                $cont++;
            }
        
        }else{
            return $idsql;
        }
        
        return $array;
    }
    
    /*Function para generar clausula WHERE
     * --Recibe:
     * ----array
     * --Retorna
     * ----Sql where
     */
    private function generarWhere($array){
        $sql=" WHERE ";
        $cont = 0;
        if(is_array($array)){
            if(isset($array['MIDDLE'])){
                $mid= $array['MIDDLE'];     
                unset($array['MIDDLE']);
            }
            $total = count($array);
            foreach($array as $key => $value){
                $inicio =0;
                $total2= count($value);
                foreach($value as $key2 => $value2){
                    if($this->validacolumn($key2)){
                        if($inicio == 0){
                            $sql.="(".$key2."=".$this->comillas($value2,$key2);
                            $inicio++;
                        }else{
                            $sql.=" ".$key." ".$key2."=".$this->comillas($value2,$key2);
                            $inicio++;
                        }
                        if($inicio == $total2){
                            $sql.=")";
                        }
                    }
                }
                $cont++;
                if($cont < $total){
                    $sql.=" ".$mid." ";
                }
            }
            return $sql;
        }else{
            return "";
        }        
    }
    
    
    /*Generar Order By
     * --Recibe:
     * ----columna
     * ----Orderby
     * --Retorna:
     * ----String del limit.
     */
    private function generarOrderBy($orderby){
        $limit ="";
        if($orderby != "" && $orderby != 1){
            $limit = " ORDER BY ".$orderby." ASC";
        }elseif($orderby == true){
            $limit = " ORDER BY ".$this->pk." DESC LIMIT 1";
        }
        return $limit;
    }
    

    /*Select de uno o varios.
     *--Recibe
     *----arreglo con parametros para where y order by
     *----retorna resultado
    */ 
    public function select($where="", $order=""){
        $sql = "SELECT ".$this->sqlcolumna($this->estructura);
        $sql.= " FROM ".$this->esquema.".".$this->tabla;
        if(is_array($where)){
            $sql.= $this->generarWhere($where);
        }
        if($order!=""){
            $sql.= $this->generarOrderBy($order);
        }
//        echo $sql."<br>";
        $sqlexecute = $this->ejecutarsql($sql);
        $sqlresult =  $this->resultado($sqlexecute);         
        return $sqlresult;
        
    }
    
    
    /*Select de un registro
     *--Recibe
     *----arreglo con parametros para where y order by
     *----retorna resultado
    */ 
    public function selectunico($where){
        $final = NULL;
        $resultados = $this->select($where,true);
        if(is_array($resultados)){
            foreach($resultados as $resultado){
                $final = $resultado;
            }
        }
        return $final;
    }
    
    /*INSERT de un registro
     * --Recibe:
     * ----arreglo de valores
     * --Retorna:
     * ----resultado
     */
    public function insertar($array){
        $sql = "INSERT INTO ".$this->esquema.".".$this->tabla." (";
        $col = $this->sqlcolumna($this->estructura);
        $sql.=$col.") VALUES (";
        $sql.=$this->valores($array, $col);
        $sql.=");";
        $sql.= $this->curren();
        $sqlexecute = $this->ejecutarsql($sql);
        $sqlresult =  $this->resultado($sqlexecute); 
        return $sqlresult[0]['currval'];
    }
    
    private function curren(){
        $sql= "SELECT currval('".$this->esquema.".".$this->tabla."_".$this->pk."_seq'::regclass);";
        
        return $sql;
    }


    /*Funcion para organizar campos del insert
     * --Recibe:
     * ----valores: array
     * ---- columnas: string
     * --Retorna:
     * ----string: de valores ordenados.
     */
    private function valores($value, $columnas){
        $columnas = split(",", $columnas);
        $cont = count($columnas);
        $i=0;
        $sql="";
        foreach($columnas as $col){
            $sql.=$this->valuesdef($col,$value[$col]);
            $i++;
            if($cont>$i){
                $sql.=",";
            }
        }
        
        return $sql;
        
    }
  
    /*UPDATE de un registro
     * --Recibe:
     * ----arreglo de valores
     * --Retorna:
     * ----resultado
     */
    public function update($valores){
        $sql = "UPDATE ".$this->esquema.".".$this->tabla." SET ";
//        $valores = array_intersect_key($valores, $this->estructura);
        $sql.=$this->set($valores);
        $where=array(
            'AND'=> array($this->pk=>$valores[$this->pk]),
        );
        $sql.= $this->generarWhere($where);
        $sqlexecute = $this->ejecutarsql($sql);
        return($sqlexecute);
    }
    
    /*Funcion para organizar campos del insert
     * --Recibe:
     * ----valores: array
     * --Retorna:
     * ----string: de valores ordenados.
     */
    private function set($value){
        $sql = "";     
        $cont = count($this->estructura);
        $i=1;
        foreach($this->estructura as $col){
            if(isset($value[$col])){
                $sql.=$col."=".$this->comillas($value[$col], $col);
                $i++;
                if($i < $cont){
                    $sql.=",";                    
                }   
            }   
        }
        return $sql;
    }
    
}
?>
  