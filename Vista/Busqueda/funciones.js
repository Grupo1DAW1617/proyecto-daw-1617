function addFecha(v){
    if(v == "fecha"){
        $(document).ready(function(){
            $("#fecha div").remove();
            $("#fecha").append("<div class='col-sm-6'>" +
                "<input type='date' name='fecha' required/>" +
                "</div>");

        });
    }else{
        $(document).ready(function(){
            $("#fecha div").remove();
            $("#fecha").append("<div class='col-sm-4'>" +
                "<label>Desde:</label>&nbsp;" +
                "<input type='date' name='fechaIni' required/>" +
                "</div>" +
                "<div class='col-sm-4'>" +
                "<label>Hasta:</label>" +
                "<input type='date' name='fechaFin' required/></div>");
        });
    }
}

function recibir(u){
    var datos = $.ajax({
        url: u,
        type: "post",
        dataType: "text",
        async: false
    }).responseText;

    return datos;
}

function recibirEnviando(d, u){
    var datos = $.ajax({
        data: {data : d},
        url: u,
        type: "post",
        dataType: "text",
        async:false
    }).responseText;

    return datos;
}

$(document).ready(function(){
    $("[name=opEmp]").click(function(){
        var datos = recibir("http://192.168.33.10/Vista/Busqueda/cargarEmpresas.php");
        $("#empresas div").remove();
        $("#empresas").append(datos);

        var countCheckedEmp = function(){
            var n = $(".es input[type=checkbox]:checked").length;
            if(n>0){
                $(".cen").css("display", "inline");
            }else{
                $(".cen [type=radio]").removeAttr("checked");
                $("#centros div").remove();
                $("#tiposTrabajadores div").remove();
                $("#trabajadores div").remove();
                $("#estados div").remove();
                $(".cen").css("display", "none");
                $(".per").css("display", "none");
                $(".tra").css("display", "none");
                $(".est").css("display", "none");
            }
            if($(".cen input[name=opCentro]:radio").is(":checked")){
                $("#centros div").remove();
                addCentro();
                $("#tiposTrabajadores div").remove();
                $(".est").css("display", "none");
            }
        };
        //countChecked();
        $(".es input[type=checkbox]" ).on("click", countCheckedEmp);
    });
});

function addCentro(){
    $("#centros div").remove();
    var selected = [];
    var n=0;
    $('.es input[type=checkbox]').each(function(){
        if(this.checked){
            selected[n] = $(this).val();
            n++;
        }
    });

    if(selected.length != 0){
        var array = JSON.stringify(selected);
        var datos = recibirEnviando(array, "http://192.168.33.10/Vista/Busqueda/cargarCentros.php");
        if(datos != ""){
            $("#estados div").remove();
            $("#trabajadores div").remove();
            $("#centros").append(datos);
            $(".tra [type=radio]").removeAttr("checked");
            $(".est [type=radio]").removeAttr("checked");
            $(".tra").css("display", "none");
            $(".per").css("display", "none");
        }else{
            $("#estados div").remove();
            $("#trabajadores div").remove();
            $("#tiposTrabajadores div").remove();
            $(".tra").css("display", "none");
            $(".est").css("display", "none");
            $(".per").css("display", "none");
            smoke.signal('No hay ning&uacute;n centro en esa empresa', function (e) {null;}, { duration: 2000 } );
        }
    }else{
        $("#tiposTrabajadores div").remove();
    }

    var countCheckedCen = function(){
        var n = $(".cs input[type=checkbox]:checked").length;
        if(n>0){
            $(".tra").css("display", "inline");
        }else{
            $(".est [type=radio]").removeAttr("checked");
            $(".tra [type=radio]").removeAttr("checked");
            $("#estados div").remove();
            $("#trabajadores div").remove();
            $("#tiposTrabajadores div").remove();
            $(".tra").css("display", "none");
            $(".est").css("display", "none");
        }
        if($(".tra input[name=opTrabajador]:radio").is(":checked")){
            $("#trabajadores div").remove();
            addTrabajador();
            $(".est [type=radio]").removeAttr("checked");
        }
    };
    $(".cs input[type=checkbox]" ).on("click", countCheckedCen);
}

function addTrabajador(){
    $("#trabajadores div").remove();
    var selectedCen = [];
    var n=0;
    $('.cs input[type=checkbox]').each(function(){
        if(this.checked){
            selectedCen[n] = $(this).val();
            n++;
        }
    });

    var selectedTipo = [];
    var nu=0;
    $('.ps input[type=checkbox]').each(function(){
        if(this.checked){
            selectedTipo[nu] = $(this).val();
            nu++;
        }
    });

    if(selectedCen.length != 0 && selectedTipo.length != 0){
        var arrayBi = [selectedCen, selectedTipo];
        var array = JSON.stringify(arrayBi);
        var datos = recibirEnviando(array, "http://192.168.33.10/Vista/Busqueda/cargarTrabajadores.php");
        if(datos != ""){
            $(".est [type=radio]").removeAttr("checked");
            $("#estados div").remove();
            $(".est").css("display", "none");
            $("#trabajadores").append(datos);
        }else{
            $("#estados div").remove();
            $(".est").css("display", "none");
            smoke.signal('No hay ning&uacute;n trabajador en ese centro', function (e) {null;}, { duration: 2000 } );
        }
    }

    var countCheckedTra = function(){
        var n = $(".ts input[type=checkbox]:checked").length;
        if(n>0){
            $(".est").css("display", "inline");
        }else{
            $(".est [type=radio]").removeAttr("checked");
            $("#estados div").remove();
            $(".est").css("display", "none");
        }

        if($(".est input[name=opEstado]:radio").is(":checked")){
         $("#estados div").remove();
         addEstado();
         }
    };
    $(".ts input[type=checkbox]" ).on("click", countCheckedTra);
}

function addTipoTrabajador(){
    $("#tiposTrabajadores div").remove();
    var datos = recibir("http://192.168.33.10/Vista/Busqueda/cargarTiposTrabajadores.php");
    $("#tiposTrabajadores").append(datos);
    addTrabajador();

    var countCheckedPer = function(){
        var n = $(".ps input[type=checkbox]:checked").length;
        if(n>0){
            addTrabajador();
            //$(".est").css("display", "inline");
        }else{
            $("#trabajadores div").remove();
            $("#estados div").remove();
            $(".est").css("display", "none");

        }
        if($(".est input[name=opEstado]:radio").is(":checked")){
         $("#estados div").remove();
         //addEstado();
         }
    };
    $(".ps input[type=checkbox]" ).on("click", countCheckedPer);
}

function addEstado(){
    var datos = recibir("http://192.168.33.10/Vista/Busqueda/cargarEstados.php");
    $("#estados div").remove();
    $("#estados").append(datos);
}

function addEstadoVacas(){
    $(document).ready(function(){
        $("#estados div").remove();
        $("#estados").append("<div class='col-sm-4'>" +
        "<label>Aprobado:</label>&nbsp;" +
        "<input type='checkbox' name='estado[]' value='A'/>" +
        "</div>" +
        "<div class='col-sm-4'>" +
        "<label>Disfrutado:</label>&nbsp;" +
        "<input type='checkbox' name='estado[]' value='D'/>" +
        "</div>"+
        "<div class='col-sm-4'>" +
        "<label>Solicitado:</label>&nbsp;" +
        "<input type='checkbox' name='estado[]' value='S'/>" +
        "</div>");
    });
}

function addEstadoVacasPrecon(){
    $(document).ready(function(){
        $("#estados div").remove();
        $("#estados").append("<div class='col-sm-4'>" +
            "<label>Previstas:</label>&nbsp;" +
            "<input type='checkbox' name='estado[]' value='A'/>" +
            "</div>" +
            "<div class='col-sm-4'>" +
            "<label>Consumidas:</label>&nbsp;" +
            "<input type='checkbox' name='estado[]' value='D'/>" +
            "</div>");
    });
}

function validar(){
    $(document).ready(function() {
        var n = $(".es input[type=checkbox]:checked").length;
        if (n < 1) {
            smoke.signal('Debes seleccionar al menos una empresa', function (e) {
                null;
            }, {duration: 10000});
        }
    });
}

/*function addEmpresa(){
    $.getJSON("cargarEmpresas.php", function (data) {
        $.each(data, function(k, v) {
            //var objeto = JSON.parse(v.nombre);
            $("#empresas").append("<div class='col-sm-5'><label>"+v.nombre+"</label>" +
                "<input type='checkbox' name='empresa' value='"+v.id+"'/></div>");
        });
    });

    $.ajax({
        url: "cargarEmpresas.php",
        dataType: "json",
        success : function(datos) {
            if(Object.prototype.toString.call(datos) === '[object Array]' ) {
                $.each(datos, function(k, v) {
                    $("#empresas").append("<div class='col-sm-5'><label>"+v.toString()+"</label>" +
                        "<input type='checkbox' name='empresa' value='"+v.id+"'/></div>");
                });
            } else if ( Object.prototype.toString.call(datos) === '[object Object]' ) {
                alert( 'Objeto!' );
            }
        },
        error : function(xhr, status) {
            alert('Disculpe, existió un problema' + xhr + " " + status);
        },
        complete : function(xhr, status) {
            alert('Petición realizada');
        }
    });
}*/
