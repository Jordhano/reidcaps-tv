<?php
    require_once ("EntityManager.php");
    require_once ("../entities/Subject.php");
    require_once ("../entities/Unit.php");
    require_once ("../entities/Lesson.php");
    class SubjectManager extends EntityManager{      
        
        public function find($operator,$parameter,$camp, $multiple) { //Multiple is false for multiple rows
            $query = "SELECT idSubject, name, lastLesson, dateLastLesson FROM subject WHERE {$camp} {$operator} {$parameter}";
            $result = mysql_query($query);
            if(mysql_num_rows($result)>0 && $multiple== "Y"){
                    $subjects= array(); 
                    while($row = mysql_fetch_array($result)){
                        $subject = new Subject($row['idSubject'],$row['name']);
                        $subject->setLastLesson($row['lastLesson']);
                        $subject->setDate($row['dateLastLesson']);
                        $subjects["{$row['idSubject']}"] = $subject;
                    }
                    return $subjects;
            }else {
                $row= mysql_fetch_array($result);
                $subject = new Subject($row['idSubject'],$row['name']);
                $subject->setLastLesson($row['lastLesson']);
                $subject->setDate($row['dateLastLesson']);

                $unitArray = array();
                $query = "SELECT idUnit, name, idSubject From unit WHERE idSubject='{$subject->getId()}'";
                $result = mysql_query($query);
                while ($row = mysql_fetch_array($result)){
                    $unit = new Unit ($row['idUnit'],$row['name']);

                    $lessonArray= array();
                    $query = "SELECT idLesson, name, idUnit From lesson WHERE idUnit='{$lesson->getId()}'";
                    $resultLesson = mysql_query($query);
                    while ($rowLesson = mysql_fetch_array($resultLesson)){
                        $lesson = new Lesson($rowLesson['idLesson'], $rowLesson['name']);
                        $lessonArray["{$rowLesson['idLesson']}"] = $lesson;
                    }

                    $unit->setLesson($lessonArray);
                    $unitArray["{$row['idUnit']}"] = $unit;
                }

                $subject->setUnits($unitArray);
                return $subject;
            }
        }
        
        public function merge($object) {
            $query = "UPDATE subject SET idSubject= '{$object['subject']->getId()}', name= '{$object['subject']->getName()}' WHERE idSubject='{$object['clave']}'";
            mysql_query($query);
            return "Completado" ;
        }
        
        public function persist($object) {
            $query = "INSERT INTO subject(idSubject, name, lastLesson, dateLastLesson) VALUES('{$object->getId()}','{$object->getName()}', 0, NOW());";
            mysql_query($query);
            if (mysql_affected_rows() == -1){
                return "Existe una materia con la misma clave de materia";
            }
            return "Completado" ;
        }
        
        public function remove($object) {
            $query = "DELETE FROM subject WHERE idSubject= '{$object}'";
            mysql_query($query);
            return "Completado";
        }
    }
    
?>
