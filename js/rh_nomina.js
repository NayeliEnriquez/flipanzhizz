//***modulos/nomina.php***
function men_nom(periodo) {
    switch (periodo) {
        case 'sem'://**Semanal
            $.ajax({
                url: "script_nom/men_nom_sem.php",
                type: "POST",
                success: function(data){
                    console.log(data);
                    $("#response_menu_nom").empty();
                    $("#response_menu_nom").append(data);
                }
            })
            break;
    
        case 'qui'://***Quincenal
            $.ajax({
                url: "script_nom/men_nom_qui.php",
                type: "POST",
                success: function(data){
                    console.log(data);
                    $("#response_menu_nom").empty();
                    $("#response_menu_nom").append(data);
                }
            })
            break;
    }
}
//************************
//***modulos/script_nom/men_nom_sem.php***
function fun_year_semana(year) {
    var parametros={
        "year":year
    }
    $.ajax({
        data: parametros,
        url: "script_nom/fun_year_semana.php",
        type: "POST",
        success: function(data){
            $("#response_year_semana").empty();
            $("#response_year_semana").append(data);
        }
    })
}

function obtener_semana() {
    slc_year = document.getElementById('slc_year').value
    slc_semana = document.getElementById('slc_semana').value
    slc_pla = document.getElementById('slc_pla').value

    var parametros={
        "slc_year":slc_year,
        "slc_semana":slc_semana,
        "slc_pla":slc_pla
    }

    $.ajax({
        data: parametros,
        url: "script_nom/obtener_semana.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_get_semana").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_get_semana").empty();
            $("#response_get_semana").append(data);
        }
    })
}
//****************************************
//***modulos/script_nom/obtener_semana.php***
function dtl_sem(recorrido, cve_emp, fecha_b) {
    alert(recorrido+'||'+cve_emp+'||'+fecha_b)
}
//*******************************************
//***modulos/sup_check.php***
function obtener_super_check() {
    slc_year = document.getElementById('slc_year').value
    slc_semana = document.getElementById('slc_semana').value
    slc_quincena = document.getElementById('slc_quincena').value
    slc_areas_p = document.getElementById('slc_areas_p').value

    if ((slc_semana == '') && (slc_quincena == '')) {
        alertify.warning('Favor de seleccionar una semana o quincena.')
        return false;
    }

    if ((slc_semana != '') && (slc_quincena != '')) {
        alertify.warning('Favor de seleccionar unicamente una semana o quincena.')
        return false;
    }

    if (slc_areas_p == '') {
        alertify.warning('Seleccionar un area.')
        return false;
    }

    var parametros={
        "slc_year":slc_year,
        "slc_semana":slc_semana,
        "slc_quincena":slc_quincena,
        "slc_areas_p":slc_areas_p
    }

    $.ajax({
        data: parametros,
        url: "script_super/obtener_super_check.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_super_check").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_super_check").empty();
            arr_data = data.split("||")
            var v_error = arr_data[0];
            var v_erdes = arr_data[1];
            if (v_error == 0) {
                if (v_erdes == 'A') {
                    alertify.warning('Session concluida por inactividad.')
                }else if (v_erdes == 'B') {
                    alertify.warning('No cuenta con departamento asignado a su cargo.')
                }
            } else {
                $("#response_super_check").append(data);
            }
        }
    })
}
function aprovar_super_check(str_emps) {
    slc_year = document.getElementById('slc_year').value
    slc_semana = document.getElementById('slc_semana').value
    slc_quincena = document.getElementById('slc_quincena').value
    slc_areas_p = document.getElementById('slc_areas_p').value

    v_confirma = 0;
    v_correo = "1";
    if (confirm('Esta seguro de enviar reporte al encargado del area?')) {
        v_confirma = 1;
    }else{
        if (confirm('Desea enviar el reporte a otro destinatario?')) {
            v_correo = prompt('Colocar correo electronico: ')
            v_confirma = 1;
        }
    }
    
    if (v_confirma == 1) {
        var parametros={
            "slc_year":slc_year,
            "slc_semana":slc_semana,
            "slc_quincena":slc_quincena,
            "slc_areas_p":slc_areas_p,
            "str_emps":str_emps,
            "v_correo":v_correo
        }
    
        $.ajax({
            data: parametros,
            url: "script_super/aprovar_super_check.php",
            type: "POST",
            success: function(data){
                console.log(data);
                alertify.success('Se envio el correo para la aprovacion de horas extra al encargado del area.');
                setTimeout(function(){
                    obtener_super_check()
                }, 2000);
            }
        })
    }
}
function descarga_excel_te(str_emps) {
    slc_year = document.getElementById('slc_year').value
    slc_semana = document.getElementById('slc_semana').value
    slc_quincena = document.getElementById('slc_quincena').value
    slc_areas_p = document.getElementById('slc_areas_p').value

    alertify.warning('Creando archivo de Excel...');
    setTimeout(function(){
        window.open("script_super/descarga_excel_te.php?slc_year="+slc_year+"&slc_semana="+slc_semana+"&slc_quincena="+slc_quincena+"&slc_areas_p="+slc_areas_p+"&str_emps="+str_emps);
    }, 4000);
}
function descarga_pdf_te(str_emps) {
    slc_year = document.getElementById('slc_year').value
    slc_semana = document.getElementById('slc_semana').value
    slc_quincena = document.getElementById('slc_quincena').value
    slc_areas_p = document.getElementById('slc_areas_p').value

    var parametros={
        "slc_year":slc_year,
        "slc_semana":slc_semana,
        "slc_quincena":slc_quincena,
        "slc_areas_p":slc_areas_p,
        "str_emps":str_emps
    }

    $.ajax({
        data: parametros,
        url: "script_super/descarga_pdf_te.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            alertify.warning('Creando PDF...')
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            window.open("script_super/"+data);
        }
    })
}
//***************************
//***modulos/script_super/obtener_super_check.php***
function fun_aprobar(dia_dia, slc_year, slc_semana, emp_code_b, f_recorre, Hora_normal, Hora_normal_end, Hora_extra, Hora_extra_end, hrs_nml, min_nml, hrs_ext, min_ext, shift_id, min_retardo, txt_te) {
    if(confirm("Esta seguro de aprobar este dia?")){
        var bio = document.getElementById("response_fun_aprobar_B_"+emp_code_b+"_"+f_recorre);

        inphin_ = document.getElementById("inphin_"+emp_code_b+"_"+f_recorre).value
        if (inphin_ == 0) {
            inphin_ = document.getElementById("inphin_read_"+emp_code_b+"_"+f_recorre).value
        }
        inphout_a = document.getElementById("inphout_"+emp_code_b+"_"+f_recorre).value
        inphout_b = document.getElementById("inphout_read_"+emp_code_b+"_"+f_recorre).value
        if (inphout_a != inphout_b) {
            inphout_ = inphout_a;
        }else{
            inphout_ = inphout_b;
        }
        inphinext_ = document.getElementById("inphinext_"+emp_code_b+"_"+f_recorre).value
        /*if (inphinext_ == 0) {
            inphinext_ = document.getElementById("inphinext_read_"+emp_code_b+"_"+f_recorre).value
        }*/

        inphoutext_a = document.getElementById("inphoutext_"+emp_code_b+"_"+f_recorre).value
        inphoutext_b = document.getElementById("inphoutext_read_"+emp_code_b+"_"+f_recorre).value
        if (inphoutext_a != inphoutext_b) {
            inphoutext_ = inphoutext_a;
        }else{
            inphoutext_ = inphoutext_b;
        }

        /*if ((Hora_extra == '') && (inphinext_ > 0)) {
            alertify.warning('NO se puede autorizar horas si el empleado no tuvo registros en el facial de TE.');
            return false;
        }*/

        let mensaje_te = prompt("Si tiene tiempo extra, colocar Area, producto y lote para futuras referencias.\nEjemplo (Enc lV, Amzuvag, 30352)", txt_te);

        var parametros={
            "dia_dia":dia_dia,
            "slc_year":slc_year,
            "slc_semana":slc_semana,
            "emp_code_b":emp_code_b,
            "f_recorre":f_recorre,
            "Hora_normal":Hora_normal,
            "Hora_normal_end":Hora_normal_end,
            "Hora_extra":Hora_extra,
            "Hora_extra_end":Hora_extra_end,
            "hrs_nml":hrs_nml,
            "min_nml":min_nml,
            "hrs_ext":hrs_ext,
            "min_ext":min_ext,
            "shift_id":shift_id,
            "min_retardo":min_retardo,
            "inphin_":inphin_,
            "inphout_":inphout_,
            "inphinext_":inphinext_,
            "inphoutext_":inphoutext_,
            "mensaje_te":mensaje_te
        }

        $.ajax({
            data: parametros,
            url: "script_super/fun_aprobar.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                alertify.message('Aprobando...')
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                $('#response_fun_aprobar_'+emp_code_b+'_'+f_recorre).hide();
                var obj = jQuery.parseJSON(data);
                bio.innerHTML = obj.msg_success;
                setTimeout(function(){
                    update_tot_horas_sem(slc_semana, slc_year, emp_code_b)
                }, 1000);
            }
        })
    }
}
function fun_modificar(emp_code, dia_dia, type_m) {
    if (type_m == 'a') {
        $("#tr_"+emp_code+"_"+dia_dia).show();
        $("#btn_"+emp_code+"_"+dia_dia).html("<center><button type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`"+emp_code+"`, `"+dia_dia+"`, `b`)'>Cancelar</button></center>");
    } else {
        document.getElementById("inphin_"+emp_code+"_"+dia_dia).value = '0'
        document.getElementById("inphout_"+emp_code+"_"+dia_dia).value = '0'
        document.getElementById("inphinext_"+emp_code+"_"+dia_dia).value = '0'
        document.getElementById("inphoutext_"+emp_code+"_"+dia_dia).value = '0'
        $("#tr_"+emp_code+"_"+dia_dia).hide();
        $("#btn_"+emp_code+"_"+dia_dia).html("<center><button type='button' class='btn btn-info btn-sm' onclick='fun_modificar(`"+emp_code+"`, `"+dia_dia+"`, `a`)'>Modificar</button></center>");
    }
}
function fun_aprob_all(emp_code, f_start, f_end, slc_semana, slc_year) {
    if (confirm("Esta seguro de aprobar todos los dias del empleado "+emp_code+"?")) {
        var parametros={
            "emp_code":emp_code,
            "f_start":f_start,
            "f_end":f_end,
            "slc_semana":slc_semana,
            "slc_year":slc_year
        }

        $.ajax({
            data: parametros,
            url: "script_super/fun_aprob_all.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                alertify.message('Aprobando...')
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                //var bio = document.getElementById("response_fun_aprobar_"+emp_code_b+"_"+dia_dia);
                console.log(data);

                arr_data_1 = data.split("||")
                var emp_code_r = arr_data_1[0];
                var response_arr = arr_data_1[1];

                arr_data_2 = response_arr.split(",");
                console.log(arr_data_2);

                //***Lunes***
                if (arr_data_2[1] != '1') {
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[0]).empty();
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[0]).append(arr_data_2[1]);
                }else{
                    alertify.warning(arr_data_2[0]+' ya cuenta con registro.')
                }
                //***Martes***
                if (arr_data_2[3] != '1') {
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[2]).empty();
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[2]).append(arr_data_2[3]);
                }else{
                    alertify.warning(arr_data_2[2]+' ya cuenta con registro.')
                }
                //***Miercoles***
                if (arr_data_2[5] != '1') {
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[4]).empty();
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[4]).append(arr_data_2[5]);
                }else{
                    alertify.warning(arr_data_2[4]+' ya cuenta con registro.')
                }
                //***Jueves***
                if (arr_data_2[7] != '1') {
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[6]).empty();
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[6]).append(arr_data_2[7]);
                }else{
                    alertify.warning(arr_data_2[6]+' ya cuenta con registro.')
                }
                //***Viernes***
                if (arr_data_2[9] != '1') {
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[8]).empty();
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[8]).append(arr_data_2[9]);
                }else{
                    alertify.warning(arr_data_2[8]+' ya cuenta con registro.')
                }
                //***Sabado***
                if (arr_data_2[11] != '1') {
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[10]).empty();
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[10]).append(arr_data_2[11]);
                }else{
                    alertify.warning(arr_data_2[10]+' ya cuenta con registro.')
                }
                //***Domingo***
                if (arr_data_2[13] != '1') {
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[12]).empty();
                    $("#response_fun_aprobar_"+emp_code_r+"_"+arr_data_2[12]).append(arr_data_2[13]);
                }else{
                    alertify.warning(arr_data_2[12]+' ya cuenta con registro.')
                }

                setTimeout(function(){
                    update_tot_horas_sem(slc_semana, slc_year, emp_code)
                }, 1000);
            }
        })
    }
}
function update_tot_horas_sem(slc_semana, slc_year, emp_code) {
    var bio_r = document.getElementById("response_htotales_"+emp_code+"_"+slc_year+"_"+slc_semana);
    slc_quincena = document.getElementById('slc_quincena').value

    var parametros={
        "slc_year":slc_year,
        "slc_semana":slc_semana,
        "emp_code":emp_code
    }

    if (slc_quincena != '') {
        $.ajax({
            data: parametros,
            url: "script_super/update_tot_horas_qui.php",
            type: "POST",
            error: function(){
                alert("Error en update_tot_horas_sem.");
            },
            success: function(data){
                console.log(data);
                var obje = jQuery.parseJSON(data);
                bio_r.innerHTML = obje.msg_success;
            }
        })
    }else{
        $.ajax({
            data: parametros,
            url: "script_super/update_tot_horas_sem.php",
            type: "POST",
            error: function(){
                alert("Error en update_tot_horas_sem.");
            },
            success: function(data){
                console.log(data);
                var obje = jQuery.parseJSON(data);
                bio_r.innerHTML = obje.msg_success;
            }
        })
    }
}
function new_horario(slc_semana, slc_year, emp_code, dia_dia, fecha_recorre) {
    if (confirm('Esta seguro de agregar un nuevo registro para el empleado '+emp_code+' el dia '+dia_dia+' ?')) {
        let horas_normales = prompt("Total de horas normales laboradas\nEjemplo (08:30) sin letras ni espacios.", "00:00");
        let horas_extra = prompt("Total de horas extra laboradas\nEjemplo (03:00) sin letras ni espacios.", "00:00");
        let mensaje_te = '';
        if (horas_extra != "00:00") {
            mensaje_te = prompt("Favor de colocar area, producto y lote para futuras referencias.\nEjemplo (Enc lV, Amzuvag, 30352)");
        }

        var parametros={
            "slc_year":slc_year,
            "slc_semana":slc_semana,
            "emp_code":emp_code,
            "dia_dia":dia_dia,
            "fecha_recorre":fecha_recorre,
            "horas_normales":horas_normales,
            "horas_extra":horas_extra,
            "mensaje_te":mensaje_te
        }

        $.ajax({
            data: parametros,
            url: "script_super/new_horario.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                $("#div_empty_"+emp_code+"_"+dia_dia).html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                $("#div_empty_"+emp_code+"_"+dia_dia).empty();
                $("#div_empty_"+emp_code+"_"+dia_dia).append(data);
            }
        })
    }
}
function editar_hrs_extra(dia_dia, emp_code) {
    //$('#response_fun_aprobar_B_'+emp_code+'_'+dia_dia).empty();
    $('#response_fun_aprobar_B_'+emp_code+'_'+dia_dia).hide();
    $('#response_fun_aprobar_'+emp_code+'_'+dia_dia).show();
}
//**************************************************
//***nomina.php***
function busca_nomina(event) {
    inp_num_empl = document.getElementById("inp_num_empl").value;
    var codigo_press = event.which;//***event.keyCode
    if (codigo_press === 13) {
        if (inp_num_empl != '') {
            var parametros={
                "inp_num_empl":inp_num_empl
            }
            
            $.ajax({
                data: parametros,
                url: "script_nom/busca_nomina.php",
                type: "POST",
                beforeSend: function(){
                    $("#response_menu_nom").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#response_menu_nom").empty();
                    $("#response_menu_nom").append(data);
                }
            })
        }
    }
}

function busca_info_nomina() {
    slc_dias = document.getElementById("slc_dias").value;
    inp_id_type = document.getElementById("inp_id_type").value;

    var parametros={
        "slc_dias":slc_dias,
        "inp_id_type":inp_id_type,
        "inp_id_empleado":inp_id_empleado
    }
    
    $.ajax({
        data: parametros,
        url: "script_nom/busca_info_nomina.php",
        type: "POST",
        beforeSend: function(){
            $("#response_get_nomina").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            $("#response_get_nomina").empty();
            $("#response_get_nomina").append(data);
        }
    })
}

function busqueda_rh() {
    inp_num_empl = document.getElementById("inp_num_empl").value;
    slc_deptos = document.getElementById("slc_deptos").value;
    var error = 0;

    if((inp_num_empl == '') && (slc_deptos == 0)){
        alertify.warning('Realiza una busqueda por empleado o por departamento.')
    }else if((inp_num_empl != '') && (slc_deptos != 0)){
        alertify.warning('Solo puede realizar una busqueda por empleado o departamento.')
    }else{
        var parametros={
            "inp_num_empl":inp_num_empl,
            "slc_deptos":slc_deptos
        }

        $.ajax({
            data: parametros,
            url: "script_nom/busqueda_rh.php",
            type: "POST",
            beforeSend: function(){
                $("#response_info_rh").empty();
                $("#response_info2_rh").empty();
                $("#response_busqueda_rh").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado.");
            },
            success: function(data){
                $("#response_busqueda_rh").empty();
                $("#response_busqueda_rh").append(data);
            }
        })
    }
}

function e_s_rh(num_empl, tipo_nomina) {
    var parametros={
        "num_empl":num_empl,
        "tipo_nomina":tipo_nomina
    }

    $.ajax({
        data: parametros,
        url: "script_nom/e_s_rh.php",
        type: "POST",
        beforeSend: function(){
            $("#response_info2_rh").empty();
            $("#response_info_rh").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            $("#response_info_rh").empty();
            $("#response_info_rh").append(data);
        }
    })
}

function buscar_check_semana(num_empl) {
    slc_semana = document.getElementById("slc_semana").value;
    slc_year = document.getElementById("slc_year").value;
    var parametros={
        "num_empl":num_empl,
        "slc_semana":slc_semana,
        "slc_year":slc_year
    }
    $.ajax({
        data: parametros,
        url: "script_nom/buscar_check_semana.php",
        type: "POST",
        beforeSend: function(){
            $("#response_info2_rh").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            $("#response_info2_rh").empty();
            $("#response_info2_rh").append(data);
        }
    })
}

function buscar_check_quincena(num_empl) {
    slc_quincena = document.getElementById("slc_quincena").value;
    slc_year = document.getElementById("slc_year").value;
    inp_dia_unico = document.getElementById("inp_dia_unico").value;
    if ((slc_quincena == '0') && (inp_dia_unico == '')) {
        alertify.warning('Favor de seleccionar una quincena o colocar una fecha especifica')
        return false;
    }

    var parametros={
        "num_empl":num_empl,
        "slc_quincena":slc_quincena,
        "slc_year":slc_year,
        "inp_dia_unico":inp_dia_unico
    }

    $.ajax({
        data: parametros,
        url: "script_nom/buscar_check_quincena.php",
        type: "POST",
        beforeSend: function(){
            $("#response_info2_rh").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            $("#response_info2_rh").empty();
            $("#response_info2_rh").append(data);
        }
    })
}

function vacaciones_rh(num_empl, tipo_nomina_str) {
    $("#resaponse_vacaciones_modal").empty();
}

function limpiar_pantalla() {
    document.getElementById("inp_num_empl").value = ''
    document.getElementById("slc_deptos").value = '0'
    $("#response_busqueda_rh").empty();
    $("#response_info_rh").empty();
    $("#response_info2_rh").empty();
}

function descarga_pdf_semana(v_empl, v_sem, v_year) {
    var parametros={
        "v_empl":v_empl,
        "v_sem":v_sem,
        "v_year":v_year
    }
    $.ajax({
        data: parametros,
        url: "script_nom/descarga_pdf_semana.php",
        type: "POST",
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            alertify.success('Creando y descargando PDF...')
            console.log(data)
            /*setTimeout(function(){
                window.open("script/"+data);
            }, 1000);*/
        }
    })
}

function busca_sem_dpto(n_semana, n_year, n_depto) {
    var parametros={
        "n_semana":n_semana,
        "n_year":n_year,
        "n_depto":n_depto
    }

    $.ajax({
        data: parametros,
        url: "script_nom/busca_sem_dpto.php",
        type: "POST",
        beforeSend: function(){
            $("#response_info_rh").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            $("#response_info_rh").empty();
            $("#response_info_rh").append(data);
            var nrows = 0
            $("table tbody tr").each(function() {
                nrows++;
            })
            /*let obtenerFila1 = document.getElementById("tr_1");
            let elementosFila1 = obtenerFila1.getElementsByTagName("td");
            console.log(elementosFila1[7].innerText);*/
            for (let index = 1; index <= nrows; index++) {
                //console.log(index);
                obtenerFila1 = document.getElementById("totales_"+index);
                //console.log(obtenerFila1.innerText);
                if (obtenerFila1.innerHTML == '<strong>0 horas</strong>') {
                    //console.log('CEROS');
                    $('#tr_'+index).hide(); //oculto mediante id
                }
                //elementosFila1 = obtenerFila1.getElementById("totales_"+index);
                //console.log(elementosFila1[7].innerText)
                //console.log(elementosFila1.innerHTML)
            }   
            //console.log(nrows);
            /*let obtenerDato = document.getElementsByTagName("td");
            console.log(obtenerDato.align-middle);*/
            /*for (let i=0; i<=nrows; i++) {
                console.log(obtenerDato[i].innerText);
            }*/
        }
    })
}

function download_formatos_ext(tipo) {
    slc_year = document.getElementById("slc_year").value;
    if (tipo == 1) {
        slc_deptos = document.getElementById("slc_deptos").value;
        slc_semana = document.getElementById("slc_semana").value;

        var parametros={
            "tipo":tipo,
            "slc_deptos":slc_deptos,
            "slc_year":slc_year,
            "slc_semana":slc_semana
        }
    } else {
        inp_num_empl = document.getElementById("inp_num_empl").value;
        slc_quincena = document.getElementById("slc_quincena").value;
        inp_dia_unico = document.getElementById("inp_dia_unico").value;

        var parametros={
            "tipo":tipo,
            "slc_year":slc_year,
            "inp_num_empl":inp_num_empl,
            "slc_quincena":slc_quincena,
            "inp_dia_unico":inp_dia_unico
        }
    }

    $.ajax({
        data: parametros,
        url: "script_nom/download_formatos_ext.php",
        type: "POST",
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            console.log(data);
            alertify.success('Descargando...')
            arr_data = data.split("|")
            var f_ini = arr_data[0];
            var f_fin = arr_data[1];
            var depto = arr_data[2];
            var emp_c = arr_data[3];
            setTimeout(function(){
                window.open("http://192.168.1.8/rh/"+depto+"/"+f_ini+"/"+f_fin+"/"+emp_c);
            }, 1000);
        }
    })
}
//**************************************************
function download_prenomina() {
    alertify.success('Descargando...')
    slc_deptos = document.getElementById("slc_deptos").value;
    slc_year = document.getElementById("slc_year").value;
    slc_semana = document.getElementById("slc_semana").value;

    setTimeout(function(){
        window.open("script_nom/download_prenomina.php?slc_deptos="+slc_deptos+"&slc_year="+slc_year+"&slc_semana="+slc_semana);
    }, 4000);
}




//*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
/*************************************************************************************** */
function pdf_mochilas(str_emps) {
   slc_year = document.getElementById('slc_year').value
   slc_semana = document.getElementById('slc_semana').value
   slc_quincena = document.getElementById('slc_quincena').value
   slc_areas_p = document.getElementById('slc_areas_p').value

   var parametros={
       "slc_year":slc_year,
       "slc_semana":slc_semana,
       "slc_quincena":slc_quincena,
       "slc_areas_p":slc_areas_p,
       "str_emps":str_emps
   }

   $.ajax({
       data: parametros,
       url: "script_super/pdf_mochilas.php",
       type: "POST",
       beforeSend: function(){
           //Texto de busqueda
           alertify.warning('Creando PDF...')
       },
       error: function(){
           alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
       },
       success: function(data){
           console.log(data);
           //window.open("script_super/"+data);
       }
   })
}