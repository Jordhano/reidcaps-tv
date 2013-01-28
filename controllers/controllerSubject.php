<?php
    require_once ("../engine/Engine.php");
    require_once("../models/SubjectManager.php");
    if(isset($_POST['operation'])){
        $manager = new SubjectManager();
       
        if($_POST['operation']==1){//find Open
            $subjects = $manager->find($_POST['operator'],"'%{$_POST['parameter']}%'",$_POST['camp'],$_POST['multiple']);
            $result="";
            foreach ($subjects as $subject){
                $result = "{$result}<tr><td class='id'>{$subject->getId()}</td>" ;
                $result = "{$result}<td>{$subject->getName()}</td>" ;
                $dude = $subject->getLastLesson()==0?"No Iniciada":$subject->getLastLesson();
                $result = "{$result}<td>{$dude}</td>" ;
                $dude = $subject->getLastLesson()==0?"No Iniciada":$subject->getDate();
                $result = "{$result}<td>{$dude}</td></tr>" ;
            }
            print_r($result);
        }else if($_POST['operation']==2){
            $subject = $manager->find($_POST['operator'],"'{$_POST['parameter']}'",$_POST['camp'],$_POST['multiple']);
            print_r($subject->toJSON());
            //echo "Valor {$subject->getName()}";
        }else if ($_POST['operation']==3){//insert
            $sub = $_POST['subject'];
            $data['subject'] = array();
            echo($manager->persist(new Subject($sub['clave'],$sub['nombre'])));
        }else if ($_POST['operation']==4){//update
            $sub = $_POST['subject'];
            $data=array();
            $data['subject'] = new Subject($sub['clave'],$sub['nombre']);
            $data['clave'] = $_POST['clave'];
            echo($manager->merge($data));
        } else if ($_POST['operation']==5){//delete
            echo($manager->remove($_POST['clave']));
        } else if ($_POST['operation']==6){
            $subjects = $manager->find($_POST['operator'],"'%{$_POST['parameter']}%'",$_POST['camp'],$_POST['multiple']);
            $result="";
            foreach ($subjects as $subject){
                $result = "{$result}<option value='{$subject->getId()}'>{$subject->getName()}</option>";
            }
            echo $result;
        }
    }
?>
