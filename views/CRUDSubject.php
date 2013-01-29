<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script  src="../js/jquery-1.9.0.js"> </script>
        <script src="../js/jTPS.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/jTPS.css"/>
        <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.10.0.custom.min.css" />
        <script src="../js/jquery-ui-1.10.0.custom.min.js"></script>
        <script>
            var eSubject=null;
            var $ready = null;
            
            $(document).ready(function (){
               /* $('#btnSave').click(function() {
                    try{
                        validation($('#txtIdSubject'),"Clave de Asignatura");
                        validation($('#txtNameSubject'),"Nombre de Asignatura");
                        save();
                    }catch(err){}
                });*/
                $('#optbtnSearchBy').change(find);
                $('#txtSearchText').keyup(find);
                $('#btnSearch').click(find);
                $('#btnSearch').click();
                $('#FormSubject').dialog({autoOpen: false,modal : true, resizable:false});
                $('#JOptionPanel').dialog({autoOpen:false, modal:true, resizable:false});
                $('#btnNewSubject').click(function(){
                    cleanFormSubject();
                    $('#FormSubject').dialog("option","title", "Registro de asignatura");
                    $('#FormSubject').dialog("option","buttons",[{text:"Registrar",click:save},{text:"Cerrar",click:closeDialog}]);
                    $('#ExistSubject').hide();
                    $('#FormSubject').dialog("open");
                });
            });
            
            function closeDialog(){
                $(this).dialog("close");
                 cleanFormSubject();
                 eSubject=null;
            }
            function validation(txt,message){
                if (txt.val().length == 0){
                    alertDialog("Error","El Campo " + message+" Se Encuentra Vacio",false,"");
                    txt.focus();
                    throw "Empty";
                }
            }
            
            function save(){
                try{
                    validation($('#txtIdSubject'),"Clave de Asignatura");
                    validation($('#txtNameSubject'),"Nombre de Asignatura");
                    
                    $.ajax({data: {"operation":3,"subject":getJSONSubject()},
                        type: "POST",
                        url: "../controllers/controllerSubject.php"
                    }).done(function (data){
                        if (data=="Completado"){
                            alertDialog("Registro Guardado",data,false,"");
                            $('#btnSearch').click();
                            $('#FormSubject').dialog("close"); 
                            cleanFormSubject();
                        }else{
                            alertDialog("Error Resgitrando",data,false,"");
                            $('#txtIdSubject').focus();
                        }    
                    });
                    
                }catch(err){}
            }
            function cleanFormSubject(){
                $('#txtIdSubject').val("");
                $('#txtNameSubject').val("");
                $('#txtLastLesson').val("");
                $('#txtDateLastLesson').val("");
                $('#txtUnitsSubject').val("");
            }
            function getJSONSubject(){
                var JSONSubject={
                    clave:$('#txtIdSubject').val(),
                    nombre:$('#txtNameSubject').val()                  
                };
                return JSONSubject;
            }
            
            function find(){ // For the data grid
                $.ajax({data:{"operation":1,"camp":$("#optbtnSearchBy option:selected").val(),"operator":"LIKE","parameter":$('#txtSearchText').val(),"multiple":"Y"},
                        type:"POST",
                        url: "../controllers/controllerSubject.php"
                    }).done(function (data){
                        $('#dtgMaterias tbody').html(data);
                        $('#dtgMaterias').jTPS({perPages:[5,50,'TODOS'],scrollStep:1,scrollDelay:30});
                        $('#dtgMaterias tbody tr:not(.stubCell)').bind('mouseover mouseout',
                            function (e) {
                                e.type == 'mouseover' ? $(this).children('td').addClass('hilightRow') : $(this).children('td').removeClass('hilightRow');
                        });
                        $('#dtgMaterias tbody tr:not(.stubCell)').click(function(data){
                            search($('.hilightRow.id').html());
                        });
                    });
            }
            
            function search(data){
                $.ajax({data:{"operation":2,"camp":"idSubject","operator":"=","parameter":data,"multiple":"N"},
                    type:"POST",
                    url: "../controllers/controllerSubject.php"}).done(function (data){
                        subject = jQuery.parseJSON(data);
                        eSubject= subject.id;
                        
                        $('#txtIdSubject').val(subject.id);
                        $('#txtNameSubject').val(subject.name);
                        $('#txtLastLesson').val(subject.lastLesson==0?"No Iniciada":subject.lastLesson);
                        $('#txtDateLastLesson').val(subject.lastLesson==0?"No Iniciada":subject.date);
                        $('#txtUnitsSubject').val(subject.units==0?"No Tiene":subject.units);
                        
                        $('#FormSubject').dialog("option","title", "Asignatura: "+ subject.id);
                        $('#FormSubject').dialog("option","buttons",[{text:"Actualizar",click:editValidation},{text:"Eliminar",click:function(){alertDialog("Eliminar","Desea Eliminar la Materia: "+eSubject,true,"deleteSubject");}},{text:"Cerrar",click:closeDialog}]);
                        $('#ExistSubject').show();
                        $('#FormSubject').dialog("open");
                        
                    });
            }
            function editSubject(){
                $.ajax({data: {"operation":4,"subject":getJSONSubject(),"clave":eSubject},
                    type: "POST",
                    url: "../controllers/controllerSubject.php"
                }).done(function (data){
                    if (data=="Completado"){
                        alertDialog("Registro Actualizado",data,false,"");
                        $('#btnSearch').click();
                        $('#FormSubject').dialog("close"); 
                        cleanFormSubject();
                    }else{
                        alertDialog("Error Actualizando Registro",data,false,"");
                        $('#txtIdSubject').focus();
                    }    
                });
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
            function editValidation(){
                try{
                    validation($('#txtIdSubject'),"Clave de Asignatura");
                    validation($('#txtNameSubject'),"Nombre de Asignatura");
                    alertDialog("Actualizar","Desea Actualizar los datos",true,"editSubject");
                    
                }catch(err){}  
            }
            function deleteSubject(){
                $.ajax({data: {"operation":5,"clave":eSubject},
                    type: "POST",
                    url: "../controllers/controllerSubject.php"
                }).done(function (data){
                   
                    if (data=="Completado"){
                        alertDialog("Registro Eliminado",data,false,"");
                        $('#btnSearch').click();
                        $('#FormSubject').dialog("close"); 
                        cleanFormSubject();
                    }else{
                      alertDialog("Error Al Parecer El Registro No Esxiste",data,false,"");
                    }    
                });
            }
            
        </script>
        
        <title>Manejador de Asignatura</title>
    </head>
    <body>
       
        <h1>Registro de Asignatura</h1>
        <div id="OptionsSubject">
            <input type="button" value="Agregar Materia" id="btnNewSubject" />
        </div>
        <div id="FormSubject">
            <img src="../resources/images/1359171448_lessons.png"/><br/>
            <label for="txtIdSubject">Clave: </label>
            <input type="text" id="txtIdSubject" /> <br/>
            <label for="txtNameSubject">Nombre: </label>
            <input type="text" id="txtNameSubject"/> <br/>
            
            <div id="ExistSubject">
                <label for="txtLastLesson">Ultima Lecci&oacute;n: </label>
                <input type="text" id="txtLastLesson" readonly /> <br/>
                <label for="txtDateLastLesson">Fecha de La Ultima Lecci&oacute;n: </label>
                <input type="text" id="txtDateLastLesson" readonly/> <br/>
                <label for="txtUnitsSubject" >Cantidad de Unidades: </label>
                <input type="text" id="txtUnitsSubject" readonly /> <br/>
            </div>            
            <!--
            <input type="button" value="Guardar" id="btnSave"/>
            <input type="button" value="Cancelar" id="btnCancel"/>
            -->
        </div>
        
        <br/><br/><br/>
        <div id="FindSubject">
            <span>
                <label for="txtSearchText">Buscar: </label>
                <input type="text" id="txtSearchText" />
                <select id="optbtnSearchBy" >
                    <option value="idSubject">Clave de Materia</option>
                    <option value="name">Nombre de Materia</option>
                </select>
                <input type="button" hidden="true" value="Buscar" id="btnSearch" />
            </span>
            <table id="dtgMaterias">
                <thead>
                    <tr>
                        <th sort="clave">Clave</th>
                        <th sort="nombre">Nombre</th>
                        <th sort="ultima leccion">Ultima Lecci&oacute;n</th>
                        <th sort="fecha ultima leccion">Fecha de La Ultima Lecci&oacute;n</th>
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