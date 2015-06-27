<?php

class form extends html{
    
    private $modelo;
    
    function __construct($model){
        $this->modelo = $model;
    }
    
    public function _formulario(){
        return $this->form($this->modelo->nombreClase(), 1);
    }
    public function formulario_(){
        return $this->form($this->modelo->nombreClase(), 2);
    }
    
    public function lista(){
        
    }
    
    public function text($nombre, $attr=""){
        return $this->input('text', $nombre, $this->modelo->data, $attr);
    }
    
    public function label($id, $nombre=""){
        if($nombre == ""){
            return $this->abel($id, $this->modelo->getLabel($id));
        }else{
            return $this->abel($id, $nombre);
        }    
    }
    
    public function submit($nombre, $attr=""){
        return $this->input('submit', $nombre, $nombre, $attr);
    }
    
    public function hidden($nombre, $valor=""){
        $valor = $valor == "" ? $this->modelo->data : $valor;
        return $this->input('hidden', $nombre, $valor, $attr);
    }
    
    public function select($nombre, $lista, $selected="", $attr=""){
        return $this->input('select', $nombre, $selected, $attr, $lista);
    }
    
}
?>