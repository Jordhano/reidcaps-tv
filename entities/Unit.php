<?php

class Unit {
    private $id;
    private $name;
    private $lesson;
    private $subject;
    function __construct($id, $name,$subject) {
        $this->id = $id;
        $this->name = $name;
        $this->subject = $subject;
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
    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function toJSON() {
        $result ='{"id":"'.$this->id.'", "name":"'.$this->name.'","idSubject":"'.$this->subject.'","lessons":'.count($this->lesson).'}';
        return $result;
    }
    
}

?>
