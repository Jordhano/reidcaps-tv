<?php

class Unit {
    private $id;
    private $name;
    private $lesson;
    
    function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getLesson() {
        return $this->lesson;
    }

    public function setLesson($lesson) {
        $this->lesson = $lesson;
    }

    
    //Escribir luego para obtener determinada Leccion
    /*public function getLeccion($id){
        
    }*/
    
}

?>
