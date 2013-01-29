<?php
    require_once ("EntityManager.php");
    require_once ("../entities/Unit.php");
    require_once ("../entities/Lesson.php");
    class UnitManager extends EntityManager{
        
        public function find($operator, $parameter, $camp, $multiple) {
            $query = "SELECT idUnit, name, idSubject FROM unit WHERE {$camp} {$operator} {$parameter}";
            $result = mysql_query($query);
             if(mysql_num_rows($result)>0 && $multiple== "Y"){
                    $units= array(); 
                    while($row = mysql_fetch_array($result)){
                        $unit = new Unit($row['idUnit'],$row['name'],$row['idSubject']);
                        $units["{$row['idUnit']}"] = $unit;
                    }
                    return $units;
             }else {
                  $row= mysql_fetch_array($result);
                  $unit =  new Unit ($row['idUnit'],$row['name'],$row['idSubject']);
                  
                  $lessonArray = array();
                  $query = "SELECT idLesson, name, idUnit From lesson WHERE idUnit='{$unit->getId()}'";
                  $resultLesson = mysql_query($query);
                  while ($rowLesson = mysql_fetch_array($resultLesson)){
                        $lesson = new Lesson($rowLesson['idLesson'], $rowLesson['name']);
                        $lessonArray["{$rowLesson['idLesson']}"] = $lesson;
                  }
                  $unit->setLesson($lessonArray);
                  
                  return $unit;
             }
        }
        public function merge($object) {
            $query = "UPDATE unit SET idUnit= {$object['unit']->getId()}, name= '{$object['unit']->getName()}',idSubject='{$object['unit']->getSubject()}' WHERE idUnit= {$object['1']} AND idSubject='{$object['2']}'";
            mysql_query($query);
            if (mysql_affected_rows() == -1){
                return "Existe una materia con la misma clave de materia";
            }
            return "Completado" ;
        }
        public function persist($object) {
            $query="INSERT INTO unit(idUnit, name, idSubject) VALUES ({$object->getId()},'{$object->getName()}','{$object->getSubject()}');";
            mysql_query($query);
            if (mysql_affected_rows()==-1){
                return "Existe una unidad con el mismo número";
            }
            return "Completado";

        }
        public function remove($object) {
            $query = "DELETE FROM unit WHERE idUnit= {$object["1"]} AND idSubject='{$object["2"]}'";
            mysql_query($query);
             if (mysql_affected_rows() == -1){
                return "Al Parecer Esta Unidad No Existe";
            }
            return "Completado";
        }
    }

?>
