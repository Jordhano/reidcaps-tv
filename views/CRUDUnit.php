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
            var eUnit2 = null;
            $(document).ready(function (){
                $('#optbtnSearchBy').change(find);
                $('#txtSearchText').keyup(find);
                $('#btnSearch').click(find);
                $('#btnSearch').click();
                $('#FormUnit').dialog({autoOpen:false,modal:true,resizable:false});
                $('#JOptionPanel').dialog({autoOpen:false,modal:true,resizable:false});
                $('#btnNewUnit').click(function(){
                    clearFormUnit();
                    loadSubjects();
                    $('#FormUnit').dialog("option","title","Registro de Unidad");
                    $('#FormUnit').dialog("option","buttons",[{text:"Registrar",click:save},{text:"Cerrar",click:closeDialog}]);
                    $('#ExistUnit').hide();
                    $('#FormUnit').dialog("open");
                    
                    
                });
            });
            function closeDialog(){
                $(this).dialog("close");
                 clearFormUnit();
                eUnit=null;
                eUnit2=null;
            }
            function validation(txt,message){
                if (txt== null || txt.val().length == 0){
                    alertDialog("Error","El Campo " + message+" Se Encuentra Vacio",false,"");
                    txt.focus();
                    throw "Empty";
                }
            }
            function save(){
                try{
                    validation($('#txtIdUnit'), "No. de Unidad");
                    validation($('#txtNameUnit'),"Nombre de Unidad");
                    validation($('#dbSubject'),"Materia");
                    
                    $.ajax({data:{"operation":3,"unit":getJSONUnit()},
                        type:"POST",
                        url:"../controllers/controllerUnit.php"}).done(function (data){
                            if (data=="Completado"){
                                alertDialog("Registro Guardado",data,false,"");
                                $('#btnSearch').click();
                                $('#FormUnit').dialog("close");
                                clearFormUnit();
                            }else{
                                alertDialog("Error Registrando",data,false,"");
                                $('#txtIdUnit').focus();
                            }
                        });
                }catch(err){}
            }
            function clearFormUnit(){
                $('#txtIdUnit').val("");
                $('#txtNameUnit').val("");
                $('#txtLessonsUnits').val("");
            }
            function getJSONUnit(){
                var JSONUnit={
                    id:$('#txtIdUnit').val(),
                    nombre:$('#txtNameUnit').val(),
                    materia:$('#dbSubject option:selected').val()
                };
                return JSONUnit;
            }
            
            function loadSubjects(){
                $('#dbSubject').html("");
                $.ajax({data:{"operation":6,"camp":"idSubject","operator":"LIKE","parameter":"","multiple":"Y"},
                        type:"POST",
                        url: "../controllers/controllerSubject.php"
                    }).done(function (data){
                        $("#dbSubject").append(data);
                    });
            }
            
            function find(){
                $.ajax({data:{"operation":1,"camp":$("#optbtnSearchBy option:selected").val(),"operator":"LIKE","parameter":$("#txtSearchText").val(),"multiple":"Y"},
                        type:"POST",
                        url: "../controllers/controllerUnit.php"
                        }).done(function(data){
                            $('#dtgUnits tbody').html(data);
                            $('#dtgUnits').jTPS({perPages:[5,50,'TODOS'],scrollStep:1,scrollDelay:30});
                            $('#dtgUnits tbody tr:not(.stubCell)').bind('mouseover mouseout',
                            function (e) {
                                e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                            });
                            $('#dtgUnits tbody tr:not(.stubCell)').click(function(data){
                                loadSubjects();
                                search($('.hilightRow.id').html());
                            });
                        });
            }
            
            function search(data){
                
                $.ajax({data:{"operation":2,"camp":"idUnit","operator":"=","parameter":data,"multiple":"N"},
                    type:"POST",
                    url: "../controllers/controllerUnit.php"}).done(function (data){
                        unit = jQuery.parseJSON(data);
                        
                        eUnit= unit.id;
                        eUnit2= unit.idSubject;
                        
                        $('#txtIdUnit').val(unit.id);
                        $('#txtNameUnit').val(unit.name);
                        $('#dbSubject').val(unit.idSubject);
                        $('#txtLessonsUnits').val(unit.lessons);
                        
                        $('#ExistUnit').show();
                        
                        $('#FormUnit').dialog("option","title", "Unidad: "+ unit.id);
                        
                        $('#FormUnit').dialog("option","buttons",[{text:"Actualizar",click:editValidation},{text:"Eliminar",click:function(){alertDialog("Eliminar","Desea Eliminar la Unidad: "+eUnit,true,"deleteUnit");}},{text:"Cerrar",click:closeDialog}]);
                        
                        $('#FormUnit').dialog("open");
                        
                    });
            }
            
            function editUnit(){
            $.ajax({data: {"operation":4,"unit":getJSONUnit(),"clave":{"1":eUnit,"2":eUnit2}},
                    type: "POST",
                    url: "../controllers/controllerUnit.php"
                }).done(function (data){
                    if (data=="Completado"){
                        alertDialog("Registro Actualizado",data,false,"");
                        $('#btnSearch').click();
                        $('#FormUnit').dialog("close"); 
                        cleanFormSubject();
                    }else{
                        alertDialog("Error Actualizando Registro",data,false,"");
                        $('#txtIdSubject').focus();
                    }    
                });
            }
            function editValidation(){
           
                try{
                    validation($('#txtIdUnit'),"Clave de Asignatura");
                    validation($('#txtNameUnit'),"Nombre de Asignatura");
                    validation($('#dbSubject'),"Materia");
                    alertDialog("Actualizar","Desea Actualizar los datos",true,"editUnit");
                    
                }catch(err){}  
            }
            
            function alertDialog(title,message,buttons,functionName){
                var response=null;
                $("#JOptionPanel p").html(message);
                $('#JOptionPanel').dialog("option","title", title);
                if (buttons){
                    $('#JOptionPanel').dialog("option","buttons",[{text:"Aceptar",click:function(){var num = eval(functionName+'()');$(this).dialog("close");}},{text:"Cancelar",click:function(){$(this).dialog("close");}}]);
                }else{
                    $('#JOptionPanel').dialog("option","buttons",[{text:"Ok",click:function(){$(this).dialog("close");}}]);
                }
                $('#JOptionPanel').dialog("open");
            }
            
            function deleteUnit(){
                $.ajax({data: {"operation":5,"clave":{"1":eUnit,"2":eUnit2}},
                    type: "POST",
                    url: "../controllers/controllerUnit.php"
                }).done(function (data){
                   
                    if (data=="Completado"){
                        alertDialog("Registro Eliminado",data,false,"");
                        $('#btnSearch').click();
                        $('#FormUnit').dialog("close"); 
                        cleanFormSubject();
                    }else{
                      alertDialog("Error Al Parecer El Registro No Esxiste",data,false,"");
                    }    
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
            <label for="dbSubject">Asignatura: </label>
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
                    <!--<option value="idLeccion">No. de Lecci&oacute;n</option> -->
                    <option value="name">Nombre de Lecci&oacute;n</option>
                    <option value="idSubject">Nombre de Asignatura</option>
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