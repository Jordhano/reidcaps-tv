<?php

    class Conection{
        var $enlace;
        function __construct($url,$user,$pass,$database) {
            $this->enlace = mysql_connect($url,$user,$pass);
            if (!$this->enlace){
                die("Error de Conexion a la BD");
            }
            
            mysql_select_db($database);
        }
        
        function _destruct(){
            mysql_close();
        }
    }
?>
