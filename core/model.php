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


    /*Comillas Inteligenes
     * Por desarrollar
     */
    private function comillas($where){
        if($where !=NULL){
        $pattern = array("insert","select","update","delete","drop","create","alter");
        if(is_numeric($where)){
            return "'".$where."'";
        }else{
            $where=addslashes($where);
            if(!eregi("[^A-Za-z0-9 ]",$where)){
                $where = str_ireplace($pattern, "", $where);
            }else{
                $where = NULL;
            }
            return "'".$where."'";
        }
        }else{
            echo "VALOR NO ENCONTRADO";die;
            return 0;
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
    
    /*Retorna el valor por defecto en caso que se esté insertando null o sea pk
     *--Recibe:
     *----Nombre de la Columna (String).
     *----Valor de la Columna.
     *--Retorna:
     *----Valor por defecto o valor insertado, según coresponda.
     */
    private function valuesdef($column, $value){
        if($column == $this->pk || $value == ""){
            $query = $this->getvaluesdef($column);
        }else{
            $query = "'".$value."'";
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
            $mid= $array['MIDDLE'];     
            unset($array['MIDDLE']);
            $total = count($array);
            foreach($array as $key => $value){
                $inicio =0;
                $total2= count($value);
                foreach($value as $key2 => $value2){
                    if($this->validacolumn($key2)){
                        if($inicio == 0){
                            $sql.="(".$key2."=".$this->comillas($value2);
                            $inicio++;
                        }else{
                            $sql.=" ".$key." ".$key2."=".$this->comillas($value2);
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
    
    
          
}
?>
  