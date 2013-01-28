<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="../js/jquery-1.9.0.js"></script>
        <script src="../js/jTPS.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/jTPS.css"/>
        <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.10.0.custom.min.css"/>
        <script src="../js/jquery-ui-1.10.0.custom.min.js"></script>
        <script>
            var eUnit =null;
            $(document).ready(function (){
                $('#FormUnit').dialog({autoOpen:false,modal:true,resizable:false});
                $('#btnNewUnit').click(function(){
                    clearFormUnit();
                    loadSubjects();
                    $('#FormUnit').dialog("option","title","Registro de Unidad");
                    $('#FormUnit').dialog("option","buttons",[{text:"Registrar",click:save},{text:"Cerrar",click:closeDialog}]);
                    $('#ExistUnit').hide();
                    $('#FormUnit').dialog("open");
                    
                });
            });
            
            function save(){
                try{
                    validation($('#txtIdUnit'), "No. de Unidad");
                    validation($('#txtNameUnit'),"Nombre de Unidad");
                    
                    $.ajax({data:{"operation":3,"unit":getJSONUnit()},
                        type:"POST",
                        url:"../controllers/controllerUnit.php"}).done(function (data){
                            alert(data);
                            $("#result").html(data);
                            /*alertDialog("Registro Guardado",data, false,"");
                            if (data="Completado"){
                                $('#btnSearch').click();
                                $('#FormUnit').dialog("close");
                                clearFormUnit();
                            }else{
                                $('#txtIdUnit').focus();
                            }*/
                        });
                }catch(err){}
            }
            function getJSONUnit(){
                var JSONSubject={
                    id:$('#txtIdUnit').val(),
                    nombre:$('#txtNameUnit').val(),
                    materia:$('#dbSubject option:selected').val()
                };
            }
            function validation(txt,message){
                if (txt.val().length == 0){
                    alert("El Campo " + message+" Se Encuentra Vacio");
                    txt.focus();
                    throw "Empty";
                }
            }
            function closeDialog(){
                $(this).dialog("close");
                 clearFormUnit();
                eUnit=null;
            }
            function clearFormUnit(){
                $('#txtIdUnit').val("");
                $('#txtNameUnit').val("");
                $('#txtLessonsUnits').val("");
            }
            
            function loadSubjects(){
                $.ajax({data:{"operation":6,"camp":"idSubject","operator":"LIKE","parameter":"","multiple":"Y"},
                        type:"POST",
                        url: "../controllers/controllerSubject.php"
                    }).done(function (data){
                        $("#dbSubject").append(data);
                    });
            }
        </script>
        <title>Manejador de Unidad</title>
    </head>
    <body>
        <h1>Registro de Unidad</h1>
        <div id="OptionsSubject">
            <input type="button" value="Agregar Unidad" id="btnNewUnit" />
        </div>
        <div id="FormUnit">
            <img src="../resources/images/1359171448_lessons.png"/><br/>
            <label for="txtIdUnit">No. : </label>
            <input type="text" id="txtIdUnit" /> <br/>
            <label for="txtNameUnit">Nombre: </label>
            <input type="text" id="txtNameUnit"/> <br/>
            <label for="dbSubject">Materia: </label>
            <select id="dbSubject"></select><br/>
            
            <div id="ExistUnit">
                <label for="txtLessonsUnits" >Cantidad de Lessiones: </label>
                <input type="text" id="txtLessonsUnits" readonly /> <br/>
            </div>            
            <!--
            <input type="button" value="Guardar" id="btnSave"/>
            <input type="button" value="Cancelar" id="btnCancel"/>
            -->
        </div>
        
        <br/><br/><br/>
        <div id="FindUnit">
            <span>
                <label for="txtSearchText">Buscar: </label>
                <input type="text" id="txtSearchText" />
                <select id="optbtnSearchBy" >
                    <option value="idLeccion">No. de Lecci&oacute;n</option>
                    <option value="name">Nombre de Lecci&oacute;n</option>
                </select>
                <input type="button" hidden="true" value="Buscar" id="btnSearch" />
            </span>
            <table id="dtgUnits">
                <thead>
                    <tr>
                        <th sort="no">Clave</th>
                        <th sort="nombre">Nombre</th>
                        <th sort="cantidad de lecciones">Cantidad de Lecciones</th>
                        <th sort="materia">Materia</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot class="nav">
                    <tr>
                        <td colspan="5">
                            <div class="pagination"></div>
                            <div class="paginationTitle"></div>
                            <div class="selectPerPage"></div>
                            <div class="status"></div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div id="JOptionPanel">
            <p></p>
        </div>
        <div id="result"></div>
    </body>
</html>