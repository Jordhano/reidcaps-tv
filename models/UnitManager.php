<?php
    require_once ("EntityManager.php");
    require_once ("../entities/Unit.php");
    require_once ("../entities/Lesson.php");
    class UnitManager extends EntityManager{
        
        public function find($operator, $parameter, $camp, $multiple) {

        }
        public function merge($object) {

        }
        public function persist($object) {
            //
            $query="INSERT INTO unit(idUnit, name, idSubject) VALUES ()";
            mysql_query($query);
            if (mysql_affected_rows()==-1){
                return "Existe una unidad con el mismo número";
            }
            return "Completado";

        }
        public function remove($object) {

        }
    }

?>
