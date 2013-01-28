<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Asignatura
 *
 * @author Jordhano Abrahan
 */
class Subject {
    private $id;
    private $name;
    private $lastLesson;
    private $date;
    private $units;
    
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

    public function getLastLesson() {
        return $this->lastLesson;
    }

    public function setLastLesson($lastLesson) {
        $this->lastLesson = $lastLesson;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getUnits() {
        return $this->units;
    }

    public function setUnits($units) {
        $this->units = $units;
    }
    
    public function toJSON() {
     
        $result='{"id":"'.$this->id.'", "name":"'.$this->name.'","lastLesson":'.$this->lastLesson.', "date": "'.$this->date.'", "units": '.count($this->units).'  }';
        return $result;
    }

      //Escribir luego para obtener determinada unidad
    /*public function getUnidad($id){
        
    }*/
}

?>
