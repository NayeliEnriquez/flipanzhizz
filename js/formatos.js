//***form_ausencua.php***/
function busca_empleado() {
    inp_num_empl = document.getElementById("inp_num_empl").value;
    //var codigo_press = event.which;//***event.keyCode
    //if (codigo_press === 13) {
        if (inp_num_empl != '') {
            var parametros={
                "inp_num_empl":inp_num_empl
            }
            
            $.ajax({
                data: parametros,
                url: "script/busca_empleado.php",
                type: "POST",
                beforeSend: function(){
                    $("#respose_srch_dtl_surt").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#respose_srch_dtl_surt").empty();
                    $("#resp_busca_empleado").empty();
                    $("#resp_busca_empleado").append(data);
                }
            })
        }
    //}
}
//***********************************************************
function frm_ausencias() {
    slc_formato = 'rh_solicitudes';
    let selected_rdo_ausencia = $("input[type='radio'][name='chk_ausencia']:checked");
    if (selected_rdo_ausencia.length > 0) {
        rdo_tipo_Val_ausencia = selected_rdo_ausencia.val();
    }

    let inp_finicial = document.getElementById('inp_finicial').value
    let inp_ffinal = document.getElementById('inp_ffinal').value
    if ((inp_finicial == '') || (inp_ffinal == '')) {
        alertify.warning('Favor de colocar una fecha inicial y final.')
        document.getElementById("inp_finicial").focus();
        return false;
    }

    let selected_rdo_goce = $("input[type='radio'][name='rdo_goce']:checked");
    if (selected_rdo_goce.length > 0) {
        rdo_tipo_Val_goce = selected_rdo_goce.val();
    }

    let txt_obs = document.getElementById('txt_obs').value
    if (txt_obs == '') {
        alertify.warning('Favor de colocar un comentario explicando la ausencia.')
        document.getElementById("txt_obs").focus();
        return false;
    }

    if (((rdo_tipo_Val_ausencia == '3') || (rdo_tipo_Val_ausencia == '4')) && (rdo_tipo_Val_goce == '1')) {
        //alert ('No puede haber goce de sueldo por amonestacion o suspencion')
        alertify.error('No puede haber goce de sueldo por suspención o amonestación');
        return false;
    }

    let inp_id_empleado = document.getElementById('inp_id_empleado').value
    let inp_id_depto = document.getElementById('inp_id_depto').value
    let inp_hr_in = document.getElementById('inp_hr_in').value
    let inp_hr_out = document.getElementById('inp_hr_out').value

    //let inp_file = append('inp_file', $('#inp_file')[0].files[0]);
    //var inp_file = $('#inp_file').prop("files")[0];
    let selected_rdo_permisos = $("input[type='radio'][name='chk_permisos']:checked");
    if (selected_rdo_permisos.length > 0) {
        rdo_tipo_Val_permisos = selected_rdo_permisos.val();
    }

    var parametros={
        "rdo_tipo_Val_ausencia":rdo_tipo_Val_ausencia,
        "inp_finicial":inp_finicial,
        "inp_ffinal":inp_ffinal,
        "rdo_tipo_Val_goce":rdo_tipo_Val_goce,
        "txt_obs":txt_obs,
        "inp_id_empleado":inp_id_empleado,
        "inp_id_depto":inp_id_depto,
        "inp_hr_in":inp_hr_in,
        "inp_hr_out":inp_hr_out,
        "rdo_tipo_Val_permisos":rdo_tipo_Val_permisos
    }

    if (confirm('Esta seguro de mandar esta solicitud?')) {
        $.ajax({
            data: parametros,
            url: "../php/frm_ausencias.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                alertify.warning('Procesando...')
                $("#btn_send").hide();
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                $("#btn_send").show();
            },
            success: function(data){
                console.log(data)
                //$("#btn_send").show();
                arr_data = data.split("|")
                response = arr_data[0]
                id_inserted = arr_data[1]
                btn_download = arr_data[2]
                if (response == 1) {
                    //alertify.success('Solicitud enviada satisfactoriamente.')
                    setTimeout(function(){
                        alertify.success('Descargando formato...');
                        download_formatos(slc_formato, id_inserted, inp_id_empleado);
                    }, 1000);
                    alert("Su folio de la solicitud es: "+id_inserted+"\nGuarde este numero para buscar el estatus de su solicitud.");
                    setTimeout(function(){
                        $("#response_empleado_aus").empty();
                        $("#response_empleado_aus").append(btn_download);
                    }, 3000);
                }else{
                    alertify.warning('Favor de intentarlo nuevamente o contactar a su administrador de sistema')
                }
            }
        })
    }
}
//***********************************************************
//***form_vacaciones.php***/
function busca_empleado_v(event) {
    inp_num_empl_v = document.getElementById("inp_num_empl_v").value;
    var codigo_press = event.which;//***event.keyCode
    if (codigo_press === 13) {
        if (inp_num_empl_v != '') {
            var parametros={
                "inp_num_empl_v":inp_num_empl_v
            }
            
            $.ajax({
                data: parametros,
                url: "script/busca_empleado_v.php",
                type: "POST",
                beforeSend: function(){
                    $("#respose_srch_dtl_surt_v").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#respose_srch_dtl_surt_v").empty();
                    $("#resp_busca_empleado_v").empty();
                    $("#resp_busca_empleado_v").append(data);
                }
            })
        }
    }
}
function busca_empleado_v2(event) {
    inp_num_empl_v = document.getElementById("inp_num_empl_v").value;
    //var codigo_press = event.which;//***event.keyCode
    //if (codigo_press === 13) {
        if (inp_num_empl_v != '') {
            var parametros={
                "inp_num_empl_v":inp_num_empl_v
            }
            
            $.ajax({
                data: parametros,
                url: "script/busca_empleado_v2.php",
                type: "POST",
                beforeSend: function(){
                    $("#respose_srch_dtl_surt_v").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#respose_srch_dtl_surt_v").empty();
                    $("#resp_busca_empleado_v").empty();
                    $("#resp_busca_empleado_v").append(data);
                }
            })
        }
    //}
}
const agregarFila = () => {
    const table_z = document.getElementById('tb_fechas_v')
    const rowCount_z = table_z.rows.length
    document.getElementById('tb_fechas_v').insertRow(-1).innerHTML = '<td><center>'+rowCount_z+'</center></td><td><center><input class="form-control" type="date" name="inp_arrdates[]"></center></td>'
}
  
const eliminarFila = () => {
    const table = document.getElementById('tb_fechas_v')
    const rowCount = table.rows.length
    
    if (rowCount <= 1)
        alertify.warning('No se puede eliminar el encabezado.')
    else
      table.deleteRow(rowCount -1)
}
//***********************************************************
function frm_ausencias_v(step) {
    let inp_id_empleado = document.getElementById('inp_id_empleado').value
    let inp_id_depto = document.getElementById('inp_id_depto').value
    
    let inp_finicial = document.getElementById('inp_finicial').value
    let inp_ffinal = document.getElementById('inp_ffinal').value
    if ((inp_finicial == '') || (inp_ffinal == '')) {
        alertify.warning('Favor de colocar una fecha inicial y final.')
        document.getElementById("inp_finicial").focus();
        return false;
    }

    let inp_hora_i = document.getElementById('inp_hora_i').value
    let inp_hora_s = document.getElementById('inp_hora_s').value
    if ((inp_hora_i == '') || (inp_hora_s == '')) {
        alertify.warning('Favor de colocar horario laboral.')
        document.getElementById("inp_hora_i").focus();
        return false;
    }
    let inp_f_hire = document.getElementById('inp_f_hire').value
    let txt_obs = document.getElementById('txt_obs').value
    
    //*** 1-> Solicitud || 2-> Confirmacion

    var parametros={
        "inp_id_empleado":inp_id_empleado,
        "inp_id_depto":inp_id_depto,
        "inp_finicial":inp_finicial,
        "inp_ffinal":inp_ffinal,
        "inp_hora_i":inp_hora_i,
        "inp_hora_s":inp_hora_s,
        "inp_f_hire":inp_f_hire,
        "txt_obs":txt_obs,
        "step":step
    }

    $.ajax({
        data: parametros,
        url: "../php/frm_ausencias_v.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            alertify.warning('Procesando...')
            $("#btn_send").hide();
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            $("#btn_send").show();
        },
        success: function(data){
            console.log(data)
            if(step == '1'){
                arr_dataa = data.split("|")
                dias_t = arr_dataa[0]
                v_inline = arr_dataa[1]
                v_ciclo = arr_dataa[2]
                if(v_ciclo == 'false') {
                    v_msg_ciclo = '';
                } else {
                    v_msg_ciclo = '\n\n***Bajo aprobación ya que solo cuenta con '+v_ciclo+' dias de vacaciones en este ciclo.';
                }
                if (v_inline == 'false') {
                    v_msg = 'Esta seguro de solicitar '+dias_t+' dia(s) de vacaciones?\n\n***Bajo aprobación del siguiente periodo.';
                }else{
                    v_msg = 'Esta seguro de solicitar '+dias_t+' dia(s) de vacaciones?'+v_msg_ciclo;
                }
                if(confirm(v_msg)){
                    setTimeout(function(){
                        frm_ausencias_v(2)
                    }, 1000);
                }else{
                    alertify.warning('Cancelando soilicitud...')
                    setTimeout(function(){
                        location.reload();  
                    }, 1000);
                }
            }else{
                arr_data = data.split("|")
                response = arr_data[0]
                id_inserted = arr_data[1]
                if(response == 1){
                    //alertify.success('Solicitud enviada satisfactoriamente.')
                    alert("Su folio de la solicitud es: "+id_inserted+"\nGuarde este numero para buscar el estatus de su solicitud.");
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }else{
                    alertify.warning('Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.')
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            }
            //console.log(data)
            $("#btn_send").show();
        }
    })
}
function frm_ausencias_vz(step) {
    slc_formato = 'rh_vacaciones';
    let inp_id_empleado = document.getElementById('inp_id_empleado').value
    let inp_id_depto = document.getElementById('inp_id_depto').value
    
    const table = document.getElementById('tb_fechas_v')
    const rowCount = table.rows.length
    if (rowCount <= 1) {
        alertify.warning('Favor de colocar al menos una fecha.')
        setTimeout(function(){
            agregarFila()
        }, 1000);
        return false;
    }else{
        //var inp_arrdates = $('input:text.inp_arrdates').serialize();
        var inp_arrdates = $('input[name="inp_arrdates[]"]').map(function(){ 
            return this.value; 
        }).get();
    }

    let inp_hora_i = document.getElementById('inp_hora_i').value
    let inp_hora_s = document.getElementById('inp_hora_s').value
    if ((inp_hora_i == '') || (inp_hora_s == '')) {
        alertify.warning('Favor de colocar horario laboral.')
        document.getElementById("inp_hora_i").focus();
        return false;
    }
    let inp_f_regreso = document.getElementById('inp_f_regreso').value
    if (inp_f_regreso == '') {
        alertify.warning('Favor de colocar la fecha en la que se reincorpora a sus labores.')
        document.getElementById("inp_f_regreso").focus();
        return false;
    }
    let inp_f_hire = document.getElementById('inp_f_hire').value
    let txt_obs = document.getElementById('txt_obs').value

    var chk_tot = 0;
    chk_lun = $('#chk_lun').is(":checked");
    if (chk_lun == true) {
        chk_tot++;
    }
    chk_mar = $('#chk_mar').is(":checked");
    if (chk_mar == true) {
        chk_tot++;
    }
    chk_mie = $('#chk_mie').is(":checked");
    if (chk_mie == true) {
        chk_tot++;
    }
    chk_jue = $('#chk_jue').is(":checked");
    if (chk_jue == true) {
        chk_tot++;
    }
    chk_vie = $('#chk_vie').is(":checked");
    if (chk_vie == true) {
        chk_tot++;
    }
    chk_sab = $('#chk_sab').is(":checked");
    if (chk_sab == true) {
        chk_tot++;
    }
    chk_dom = $('#chk_dom').is(":checked");
    if (chk_dom == true) {
        chk_tot++;
    }

    if (chk_tot <= 4) {
        alertify.warning('Favor de revisar sus dias laborales.')
        return false;
    }
    
    //*** 1-> Solicitud || 2-> Confirmacion

    var parametros={
        "inp_id_empleado":inp_id_empleado,
        "inp_id_depto":inp_id_depto,
        "inp_arrdates":inp_arrdates,
        "inp_f_regreso":inp_f_regreso,
        "inp_hora_i":inp_hora_i,
        "inp_hora_s":inp_hora_s,
        "chk_lun":chk_lun,
        "chk_mar":chk_mar,
        "chk_mie":chk_mie,
        "chk_jue":chk_jue,
        "chk_vie":chk_vie,
        "chk_sab":chk_sab,
        "chk_dom":chk_dom,
        "inp_f_hire":inp_f_hire,
        "txt_obs":txt_obs,
        "step":step
    }

    $.ajax({
        data: parametros,
        url: "../php/frm_ausencias_vz.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            alertify.warning('Procesando...')
            $("#btn_send").hide();
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            $("#btn_send").show();
        },
        success: function(data){
            console.log(data)
            if(step == '1'){
                arr_dataa = data.split("|")
                dias_t = arr_dataa[0]
                v_inline = arr_dataa[1]
                v_ciclo = arr_dataa[2]

                if (v_ciclo == 'NA') {
                    if (dias_t == '1') {
                        arr_datos = v_inline.split(",");
                        fecha_datos = arr_datos[0]
                        folio_datos = arr_datos[1]
                        alertify.error('El dia '+fecha_datos+' ya fue se encuentra registrado en una solicitud activa con el folio '+folio_datos+'.')
                    } else if (dias_t == '2') {
                        alertify.error('El dia de reincorporacion no puede ser la misma que uno de los dias de vacaciones, favor de revisar')
                    } else {
                        alertify.error('Tiene dias repetidos en su solicitud de vacaciones')
                    }
                    $("#btn_send").show();
                    return false;
                } else {
                    if(v_ciclo == 'false') {
                        v_msg_ciclo = '';
                    } else {
                        v_msg_ciclo = '\n\n***Bajo aprobación ya que solo cuenta con '+v_ciclo+' dias de vacaciones en este ciclo.';
                    }
                    if (v_inline == 'false') {
                        v_msg = 'Esta seguro de solicitar '+dias_t+' dia(s) de vacaciones?\n\n***Bajo aprobación del siguiente periodo.';
                    }else{
                        v_msg = 'Esta seguro de solicitar '+dias_t+' dia(s) de vacaciones?'+v_msg_ciclo;
                    }
                    if(confirm(v_msg)){
                        setTimeout(function(){
                            frm_ausencias_vz(2)
                        }, 1000);
                    }else{
                        alertify.warning('Cancelando solicitud...')
                        setTimeout(function(){
                            //location.reload();  
                        }, 1000);
                    }
                }
            }else{
                arr_data = data.split("|")
                response = arr_data[0]
                id_inserted = arr_data[1]
                btn_download = arr_data[2]
                if(response == 1){
                    //alertify.success('Solicitud enviada satisfactoriamente.')
                    setTimeout(function(){
                        alertify.success('Descargando formato...');
                        download_formatos(slc_formato, id_inserted, inp_id_empleado);
                    }, 1000);
                    alert("Su folio de la solicitud es: "+id_inserted+"\nGuarde este numero para buscar el estatus de su solicitud.");
                    setTimeout(function(){
                        $("#response_empleado_vaca").empty();
                        $("#response_empleado_vaca").append(btn_download);
                    }, 3000);
                }else{
                    alertify.warning('Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.')
                    /*setTimeout(function(){
                        location.reload();
                    }, 1000);*/
                }
            }
        }
    })
}
function frm_vacaciones_new(step) {
    slc_formato = 'rh_vacaciones';
    let inp_id_empleado = document.getElementById('inp_id_empleado').value
    let inp_id_depto = document.getElementById('inp_id_depto').value
    
    const table = document.getElementById('tb_fechas_v')
    const rowCount = table.rows.length
    if (rowCount <= 1) {
        alertify.warning('Favor de colocar al menos una fecha.')
        setTimeout(function(){
            agregarFila()
        }, 1000);
        return false;
    }else{
        var inp_arrdates = $('input[name="inp_arrdates[]"]').map(function(){ 
            return this.value; 
        }).get();
    }

    let inp_hora_i = document.getElementById('inp_hora_i').value
    let inp_hora_s = document.getElementById('inp_hora_s').value
    if ((inp_hora_i == '') || (inp_hora_s == '')) {
        alertify.warning('Favor de colocar horario laboral.')
        document.getElementById("inp_hora_i").focus();
        return false;
    }
    let inp_f_regreso = document.getElementById('inp_f_regreso').value
    if (inp_f_regreso == '') {
        alertify.warning('Favor de colocar la fecha en la que se reincorpora a sus labores.')
        document.getElementById("inp_f_regreso").focus();
        return false;
    }
    let inp_f_hire = document.getElementById('inp_f_hire').value
    let txt_obs = document.getElementById('txt_obs').value

    var chk_tot = 0;
    chk_lun = $('#chk_lun').is(":checked");
    if (chk_lun == true) {
        chk_tot++;
    }
    chk_mar = $('#chk_mar').is(":checked");
    if (chk_mar == true) {
        chk_tot++;
    }
    chk_mie = $('#chk_mie').is(":checked");
    if (chk_mie == true) {
        chk_tot++;
    }
    chk_jue = $('#chk_jue').is(":checked");
    if (chk_jue == true) {
        chk_tot++;
    }
    chk_vie = $('#chk_vie').is(":checked");
    if (chk_vie == true) {
        chk_tot++;
    }
    chk_sab = $('#chk_sab').is(":checked");
    if (chk_sab == true) {
        chk_tot++;
    }
    chk_dom = $('#chk_dom').is(":checked");
    if (chk_dom == true) {
        chk_tot++;
    }

    if (chk_tot <= 4) {
        alertify.warning('Favor de revisar sus dias laborales.')
        return false;
    }

    var parametros={
        "inp_id_empleado":inp_id_empleado,
        "inp_id_depto":inp_id_depto,
        "inp_arrdates":inp_arrdates,
        "inp_f_regreso":inp_f_regreso,
        "inp_hora_i":inp_hora_i,
        "inp_hora_s":inp_hora_s,
        "chk_lun":chk_lun,
        "chk_mar":chk_mar,
        "chk_mie":chk_mie,
        "chk_jue":chk_jue,
        "chk_vie":chk_vie,
        "chk_sab":chk_sab,
        "chk_dom":chk_dom,
        "inp_f_hire":inp_f_hire,
        "txt_obs":txt_obs,
        "step":step
    }

    $.ajax({
        data: parametros,
        url: "../php/frm_vacaciones_new.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            alertify.warning('Procesando...')
            $("#btn_send").hide();
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            $("#btn_send").show();
        },
        success: function(data){
            console.log(data)
            if(step == '1'){
                arr_dataa = data.split("|")
                dias_t = arr_dataa[0]
                v_inline = arr_dataa[1]
                v_ciclo = arr_dataa[2]

                if (v_ciclo == 'NA') {
                    if (dias_t == '1') {
                        arr_datos = v_inline.split(",");
                        fecha_datos = arr_datos[0]
                        folio_datos = arr_datos[1]
                        //alertify.warning('El dia '+fecha_datos+' ya fue se encuentra registrado en una solicitud activa con el folio '+folio_datos+'.')
                        alert('El dia '+fecha_datos+' ya fue se encuentra registrado en una solicitud activa con el folio '+folio_datos+'.')
                    } else if (dias_t == '2') {
                        alertify.warning('El dia de reincorporacion no puede ser la misma que uno de los dias de vacaciones, favor de revisar')
                    } else {
                        alertify.warning('Tiene dias repetidos en su solicitud de vacaciones, favor de revisar')
                    }
                    $("#btn_send").show();
                    return false;
                } else {
                    v_msg = 'Esta seguro de solicitar '+dias_t+' dia(s) de vacaciones?';
                    if(confirm(v_msg)){
                        setTimeout(function(){
                            frm_vacaciones_new(2)
                        }, 1000);
                    }
                }
            }else{
                arr_data = data.split("|")
                response = arr_data[0]
                id_inserted = arr_data[1]
                btn_download = arr_data[2]
                if(response == 1){
                    setTimeout(function(){
                        alertify.success('Descargando formato...');
                        download_formatos(slc_formato, id_inserted, inp_id_empleado);
                    }, 1000);
                    alert("Su folio de la solicitud es: "+id_inserted+"\nGuarde este numero para buscar el estatus de su solicitud.");
                    setTimeout(function(){
                        $("#response_empleado_vaca").empty();
                        $("#response_empleado_vaca").append(btn_download);
                    }, 3000);
                }else{
                    alertify.warning('Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.')
                }
            }
        }
    })
}
//***********************************************************
//***salida.php***/
function busca_empleado_s(event) {
    inp_num_empl_s = document.getElementById("inp_num_empl_s").value;
    //var codigo_press = event.which;//***event.keyCode
    //if (codigo_press === 13) {
        if (inp_num_empl_s != '') {
            var parametros={
                "inp_num_empl_s":inp_num_empl_s
            }
            
            $.ajax({
                data: parametros,
                url: "script/busca_empleado_s.php",
                type: "POST",
                beforeSend: function(){
                    $("#respose_srch_dtl_surt_s").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#respose_srch_dtl_surt_s").empty();
                    $("#resp_busca_empleado_s").empty();
                    $("#resp_busca_empleado_s").append(data);
                }
            })
        }
    //}
}
//***********************************************************
function frm_ausencias_s() {
    let slc_formato = 'rh_salida';
    let inp_id_empleado = document.getElementById('inp_id_empleado').value
    let inp_id_depto = document.getElementById('inp_id_depto').value
    let selected_rdo_inout = $("input[type='radio'][name='rdo_inout']:checked");
    if (selected_rdo_inout.length > 0) {
        selected_rdo_inout_Val = selected_rdo_inout.val();
    }
    let selected_rdo_asunto = $("input[type='radio'][name='rdo_asunto']:checked");
    if (selected_rdo_asunto.length > 0) {
        selected_rdo_asunto_Val = selected_rdo_asunto.val();
    }
    let selected_rdo_goce = $("input[type='radio'][name='rdo_goce']:checked");
    if (selected_rdo_goce.length > 0) {
        selected_rdo_goce_Val = selected_rdo_goce.val();
    }
    let inp_fecha_p = document.getElementById("inp_fecha_p").value;
    if (inp_fecha_p == '') {
        alertify.warning('Favor de colocar una fecha.')
        document.getElementById("inp_fecha_p").focus();
        return false;
    }
    let inp_hora_p = document.getElementById("inp_hora_p").value;
    if (inp_hora_p == '') {
        alertify.warning('Favor de colocar una hora.')
        document.getElementById("inp_hora_p").focus();
        return false;
    }
    let txt_obs = document.getElementById('txt_obs').value;
    if (txt_obs == '') {
        alertify.warning('Favor de colocar un comentario explicando la ausencia.')
        document.getElementById("txt_obs").focus();
        return false;
    }
    chk_reponer = $('#chk_reponer').is(":checked");
    let inp_hr_in_out = '';

    if (selected_rdo_goce_Val == '1') {
        if (selected_rdo_inout_Val == '1') {
            inp_hr_in_out = document.getElementById("inp_hr_in").value;
        }else{
            inp_hr_in_out = document.getElementById("inp_hr_out").value;
        }

        if (inp_hr_in_out == '') {
            alertify.warning('Favor de colocar el horario laboral.')
            return false;
        }
    }

    let inp_fhrepo = document.getElementById('inp_fhrepo').value;
    let inp_hrrepo = document.getElementById('inp_hrrepo').value;

    var parametros={
        "selected_rdo_inout_Val":selected_rdo_inout_Val,
        "selected_rdo_asunto_Val":selected_rdo_asunto_Val,
        "selected_rdo_goce_Val":selected_rdo_goce_Val,
        "inp_fecha_p":inp_fecha_p,
        "inp_hora_p":inp_hora_p,
        "txt_obs":txt_obs,
        "chk_reponer":chk_reponer,
        "inp_id_empleado":inp_id_empleado,
        "inp_id_depto":inp_id_depto,
        "inp_hr_in_out":inp_hr_in_out,
        "inp_fhrepo":inp_fhrepo,
        "inp_hrrepo":inp_hrrepo
    }

    if (confirm('Esta seguro de mandar esta solicitud?')) {
        $.ajax({
            data: parametros,
            url: "../php/frm_ausencias_s.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                alertify.warning('Procesando...')
                $("#btn_send").hide();
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                $("#btn_send").show();
            },
            success: function(data){
                console.log(data)
                //$("#btn_send").show();
                arr_data = data.split("|")
                response = arr_data[0]
                id_inserted = arr_data[1]
                btn_download = arr_data[2]
                if (response == 1) {
                    setTimeout(function(){
                        alertify.success('Descargando formato...');
                        download_formatos(slc_formato, id_inserted, inp_id_empleado);
                    }, 1000);
                    alert("Su folio de la solicitud es: "+id_inserted+"\nGuarde este numero para buscar el estatus de su solicitud.");
                    setTimeout(function(){
                        $("#response_empleado_s").empty();
                        $("#response_empleado_s").append(btn_download);
                    }, 3000);
                }else{
                    alertify.warning('Favor de intentarlo nuevamente o contactar a su administrador de sistema')
                }
            }
        })
    }
}
//***estatus_solicitud.php***
function frm_solicitud_sts() {
    var error = 0;
    slc_formato = document.getElementById('slc_formato').value
    if (slc_formato == '') {
        error = 1;
    }
    inp_folio = document.getElementById('inp_folio').value
    
    inp_num_empl_h = document.getElementById('inp_num_empl_h').value
    if (inp_num_empl_h == '') {
        error = 1;
    }

    if (error == 0) {
        var parametros={
            "slc_formato":slc_formato,
            "inp_folio":inp_folio,
            "inp_num_empl_h":inp_num_empl_h
        }
        $.ajax({
            data: parametros,
            url: "script/frm_solicitud_sts.php",
            type: "POST",
            beforeSend: function(){
                $("#response_frm_sol_sts").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado.");
            },
            success: function(data){
                console.log(data)
                $("#response_frm_sol_sts").empty();
                $("#response_frm_sol_sts").append(data);
            }
        })
    }
}
//***************************
//***public_formats/script/frm_solicitud_sts.php***
function download_formatos(slc_formato, inp_folio, inp_num_empl_h) {
   //alert(slc_formato+"\n"+inp_folio+"\n"+inp_num_empl_h)
   var parametros={
      "slc_formato":slc_formato,
      "inp_folio":inp_folio,
      "inp_num_empl_h":inp_num_empl_h
   }
   $.ajax({
      data: parametros,
      url: "script/download_formatos.php",
      type: "POST",
      beforeSend: function(){
         alertify.warning('Creando PDF...')
      },
      error: function(){
         alert("Error inesperado.");
      },
      success: function(data){
         /*$("#response_frm_sol_sts").empty();
         $("#response_frm_sol_sts").append(data);*/
         console.log(data)
         setTimeout(function(){
            window.open("script/"+data);
         }, 1000);
      }
   })
}
//*************************************************
//***public_formats/script/busca_empleado_v2.php***
function ver_vacaciones_dsp(num_empl) {
    var parametros={
        "num_empl":num_empl
    }
    $.ajax({
        data: parametros,
        url: "script/ver_vacaciones_dsp.php",
        type: "POST",
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            console.log(data)
            $("#response_vacas").empty();
            $("#response_vacas").append(data);
        }
    })
}
//*************************************************
//**********************FUNCION PIN***************************
function revisa_pin_sub(num_empl, fun_org){
    if (num_empl != '') {
        var parametros={
            "num_empl":num_empl
        }

        if (fun_org == 'tiempo_extra') {
            var el_url = "../public_formats/script/revisa_pin.php"
        } else {
            var el_url = "script/revisa_pin.php"
        }

        $.ajax({
            data: parametros,
            url: el_url,
            type: "POST",
            beforeSend: function(){
                alertify.warning('Validando PIN...')
            },
            error: function(){
                alert("Error inesperado.");
            },
            success: function(data){
                console.log(data)
                if (data == 1) {
                    inserta_pin(num_empl, fun_org);
                } else {
                    solicita_pin(num_empl, fun_org);
                }
            }
        })
    }
}

function revisa_pin(num_empl, event, fun_org){
    //num_empl = document.getElementById("inp_num_empl").value;
    var codigo_press = event.which;//***event.keyCode
    if (codigo_press === 13) {
        if (num_empl != '') {
            var parametros={
                "num_empl":num_empl
            }

            if (fun_org == 'tiempo_extra') {
                var el_url = "../public_formats/script/revisa_pin.php"
            } else {
                var el_url = "script/revisa_pin.php"
            }

            $.ajax({
                data: parametros,
                url: el_url,
                type: "POST",
                beforeSend: function(){
                    alertify.warning('Validando PIN...')
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    console.log(data)
                    if (data == 1) {
                        inserta_pin(num_empl, fun_org);
                    } else {
                        solicita_pin(num_empl, fun_org);
                    }
                }
            })
        }
    }
}

function inserta_pin(num_empl, fun_org) {
    var v_pin = prompt("Favor de colocar un PIN NUEVO de 4 digitos. \n Recuerde este PIN para futuras consultas.");
    if (v_pin.length != 4) {
        inserta_pin(num_empl, fun_org)
        return false;
    }
    var parametros={
        "num_empl":num_empl,
        "v_pin":v_pin
    }

    if (fun_org == 'tiempo_extra') {
        var el_url = "../public_formats/script/inserta_pin.php"
    } else {
        var el_url = "script/inserta_pin.php"
    }

    $.ajax({
        data: parametros,
        url: el_url,
        type: "POST",
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            console.log(data)
            if (data == 1) {
                alertify.warning('El valor ingresado no es numerico, registre solo valores numericos.')
                inserta_pin(num_empl, fun_org)
            } else {
                alertify.success('PIN registrado correctamente.')
                setTimeout(function(){
                    location.reload();
                }, 1000);
            }
        }
    })
}

function solicita_pin(num_empl, fun_org) {
    var v_pin = prompt("Favor de colocar su PIN de seguridad.");

    var parametros={
        "num_empl":num_empl,
        "v_pin":v_pin
    }

    if (fun_org == 'tiempo_extra') {
        var el_url = "../public_formats/script/solicita_pin.php"
    } else {
        var el_url = "script/solicita_pin.php"
    }
    
    $.ajax({
        data: parametros,
        url: el_url,
        type: "POST",
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            console.log(data)
            if (data == 1) {
                alertify.warning('PIN incorrecto, intentarlo nuevamente.')
                setTimeout(function(){
                    solicita_pin(num_empl, fun_org)
                }, 1000);
            } else {
                alertify.success('Correcto!.')
                console.log('FUN: '+fun_org)
                if (fun_org == 'busca_empleado') {
                    setTimeout(function(){
                        busca_empleado()
                    }, 500);
                }else if (fun_org == 'busca_empleado_s') {
                    setTimeout(function(){
                        busca_empleado_s()
                    }, 500);
                }else if (fun_org == 'busca_empleado_v2') {
                    setTimeout(function(){
                        busca_empleado_v2()
                    }, 500);
                }else if (fun_org == 'frm_solicitud_sts') {
                    setTimeout(function(){
                        frm_solicitud_sts()
                    }, 500);
                }else if (fun_org == 'tiempo_extra') {
                    setTimeout(function(){
                        busca_empleado_te()
                    }, 500);
                }
            }
        }
    })
}
//************************************************************
//********FORMATO DE TIEMPO EXTRA***********
function busca_empleado_te() {
    inp_num_empl_te = document.getElementById("inp_num_empl_te").value;
    if (inp_num_empl_te != '') {
        var parametros={
            "inp_num_empl_te":inp_num_empl_te
        }
        
        $.ajax({
            data: parametros,
            url: "script/busca_empleado_te.php",
            type: "POST",
            beforeSend: function(){
                $("#respose_srch_dtl_surt_te").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado.");
            },
            success: function(data){
                $("#respose_srch_dtl_surt_te").empty();
                $("#resp_busca_empleado_te").empty();
                $("#resp_busca_empleado_te").append(data);
            }
        })
    }
}

function frm_tiempo_extra() {
    inp_id_empleado = document.getElementById('inp_id_empleado').value
    inp_id_depto = document.getElementById('inp_id_depto').value
    inp_fecha_textra = document.getElementById('inp_fecha_textra').value
    slc_tot_te = document.getElementById('slc_tot_te').value
    inp_n_orden = document.getElementById('inp_n_orden').value
    if (inp_n_orden == '') {
        alertify.warning('Favor de colocar un numero de orden valido');
        document.getElementById("inp_n_orden").focus();
        return false;
    }

    var parametros={
        "inp_id_empleado":inp_id_empleado,
        "inp_id_depto":inp_id_depto,
        "inp_fecha_textra":inp_fecha_textra,
        "slc_tot_te":slc_tot_te,
        "inp_n_orden":inp_n_orden
    }

    $.ajax({
        data: parametros,
        url: "script/frm_tiempo_extra.php",
        type: "POST",
        beforeSend: function(){
            $("#response_empleado_te").html('<button class="w-100 btn btn-primary btn-lg" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Enviando...</button>');
            $("#btn_send_te").hide();
        },
        error: function(){
            alert("Error inesperado.");
            $("#response_empleado_te").empty();
            $("#btn_send_te").show();
        },
        success: function(data){
            $("#response_empleado_te").empty();
            //$("#response_empleado_te").html('<button class="w-100 btn btn-primary btn-lg" id="btn_send_te" disabled>Enviar registro</button>');
            //$("#btn_send_te").show();
            arr_data = data.split("|")
            response = arr_data[0]
            v_html = arr_data[1]
            if (response == '0') {
                alertify.warning('No se encontro el numero de orden, favor de intentar nuevamente.')
                $("#btn_send_te").show();
            } else {
                $("#response_empleado_te").html('<button class="w-100 btn btn-primary btn-lg" id="btn_send_te" disabled>Enviar registro</button>');
            }
            $("#response_te_send").empty();
            $("#response_te_send").append(v_html);
        }
    })
}

function clean_te() {
    location.reload();
}
//******************************************