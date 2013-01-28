<?php
    abstract class EntityManager{
        abstract public function persist($object); //for insert a new entity
        abstract public function merge($object); // for update a exist entity
        abstract public function remove($object); // for delete a exist entity
        abstract public function find($operator,$parameter,$camp, $multiple); // for find a exist entity
        
    }
?>
