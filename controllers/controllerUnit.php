<?php
    require_once("../engine/Engine.php");
    require_once("../models/UnitManager.php");
    
    if(isset($_POST['operation'])){
        $manager = new UnitManager();
        
        if($_POST['operation']==1){//find Open
            //return print_r($_POST);
            $units = $manager->find($_POST['operator'],"'%{$_POST['parameter']}%'",$_POST['camp'],$_POST['multiple']);
            $result="";
             foreach ($units as $unit){
                 $result="{$result}<tr><td class='id'>{$unit->getId()}</td>";
                 $result="{$result}<td>{$unit->getName()}</td>";
                 $dude= count($unit->getLesson());
                 $result="{$result}<td>{$dude}</td>";
                 $result="{$result}<td>{$unit->getSubject()}</td>";
             }
             print_r($result);
        }elseif($_POST['operation']==2){//
            $unit= $manager->find($_POST['operator'],"'{$_POST['parameter']}'",$_POST['camp'],$_POST['multiple']);
            print_r($unit->toJSON());
        }else if($_POST['operation']==3){//insert
            echo ($manager->persist(new Unit($_POST['unit']['id'],$_POST['unit']['nombre'],$_POST['unit']['materia'])));
        }else if($_POST['operation']==4){
            $data = array();
            $data['unit'] = new Unit($_POST['unit']['id'],$_POST['unit']['nombre'],$_POST['unit']['materia']);
            $data['1'] = $_POST['clave']['1'];
            $data['2'] = $_POST['clave']['2'];
            echo ($manager->merge($data));
        }else if($_POST['operation']==5){
            echo($manager->remove($_POST['clave']));
        }
    }

?>
