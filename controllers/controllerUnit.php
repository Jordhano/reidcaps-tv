<?php
    require_once("../engine/Engine.php");
    require_once("../models/UnitManager.php");
    
    if(isset($_POST['operation'])){
        $manager = new UnitManager();
        
        if($_POST['operation']==1){//find Open
            
        }elseif($_POST['operation']==2){//
            
        }else if($_POST['operation']==3){//insert
            print_r($_POST);
        }
    }

?>
