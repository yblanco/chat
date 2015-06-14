<?php
class ip{
    
    static $ip;

    private function extraerip($ip){
         if(strstr($ip,',')){
            $ip = array_shift(explode(',',$ip));
       }
       return $ip;
    }
    //obtener la ip
    public function ObtenerIP(){
        if(isset($_SERVER)){
            if(!empty($_SERVER['HTTP_CLIENT_IP'])){
                $this->ip=$_SERVER['HTTP_CLIENT_IP'];
            }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $this->ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $this->ip=$_SERVER['REMOTE_ADDR'];
            }
        }else{
            if(getenv('HTTP_CLIENT_IP')){
                $this->ip=getenv('HTTP_CLIENT_IP');
            }elseif(getenv('HTTP_X_FORWARDED_FOR')){
                $this->ip=getenv('HTTP_X_FORWARDED_FOR');
            }else{
                $this->ip=getenv('REMOTE_ADDR');
            }
        }  
        
       return $this->extraerip($this->ip);
    }
}
?>