//*** */
function login_btn() {
    inp_user_rh = document.getElementById('inp_user_rh').value
    if (inp_user_rh == '') {
        alertify.warning('Colocar un usuario valido')
        document.getElementById("inp_user_rh").focus();
        return false;
    }
	inp_rh_Password = document.getElementById('inp_rh_Password').value
    if (inp_rh_Password == '') {
        alertify.warning('Colocar un password')
        document.getElementById("inp_rh_Password").focus();
        return false;
    }
    //chk_remember = $('#chk_remember').is(":checked");

	var parametros={
		"inp_user_rh":inp_user_rh,
		"inp_rh_Password":inp_rh_Password
	}

	$.ajax({
		data: parametros,
		url: "php/login_btn.php",
		type: "POST",
		beforeSend: function(){
			$("#response_login").html('<center><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></center>');
            $("#btn_login").hide();
		},
		error: function(){
			alert("Error inesperado.");
		},
		success: function(data){
            console.log(data);
            /*$("#response_login").append(data);
            $("#btn_login").show();
            return false;*/
            arr_data = data.split("|")
            response = arr_data[0]
            id_login = arr_data[1]
			$("#response_login").empty();
            $("#btn_login").show();
            if (response == '1') {
                alertify.success('Bienvenido!.')
                setTimeout(function(){
                    window.location.href = "modulos/principal.php";
                }, 1000);
            }else if(response == '3'){
                window.location.href = "new_pass.php?id_login="+id_login;
            }else{
                alertify.warning('Favor de revisar su usuario y/o contraseña')
            }
			//$("#response_login").append(data);
		}
	})
}
//***sistmea.php***
function nuevo_usuario() {
    let inp_namefull = document.getElementById('inp_namefull').value
    if (inp_namefull == '') {
        alertify.warning('Favor de colocar nombre completo.')
        document.getElementById("inp_namefull").focus();
        return false;
    }
    let inp_email = document.getElementById('inp_email').value
    if (inp_email == '') {
        alertify.warning('Favor de colocar correo electronico.')
        document.getElementById("inp_email").focus();
        return false;
    }
    let inp_email_confirm = document.getElementById('inp_email_confirm').value
    if (inp_email_confirm == '') {
        alertify.warning('Favor de confirmar correo electronico.')
        document.getElementById("inp_email_confirm").focus();
        return false;
    }

    //if (inp_email == inp_email_confirm) {
        chk_p1 = $('#chk_p1').is(":checked");
        chk_p2 = $('#chk_p2').is(":checked");
        chk_p3 = $('#chk_p3').is(":checked");
        chk_p4 = $('#chk_p4').is(":checked");
        chk_p5 = $('#chk_p5').is(":checked");
        chk_p6 = $('#chk_p6').is(":checked");
        chk_p7 = $('#chk_p7').is(":checked");

        var parametros={
            "inp_namefull":inp_namefull,
            "inp_email":inp_email,
            "inp_email_confirm":inp_email_confirm,
            "chk_p1":chk_p1,
            "chk_p2":chk_p2,
            "chk_p3":chk_p3,
            "chk_p4":chk_p4,
            "chk_p5":chk_p5,
            "chk_p6":chk_p6,
            "chk_p7":chk_p7
        }

        if (confirm('Esta seguro de mandar esta solicitud?')) {
            $.ajax({
                data: parametros,
                url: "script/nuevo_usuario.php",
                type: "POST",
                beforeSend: function(){
                    //Texto de busqueda
                    $("#response_nuser").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    //$("#btn_send").hide();
                },
                error: function(){
                    alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                    $("#btn_send").show();
                },
                success: function(data){
                    console.log(data);
                    $("#response_nuser").empty();
                    if (data == 1) {
                        alertify.success('Usuario creado satisfactoriamente.')
                        document.getElementById('inp_namefull').value = ''
                        document.getElementById('inp_email').value = ''
                        document.getElementById('inp_email_confirm').value = ''
                        $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                        setTimeout(function(){
                            tabla_usuarios()
                        }, 1000);
                    }else if(data == 2){
                        alertify.warning('El empleado '+inp_namefull+' o el correo '+inp_email+' ya se encuentra registrado en el sistema. Favor de revisar.')
                    }else{
                        alertify.warning('Favor de intentarlo nuevamente o contactar a su administrador de sistema')
                    }
                }
            })
        }
    /*}else{
        alertify.warning('Correo electronico es diferente en ambos campos.')
        document.getElementById("inp_email").focus();
        return false;
    }*/
}
//*****************
//***sistmea.php***
function tabla_usuarios() {
    $.ajax({
        //data: parametros,
        url: "script/tabla_usuarios.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_usuarios").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_usuarios").empty();
            $("#response_usuarios").append(data);
        }
    })
}
//*****************
//***script/tabla_usuarios.php***
function ver_usuario(id_user) {
    var parametros={
        "id_user":id_user
    }
    $.ajax({
        data: parametros,
        url: "script/ver_usuario.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_ver_usuario").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_ver_usuario").empty();
            $("#response_ver_usuario").append(data);
        }
    })
}
//*******************************
//***script/tabla_usuarios.php***
function actions_usuario(tipo_mov) {
    let id_user = document.getElementById('id_user').value
    chk_p1_u = $('#chk_p1_u').is(":checked");
    chk_p2_u = $('#chk_p2_u').is(":checked");
    chk_p3_u = $('#chk_p3_u').is(":checked");
    chk_p4_u = $('#chk_p4_u').is(":checked");
    chk_p5_u = $('#chk_p5_u').is(":checked");
    chk_p6_u = $('#chk_p6_u').is(":checked");
    chk_p7_u = $('#chk_p7_u').is(":checked");

    var parametros={
        "tipo_mov":tipo_mov,
        "id_user":id_user,
        "chk_p1_u":chk_p1_u,
        "chk_p2_u":chk_p2_u,
        "chk_p3_u":chk_p3_u,
        "chk_p4_u":chk_p4_u,
        "chk_p5_u":chk_p5_u,
        "chk_p6_u":chk_p6_u,
        "chk_p7_u":chk_p7_u
    }
    switch (tipo_mov) {
        case 1://***Actualizar
            if(confirm('Esta seguro de actualizar los permisos?')){
                $.ajax({
                    data: parametros,
                    url: "script/actions_usuario.php",
                    type: "POST",
                    beforeSend: function(){
                        //Texto de busqueda
                        alertify.warning('Realizando cambios...')
                        $("#btn_act1").hide();
                    },
                    error: function(){
                        alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                        $("#btn_act1").show();
                    },
                    success: function(data){
                        //console.log(data);
                        if (data == '1') {
                            alertify.success('Ajustes realizados')
                            $("#btn_act1").show();
                            $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                            setTimeout(function(){
                                tabla_usuarios()
                            }, 1000);
                        }else{
                            alertify.warning('Favor de intentarlo nuevamente.')
                            $("#btn_act1").show();
                        }
                    }
                })
            }

            break;
    
        case 2://***Reestablecer contraseña
            if(confirm('Esta seguro de reestablecer la contraseña?')){
                $.ajax({
                    data: parametros,
                    url: "script/actions_usuario.php",
                    type: "POST",
                    beforeSend: function(){
                        //Texto de busqueda
                        alertify.warning('Reestableciendo...')
                        $("#btn_act2").hide();
                    },
                    error: function(){
                        alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                        $("#btn_act2").show();
                    },
                    success: function(data){
                        //console.log(data);
                        if (data == '1') {
                            alertify.success('Contraseña reestablecida, favor de iniciar sesion con contraseña generica')
                            $("#btn_act2").show();
                            $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                            setTimeout(function(){
                                tabla_usuarios()
                            }, 1000);
                        }else{
                            alertify.warning('Favor de intentarlo nuevamente.')
                            $("#btn_act2").show();
                        }
                    }
                })
            }
            break;

        case 3://***Eliminar
            if(confirm('Esta seguro de eliminar el usuario?')){
                $.ajax({
                    data: parametros,
                    url: "script/actions_usuario.php",
                    type: "POST",
                    beforeSend: function(){
                        //Texto de busqueda
                        alertify.warning('Eliminando...')
                        $("#btn_act3").hide();
                    },
                    error: function(){
                        alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                        $("#btn_act3").show();
                    },
                    success: function(data){
                        //console.log(data);
                        if (data == '1') {
                            alertify.success('Usuario eliminado correctamente.')
                            $("#btn_act3").show();
                            $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                            setTimeout(function(){
                                tabla_usuarios()
                            }, 1000);
                        }else{
                            alertify.warning('Favor de intentarlo nuevamente.')
                            $("#btn_act3").show();
                        }
                    }
                })
            }
            break;
    }
}
//*******************************
//***sistmea.php***
function tabla_areas() {
    $.ajax({
        //data: parametros,
        url: "script/tabla_areas.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_areas").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_areas").empty();
            $("#response_areas").append(data);
        }
    })
}
//*****************
//***script/tabla_areas.php***
function ver_depto(id_depto) {
    var parametros={
        "id_depto":id_depto
    }
    $.ajax({
        data: parametros,
        url: "script/ver_depto.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_ver_depto").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_ver_depto").empty();
            $("#response_ver_depto").append(data);
        }
    })
}
//***script/tabla_areas.php***
function actions_depto(tipo_mov) {
    let id_depto = document.getElementById('id_depto').value
    let inp_code_emp = document.getElementById('inp_code_emp').value
    let inp_code_emp_sub = document.getElementById('inp_code_emp_sub').value
    var parametros={
        "tipo_mov":tipo_mov,
        "id_depto":id_depto,
        "inp_code_emp":inp_code_emp,
        "inp_code_emp_sub":inp_code_emp_sub
    }
    switch (tipo_mov) {
        case 1:
            if(confirm('Esta seguro de actualizar los datos?')){
                $.ajax({
                    data: parametros,
                    url: "script/actions_depto.php",
                    type: "POST",
                    beforeSend: function(){
                        //Texto de busqueda
                        alertify.warning('Realizando cambios...')
                        $("#btn_act1").hide();
                    },
                    error: function(){
                        alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                        $("#btn_act1").show();
                    },
                    success: function(data){
                        //console.log(data);
                        if (data == '1') {
                            alertify.success('Datos actualizados')
                            $("#btn_act1").show();
                            $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                            setTimeout(function(){
                                tabla_areas()
                            }, 1000);
                        }else{
                            alertify.warning('Favor de intentarlo nuevamente.')
                            $("#btn_act1").show();
                        }
                    }
                })
            }
            break;
    }
}
//****************************
//***sistema.php***
function tabla_empleados() {
    $.ajax({
        url: "script/tabla_empleados.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_empleados").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_empleados").empty();
            $("#response_empleados").append(data);
        }
    })
}

function ver_boss(emp_code) {
    var parametros={
        "emp_code":emp_code
    }
    $.ajax({
        data: parametros,
        url: "script/ver_boss.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_cambia_boss").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_cambia_boss").empty();
            $("#response_cambia_boss").append(data);
        }
    })
}

function update_boss() {
    inp_num_empl_cat = document.getElementById('inp_num_empl_cat').value 
    inp_num_empl_boss = document.getElementById('inp_num_empl_boss').value

    var parametros={
        "inp_num_empl_cat":inp_num_empl_cat,
        "inp_num_empl_boss":inp_num_empl_boss
    }
    if (confirm('Esta seguro de actualizar la informacion?')) {
        $.ajax({
            data: parametros,
            url: "script/update_boss.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                //$("#btn_update_boss").hide();
                alertify.warning('Actualizando...');
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                alertify.success('Actualizacion correcta')
                $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                setTimeout(function(){
                    tabla_empleados()
                }, 1000);
            }
        })
    }
}

function tabla_supers() {
    $.ajax({
        url: "script/tabla_supers.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_empleados").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_empleados").empty();
            $("#response_empleados").append(data);
        }
    })
}

function tabla_direc() {
    $.ajax({
        url: "script/tabla_direc.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_empleados").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_empleados").empty();
            $("#response_empleados").append(data);
        }
    })
}
//*****************
//***solicitudes.php***
function tabla_solicitudes() {
    var total_solicitudes = document.getElementById("spn_tot_nuev").textContent;
    if (total_solicitudes > 0) {
        $.ajax({
            url: "script/tabla_solicitudes.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                $("#response_solicitudes").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                $("#response_solicitudes").empty();
                $("#response_solicitudes").append(data);
            }
        })
    }else{
        alertify.warning('Sin solicitudes disponibles')
    }
}
//*********************
//***script/tabla_solicitudes.php***
function ver_solicitud(id_table, table) {
    var parametros={
        "id_table":id_table,
        "table":table
    }
    $.ajax({
        data: parametros,
        url: "script/ver_solicitud.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_ver_solicitud").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            arr_data = data.split("||")
            var resp_html = arr_data[0];
            var resp_btn = arr_data[1];
            $("#response_ver_solicitud").empty();
            $("#response_ver_solicitud").append(resp_html);
            $("#response_buton_hs").empty();
            $("#response_buton_hs").append(resp_btn);
        }
    })
}
//**********************************
//***modulos/tabla_solicitudes.php***
function actions_solicitud(mov, dg) {
    var confirmacion = 0;
    inp_id_table = document.getElementById('inp_id_table').value
    inp_table = document.getElementById('inp_table').value

    txt_obs = document.getElementById('txt_obs').value
    let selected_rdo_goce = $("input[type='radio'][name='rdo_goce']:checked");
    let rdo_tipo_Val_goce = '';


    switch (inp_table) {
        case 'rh_solicitudes':
            if (selected_rdo_goce.length > 0) {
                rdo_tipo_Val_goce = selected_rdo_goce.val();
            }
            
            var parametros={
                "mov":mov,
                "inp_id_table":inp_id_table,
                "inp_table":inp_table,
                "txt_obs":txt_obs,
                "rdo_tipo_Val_goce":rdo_tipo_Val_goce
            }
            break;

        case 'rh_salida':
            if (selected_rdo_goce.length > 0) {
                rdo_tipo_Val_goce = selected_rdo_goce.val();
            }

            inp_fecha_p = document.getElementById('inp_fecha_p').value
            inp_hora_p = document.getElementById('inp_hora_p').value
            chk_reponer = $('#chk_reponer').is(":checked");

            var parametros={
                "mov":mov,
                "inp_id_table":inp_id_table,
                "inp_table":inp_table,
                "txt_obs":txt_obs,
                "rdo_tipo_Val_goce":rdo_tipo_Val_goce,
                "inp_fecha_p":inp_fecha_p,
                "inp_hora_p":inp_hora_p,
                "chk_reponer":chk_reponer
            }
            break;

        case 'rh_vacaciones':
            /*inp_finicial = document.getElementById('inp_finicial').value
            inp_ffinal = document.getElementById('inp_ffinal').value*/
            inp_hora_i = document.getElementById('inp_hora_i').value
            inp_hora_s = document.getElementById('inp_hora_s').value
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

            inp_dg = document.getElementById('inp_dg').value
            if (inp_dg == '1') {
                selected_rdo_goce = 'true';
                dg = 0;
            }

            var parametros={
                "mov":mov,
                "inp_id_table":inp_id_table,
                "inp_arrdates":inp_arrdates,
                "inp_table":inp_table,
                "txt_obs":txt_obs,
                "inp_dg":inp_dg
            }
            break;
    }

    if (mov == '1') {
        if (confirm('¿Esta seguro de aprobar esta solicitud?')) {
            if ((rdo_tipo_Val_goce == 1) && (dg == 0)) {
                if (confirm('Esta solicitud sera reenviada a Direccion General.\n ¿Desea continuar?')) {
                    confirmacion = 1;
                }else{
                    confirmacion = 0;
                }
            }else{
                confirmacion = 1;
            }
        }
    } else if (mov == '2') {
        if (confirm('¿Esta seguro de rechazar esta solicitud?')) {
            confirmacion = 1;
        }
    } else if (mov == '5') {
        if (confirm('¿Esta seguro de rechazar las vacaciones ya aprobadas?')) {
            //var obs_extra = prompt("Favor de colocar el motivo del rechazo.");
            
            if (txt_obs == '') {
                alert("Favor de colocar el motivo del rechazo en el campo de observaciones.")
                confirmacion = 0;
            }else{
                confirmacion = 1;
            }
        }
    }
    
    if (confirmacion == 1) {
        $.ajax({
            data: parametros,
            url: "script/actions_solicitud.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                alertify.warning('Procesando...')
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                if (data == 1) {
                    if (mov == '1') {
                        if (inp_table == 'rh_vacaciones') {
                            alertify.success('La solicitud fue aprobada y enviada para revision a RH.')
                        } else {
                            alertify.success('La solicitud fue aprobada.')
                        }
                    } else {
                        alertify.success('La solicitud fue rechazada.')
                    }

                    $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                    setTimeout(function(){
                        new_solicitudes(),
                        tabla_solicitudes()
                    }, 1000);
                }else{
                    alertify.warning('Favor de intentrarlo nuevamente o contactar a su administrador de sistema.')
                }
            }
        })    
    }
}
//***********************************
//***modulos/solicitudes.php***
function new_solicitudes() {
    $.ajax({
        url: "script/new_solicitudes.php",
        type: "POST",
        success: function(data){
            //console.log(data);
            document.getElementById("spn_tot_nuev").textContent = data;
            setTimeout(function(){
                new_solicitudes()
            }, 120000);//***120 segundos
        }
    })
}

function tabla_calendario() {
    location.reload();
}
//*****************************
//***solicitudes.php***
function tabla_solicitudes_h() {
    $.ajax({
        url: "script/tabla_solicitudes_h.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_solicitudes").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            $("#response_solicitudes").empty();
            $("#response_solicitudes").append(data);
        }
    })
}
//***modulos/personal.php***
function employee_actions(emp_code, mov) {
    if (mov == 1) {
        var parametros={
            "mov":mov,
            "emp_code":emp_code
        }

        $.ajax({
            data: parametros,
            url: "script/employee_actions.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                $("#response_employee_actions").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                $("#response_employee_actions").empty();
                $("#response_employee_actions").append(data);
            }
        })
    } else if (mov == 2) {
        $("#response_employee_actions").empty();

        var parametros={
            "mov":mov,
            "emp_code":emp_code
        }

        $.ajax({
            data: parametros,
            url: "script/employee_actions.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                $("#response_employee_actions").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                $("#response_employee_actions").empty();
                $("#response_employee_actions").append(data);
            }
        })
    } else if (mov == 3) {
        let selected_rdo_horarios = $("input[type='radio'][name='rdo_horarios']:checked");
        if (selected_rdo_horarios.length > 0) {
            rdo_horarios_val = selected_rdo_horarios.val();
        }
        inp_date_hr = document.getElementById('inp_date_hr').value

        if (confirm('¿Esta seguro de actualizar el horario?')) {

            var v_observaciones = prompt("Observaciones");

            var parametros={
               "mov":mov,
               "emp_code":emp_code,
               "rdo_horarios_val":rdo_horarios_val,
               "v_observaciones":v_observaciones,
               "inp_date_hr":inp_date_hr
           }
            
            $.ajax({
                data: parametros,
                url: "script/employee_actions.php",
                type: "POST",
                error: function(){
                    alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
                },
                success: function(data){
                    console.log(data);
                    arr_data = data.split("|")
                    var v_pos = arr_data[0];
                    var v_sms = arr_data[1];

                    arr_data_2 = v_pos.split("*")
                    var v_pos = arr_data_2[0];
                    var file_s = arr_data_2[1];
                    if (v_pos == 1) {
                        alertify.warning(v_sms)
                    } else {
                        alertify.success('Actualizacion satisfactoria')
                        setTimeout(function(){
                           window.open("script/file/"+file_s);
                           //alert(v_sms)
                        }, 1000);
                        setTimeout(function(){
                            employee_actions(emp_code, 1)
                        }, 1000);
                    }
                }
            })
        }
    }
}
//**************************
//***modulos/sistema.php***
function tipos_nominas(emp_code, mov) {
    if (mov == 1) {
        var parametros={
            "emp_code":emp_code,
            "mov":mov
        }
        $.ajax({
            data: parametros,
            url: "script/tipos_nominas.php",
            type: "POST",
            beforeSend: function(){
                //Texto de busqueda
                $("#response_tipos_nominas").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            },
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                //console.log(data);
                $("#response_tipos_nominas").empty();
                $("#response_tipos_nominas").append(data);
            }
        })
    } else {
        emp_code = document.getElementById('inp_emp_code').value
        slc_opcion = document.getElementById('slc_opcion').value
        var parametros={
            "emp_code":emp_code,
            "slc_opcion":slc_opcion
        }
        $.ajax({
            data: parametros,
            url: "script/tipos_nominas.php",
            type: "POST",
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                alertify.success('Actualizacion correcta')
                $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                setTimeout(function(){
                    tabla_empleados()
                }, 1000);
            }
        })
    }
}
function restablece_pin(num_empl) {
    if (confirm('¿Esta seguro de restablecer el PIN de este empleado?')) {
        var parametros={
            "num_empl":num_empl
        }
    
        $.ajax({
            data: parametros,
            url: "script/restablece_pin.php",
            type: "POST",
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                if (data == '1') {
                    alertify.success('PIN restablecido')
                }else{
                    alertify.warning('Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.')
                }
            }
        })
    }
}
function nuevo_super() {
    let v_error = 0;
    inp_num_empl_sup = document.getElementById('inp_num_empl_sup').value
    inp_fname_sup = document.getElementById('inp_fname_sup').value
    inp_lname_sup = document.getElementById('inp_lname_sup').value
    if (inp_num_empl_sup == '') {
        v_error = 1
    }
    if (inp_fname_sup == '') {
        v_error = 1
    }

    if (v_error == 1) {
        alertify.warning('Favor de revisar su informacion')
    } else {
        var parametros={
            "inp_num_empl_sup":inp_num_empl_sup,
            "inp_fname_sup":inp_fname_sup,
            "inp_lname_sup":inp_lname_sup
        }
        
        $.ajax({
            data: parametros,
            url: "script/nuevo_super.php",
            type: "POST",
            error: function(){
                alert("Error inesperado, intentalo de nuevo o consulta a tu administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                if (data == '1') {
                    alertify.warning('El empleado esta registrado como supervisor.')
                }else{
                    alertify.success('El empleado se registro como supervisor.')
                    $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                    setTimeout(function(){
                        tabla_supers()
                    }, 2000);
                }
            }
        })
    }
}
//*************************
//***modulos/personal.php***
function correo_empleado(id_zk, email_zk) {
    var v_pregunta = prompt("Coloca el nuevo correo electronico", email_zk);
    if (v_pregunta != null) {
        var parametros={
            "id_zk":id_zk,
            "v_pregunta":v_pregunta
        }

        $.ajax({
            data: parametros,
            url: "script/correo_empleado.php",
            type: "POST",
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                if (data == '1') {
                    alertify.success('Actualizacion correcta')
                    location.reload();
                }
            }
        })
    }else{
        alert('Colocar un correo electronico.')
    }
}
//**************************
//***modulos/personal.php***
function busca_emp_sup(event) {
    inp_num_empl_sup = document.getElementById('inp_num_empl_sup').value
    var codigo_press = event.which;//***event.keyCode
    if (codigo_press === 13) {
        if (inp_num_empl_sup != '') {
            var parametros={
                "inp_num_empl_sup":inp_num_empl_sup
            }
            
            $.ajax({
                data: parametros,
                url: "script/busca_emp_sup.php",
                type: "POST",
                beforeSend: function(){
                    $("#response_super_emp").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#response_super_emp").empty();
                    if (data == '5') {
                        alert('Sin coincidencias')
                    } else {
                        $("#response_super_emp").append(data);
                    }
                }
            })
        }
    }
}

function btn_save_super() {
    let v_error = 0;
    inp_num_empl_sup = document.getElementById('inp_num_empl_sup').value
    inp_fname_sup = document.getElementById('inp_fname_sup').value
    inp_lname_sup = document.getElementById('inp_lname_sup').value
    inp_depto_sup = document.getElementById('inp_depto_sup').value
    inp_pos_sup = document.getElementById('inp_pos_sup').value
    if (inp_num_empl_sup == '') {
        v_error = 1
    }
    if (inp_fname_sup == '') {
        v_error = 1
    }

    if (v_error == 1) {
        alertify.warning('Favor de revisar su informacion')
    } else {
        var parametros={
            "inp_num_empl_sup":inp_num_empl_sup,
            "inp_fname_sup":inp_fname_sup,
            "inp_lname_sup":inp_lname_sup,
            "inp_depto_sup":inp_depto_sup,
            "inp_pos_sup":inp_pos_sup
        }
        
        $.ajax({
            data: parametros,
            url: "script/btn_save_super.php",
            type: "POST",
            error: function(){
                alert("Error inesperado, intentalo de nuevo o consulta a tu administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                arr_data = data.split("|")
                response_a = arr_data[0]
                response_b = arr_data[1]
                $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                if (response_a == '1') {
                    alertify.warning('El empleado esta asignado a '+response_b+', solicite la liberacion con el supervisor.')
                }else if (response_a == '2') {
                    alertify.warning('El empleado ya esta asignado a su equipo de trabajo.')
                }else{
                    alertify.success('El empleado se registro a su equipo de trabajo.')
                    $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            }
        })
    }
}
function liberar_sup_emp(emp_code) {
    if (confirm('Esta seguro de liberar del equipo de trabajo?')) {
        var parametros={
            "emp_code":emp_code
        }

        $.ajax({
            data: parametros,
            url: "script/liberar_sup_emp.php",
            type: "POST",
            beforeSend: function(){
                alertify.warning('Liberar...')
            },
            error: function(){
                alert("Error inesperado.");
            },
            success: function(data){
                if (data == '1') {
                    alertify.success('Empleado liberado correctamente.')
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            }
        })
    }
}
//**************************
//***modulos/solicitudes.php***VISTO COMO RH
function rh_tabla_vacaciones() {
    $.ajax({
        url: "script/rh_tabla_vacaciones.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_solicitudes").html('<div class="d-flex align-items-center"><strong>Cargando...</strong><div class="spinner-border text-warning ms-auto" role="status" aria-hidden="true"></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_solicitudes").empty();
            $("#response_solicitudes").append(data);
        }
    })
}

function ver_solicitud_rh(id_table, table, accion) {
    var parametros={
        "id_table":id_table,
        "table":table,
        "accion":accion
    }
    $.ajax({
        data: parametros,
        url: "script/ver_solicitud_rh.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_ver_solicitud_rh").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            //console.log(data);
            arr_data = data.split("||")
            var resp_html = arr_data[0];
            var resp_btn = arr_data[1];
            $("#response_ver_solicitud_rh").empty();
            $("#response_ver_solicitud_rh").append(resp_html);
            $("#response_ver_solicitud_rh_buttons").empty();
            $("#response_ver_solicitud_rh_buttons").append(resp_btn);
        }
    })
}

function actions_solicitud_rh(mov) {
    var confirmacion = 0;
    var obs_extra = '';
    var msg_succes = '';
    inp_id_table = document.getElementById('inp_id_table').value
    inp_table = document.getElementById('inp_table').value

    if (mov == '1') {
        //if (confirm('¿Esta seguro de aprobar esta solicitud?')) {
            confirmacion = 1;
            msg_succes = 'Solicitud revisada y aceptada correctamente';
        //}
    } else if (mov == '2') {
        //if (confirm('¿Esta seguro de rechazar esta solicitud?')) {
            confirmacion = 1;
            msg_succes = 'Solicitud revisada y rechazada correctamente';
        //}
    } 
    
    if (confirmacion == 1) {
        if (confirm('¿Desea agregar algun comentario?')) {
            obs_extra = prompt("Coloque su comentario:");
        }

        var parametros={
            "mov":mov,
            "inp_id_table":inp_id_table,
            "inp_table":inp_table,
            "obs_extra":obs_extra
        }

        $.ajax({
            data: parametros,
            url: "script/actions_solicitud_rh.php",
            type: "POST",
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
               console.log(data);
               if (data == 1) {
                  alertify.success(msg_succes);

                  $("[data-bs-dismiss=modal]").trigger({ type: "click" });
                  /*setTimeout(function(){
                     rh_tabla_vacaciones()
                  }, 1000);
                  setTimeout(function(){
                     $('#Mdl_dias_vac_RH').modal('show');
                     $("#response_vacaciones_rh").empty();
                     $("#response_vacaciones_rh").html('<div class="d-flex align-items-center"><strong>Cargando...</strong><div class="spinner-border text-warning ms-auto" role="status" aria-hidden="true"></div></div>');
                     setTimeout(function(){
                        accion_vacaciones(1, inp_id_table, inp_table)//***Ver info de vacaciones
                     }, 1000);
                  }, 1000);*/
               }else if (data == 7) {
                  alertify.warning('Esta solicitud ya fue aprobada con anterioridad.')
               }else if (data == 8) {
                  alertify.warning('Esta solicitud ya fue rechazada con anterioridad.')
               }else{
                  alertify.warning('Favor de intentrarlo nuevamente o contactar a su administrador de sistema.')
               }
            }
        })    
    }
}

function rh_tb_dia() {
    $.ajax({
        url: "script/rh_tb_dia.php",
        type: "POST",
        beforeSend: function(){
            $("#response_solicitudes").html('<div class="d-flex align-items-center"><strong>Cargando...</strong><div class="spinner-border text-warning ms-auto" role="status" aria-hidden="true"></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_solicitudes").empty();
            $("#response_solicitudes").append(data);
        }
    })
}

function busqueda_full_rh() {
    let inp_f_busqueda = document.getElementById('inp_f_busqueda').value
    let inp_num_empl_rh = document.getElementById('inp_num_empl_rh').value
    let slc_formato = document.getElementById('slc_formato').value

    var parametros={
        "inp_f_busqueda":inp_f_busqueda,
        "inp_num_empl_rh":inp_num_empl_rh,
        "slc_formato":slc_formato
    }

    $.ajax({
        data: parametros,
        url: "script/busqueda_full_rh.php",
        type: "POST",
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_solicitudes").empty();
            $("#response_solicitudes").append(data);
        }
    })
}

function limpia_full_rh() {
    document.getElementById('inp_f_busqueda').value = ''
    document.getElementById('inp_num_empl_rh').value = ''
}

function accion_vacaciones(mov, data_a, data_b) {
    var parametros={
        "mov":mov,
        "data_a":data_a,
        "data_b":data_b
    }

    $.ajax({
        data: parametros,
        url: "script/accion_vacaciones.php",
        type: "POST",
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_vacaciones_rh").empty();
            $("#response_vacaciones_rh").append(data);
        }
    })    
}

function save_dias() {
    inp_id_empleado_rh = document.getElementById('inp_id_empleado_rh').value
    year_ciclo_a = document.getElementById('year_ciclo_a').value
    ciclo_a = document.getElementById('ciclo_a').value
    if (ciclo_a < 1) {
        alertify.warning('Favor de colocar un numero valido.')
        document.getElementById("ciclo_a").focus();
        return false;
    }
    year_ciclo_b = document.getElementById('year_ciclo_b').value
    ciclo_b = document.getElementById('ciclo_b').value
    if (ciclo_b < 1) {
        alertify.warning('Favor de colocar un numero valido.')
        document.getElementById("ciclo_b").focus();
        return false;
    }

    var parametros={
        "inp_id_empleado_rh":inp_id_empleado_rh,
        "year_ciclo_a":year_ciclo_a,
        "ciclo_a":ciclo_a,
        "year_ciclo_b":year_ciclo_b,
        "ciclo_b":ciclo_b
    }

    $.ajax({
        data: parametros,
        url: "script/save_dias.php",
        type: "POST",
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            if (data == 1) {
                alertify.success('Se actualizaron los dias de vacaciones');
            }else{
                alertify.warning('Favor de intentrarlo nuevamente o contactar a su administrador de sistema.')
            }
            $("[data-bs-dismiss=modal]").trigger({ type: "click" });
            setTimeout(function(){
                rh_tabla_vacaciones()
            }, 1000);
        }
    })
}
//*****************************
//***modulos/incapacidades.php
function btn_busca_empleado() {
    let inp_num_empl_in = document.getElementById('inp_num_empl_in').value

    var parametros={
        "inp_num_empl_in":inp_num_empl_in
    }

    $.ajax({
        data: parametros,
        url: "script/btn_busca_empleado.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_emp_inc").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_emp_inc").empty();
            $("#response_emp_inc").append(data);
        }
    })
}

function btn_guarda_inc() {
    let inp_id_empleado = document.getElementById('inp_id_empleado').value
    let inp_id_depto = document.getElementById('inp_id_depto').value
    let inp_fname = document.getElementById('inp_fname').value
    let inp_lname = document.getElementById('inp_lname').value
    let inp_depto = document.getElementById('inp_depto').value
    let inp_pos = document.getElementById('inp_pos').value
    let inp_fini = document.getElementById('inp_fini').value
    if (inp_fini == '') {
        alertify.warning('Favor de colocar una fecha inicial.')
        document.getElementById("inp_fini").focus();
        return false;
    }
    let inp_ffin = document.getElementById('inp_ffin').value
    let inp_tdias = document.getElementById('inp_tdias').value
    if ((inp_ffin != '') && (inp_tdias == '')) {
        alertify.warning('Favor de colocar el total de dias de incapacidad.')
        document.getElementById("inp_tdias").focus();
        return false;
    }else if ((inp_ffin == '') && (inp_tdias != '')) {
        alertify.warning('Favor de colocar la fecha final de incapacidad.')
        document.getElementById("inp_ffin").focus();
        return false;
    }
    //let inp_file = document.getElementById('inp_file').value
    let txt_obs = document.getElementById('txt_obs').value

    var paqueteDeDatos = new FormData();
	paqueteDeDatos.append('inp_file', $('#inp_file')[0].files[0]);
    paqueteDeDatos.append('inp_id_empleado', inp_id_empleado);
    paqueteDeDatos.append('inp_id_depto', inp_id_depto);
    paqueteDeDatos.append('inp_fname', inp_fname);
    paqueteDeDatos.append('inp_lname', inp_lname);
    paqueteDeDatos.append('inp_depto', inp_depto);
    paqueteDeDatos.append('inp_pos', inp_pos);
    paqueteDeDatos.append('inp_fini', inp_fini);
    paqueteDeDatos.append('inp_ffin', inp_ffin);
    paqueteDeDatos.append('inp_tdias', inp_tdias);
    paqueteDeDatos.append('txt_obs', txt_obs);

    $.ajax({
        data: paqueteDeDatos,
        url: "script/btn_guarda_inc.php",
        type: "POST",
        contentType: false,
        processData: false,
        cache: false, 
        beforeSend: function(){
            alertify.warning('Procesando...')
            $("#response_emp_inc_btn").hide();
        },
        error: function(){
            alert("Favor de intentarlo nuevamente.");
            $("#response_emp_inc_btn").show();
        },
        success: function(data){
            switch (data) {
                case 2:
                    alertify.danger('El archivo es demasiado grande, favor de revisar e intentar nuevamente')
                    $("#response_emp_inc_btn").show();
                    break;

                case 3:
                    alertify.danger('El archivo no es tipo PDF, favor de revisar e intentar nuevamente')
                    $("#response_emp_inc_btn").show();
                    break;
            
                default:
                    $("#response_emp_inc_div").empty();
                    $("#response_emp_inc_div").append(data);
                    break;
            }
        }
    })
}

function borrar_inc(id_ausencia) {
    if (confirm('¿Esta seguro de eliminar esta incapacidad?')) {
        var parametros={
            "id_ausencia":id_ausencia
        }
    
        $.ajax({
            data: parametros,
            url: "script/borrar_inc.php",
            type: "POST",
            error: function(){
                alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
            },
            success: function(data){
                console.log(data);
                if (data == 1) {
                    alertify.success('Se elimino la incapacidad.')
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                }
            }
        })
    }
}
//****************************
//***reportes.php***
function btn_busca_lsm() {
    let slc_kardex = document.getElementById('slc_kardex').value
    let slc_year = document.getElementById('slc_year').value
    let list_num_empl_k = document.getElementById('list_num_empl_k').value

    if ((slc_kardex == '0') && (list_num_empl_k == '')) {
        alertify.warning('Seleccionar el tipo de Kardex a buscar o colocar un numero de empleado');
        return false;
    }

    var parametros={
        "slc_kardex":slc_kardex,
        "slc_year":slc_year,
        "list_num_empl_k":list_num_empl_k
    }

    $.ajax({
        data: parametros,
        url: "script_reportes/btn_busca_lsm.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_kardex").html('<div class="d-flex align-items-center text-warning"><strong>Buscando informacion...</strong><div class="spinner-border ms-auto text-warning" role="status" aria-hidden="true"></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_kardex").empty();
            $("#response_kardex").append(data);
        }
    })
}

function verkardex() {
    let slc_kardex_v = document.getElementById('slc_kardex_v').value //***Modal
    if (slc_kardex_v == '0') {
        alertify.warning('Seleccionar el tipo de Kardex a buscar');
        return false;
    }

    var parametros={
        "slc_kardex_v":slc_kardex_v
    }

    $.ajax({
        data: parametros,
        url: "script_reportes/verkardex.php",
        type: "POST",
        beforeSend: function(){
            //Texto de busqueda
            $("#response_verkardex").html('<div class="d-flex align-items-center text-info"><strong>Buscando...</strong><div class="spinner-border ms-auto text-warning" role="status" aria-hidden="true"></div></div>');
        },
        error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
        },
        success: function(data){
            console.log(data);
            $("#response_verkardex").empty();
            $("#response_verkardex").append(data);
        }
    })
}

function busca_emp_k(event) {
    inp_num_empl_sup = document.getElementById('inp_num_empl_k').value
    var codigo_press = event.which;//***event.keyCode
    if (codigo_press === 13) {
        if (inp_num_empl_k != '') {
            var parametros={
                "inp_num_empl_sup":inp_num_empl_sup
            }
            
            $.ajax({
                data: parametros,
                url: "script/busca_emp_sup.php",
                type: "POST",
                beforeSend: function(){
                    $("#response_busca_emp_k").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                },
                error: function(){
                    alert("Error inesperado.");
                },
                success: function(data){
                    $("#response_busca_emp_k").empty();
                    if (data == '5') {
                        $("#response_busca_emp_k").html('<input type="hidden" class="form-control" id="inp_fname_sup" value="">');
                    } else {
                        $("#response_busca_emp_k").append(data);
                    }
                }
            })
        }
    }
}

function activa_div_kardex() {
    var x = document.getElementById("div_empleados_kardex");
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}

function limpia_emp_kardex() {
    var x = document.getElementById("div_empleados_kardex");
    $("#response_busca_emp_k").empty();
    document.getElementById('inp_num_empl_k').value = '';
    x.style.display = "none";
}

function guarda_emp_kardex() {
    var inp_num_empl_k = document.getElementById("inp_num_empl_k").value;
    var inp_fname_sup = document.getElementById("inp_fname_sup").value;
    let slc_kardex_v = document.getElementById('slc_kardex_v').value //***Modal

    if (slc_kardex_v == '0') {
        alertify.warning('Seleccionar el tipo de Kardex a buscar');
        return false;
    }

    if (inp_num_empl_k == '') {
        alertify.warning('Favor de colocar un numero de empleado valido.')
        document.getElementById("inp_num_empl_k").focus();
        return false;
    }

    if (inp_fname_sup == '') {
        alertify.warning('Recuerde dar enter en el numero de empleado para mostrar la informacion del empleado.')
        document.getElementById("inp_num_empl_k").focus();
        return false;
    }

    var parametros={
        "inp_num_empl_k":inp_num_empl_k,
        "slc_kardex_v":slc_kardex_v
    }
    
    $.ajax({
        data: parametros,
        url: "script_reportes/guarda_emp_kardex.php",
        type: "POST",
        error: function(){
            alert("Error inesperado.");
        },
        success: function(data){
            console.log(data);
            if (data == 1) {
                alertify.warning('Este empleado ya esta registrado en el Kardex')
            } else {
                alertify.success('Se guardo correctamente.')
                var x = document.getElementById("div_empleados_kardex");
                $("#response_busca_emp_k").empty();
                document.getElementById('inp_num_empl_k').value = '';
                x.style.display = "none";

                setTimeout(function(){
                    verkardex();
                }, 1000);
            }
        }
    })
}

function excel_karex() {
    let slc_kardex = document.getElementById('slc_kardex').value
    let slc_year = document.getElementById('slc_year').value
    let list_num_empl_k = document.getElementById('list_num_empl_k').value

    alertify.warning('Creando archivo de Excel...');
    setTimeout(function(){
        window.open("script_reportes/excel_karex.php?slc_year="+slc_year+"&slc_kardex="+slc_kardex+"&list_num_empl_k="+list_num_empl_k);
    }, 1000);
}

function busca_reg_te() {
   slc_areas_nn = document.getElementById('slc_areas_nn').value
   inp_fecha_nn = document.getElementById('inp_fecha_nn').value

   var parametros={
      "slc_areas_nn":slc_areas_nn,
      "inp_fecha_nn":inp_fecha_nn
   }

   $.ajax({
      data: parametros,
      url: "script_reportes/busca_reg_te.php",
      type: "POST",
      beforeSend: function(){
         //Texto de busqueda
         $("#response_te_nn").html('<div class="d-flex align-items-center text-warning"><strong>Buscando registros...</strong><div class="spinner-border ms-auto text-warning" role="status" aria-hidden="true"></div></div>');
      },
      error: function(){
         alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
      },
      success: function(data){
         console.log(data);
         $("#response_te_nn").empty();
         $("#response_te_nn").append(data);
      }
   })
}

function listas_tabla_nn(slc_area) {
   var parametros={
      "slc_area":slc_area
   }

   $.ajax({
      data: parametros,
      url: "script_reportes/listas_tabla_nn.php",
      type: "POST",
      beforeSend: function(){
         //Texto de busqueda
         $("#response_listas_nn").html('<div class="d-flex align-items-center text-info"><strong>Buscando...</strong><div class="spinner-border ms-auto text-info" role="status" aria-hidden="true"></div></div>');
      },
      error: function(){
         alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
      },
      success: function(data){
         console.log(data);
         $("#response_listas_nn").empty();
         $("#response_listas_nn").append(data);
      }
   })
}

function fun_areas(mov, id_bdarea) {
   switch (mov) {
      case 1:
         inp_id_bd_nn = document.getElementById('inp_id_bd_nn').value
         slc_new_areas_nn_list = document.getElementById('slc_new_areas_nn_list').value

         var parametros={
            "mov":mov,
            "inp_id_bd_nn":inp_id_bd_nn,
            "slc_new_areas_nn_list":slc_new_areas_nn_list
         }
         break;
   
      case 2:
         if (confirm('Esta seguro de eliminar este registro?')) {
            var parametros={
               "mov":mov,
               "id_bdarea":id_bdarea
            }
         }else{
            return false;
         }
         break;

      case 3:
         var parametros={
            "mov":mov,
            "id_bdarea":id_bdarea
         }
         break;

      case 4:
         inp_num_empl_sup = document.getElementById('inp_num_empl_sup').value
         slc_areas_pal_new = document.getElementById('slc_areas_pal_new').value
         inp_fname_sup = document.getElementById('inp_fname_sup').value
         if (inp_fname_sup == '') {
            alertify.warning('Despues de escribir el numero de empleado, presionar Enter')
            return false;
         }
         if (slc_areas_pal_new == "") {
            alertify.warning('Favor de seleccionar un area valida')
            document.getElementById("slc_areas_pal_new").focus();
            return false;
         }

         var parametros={
            "mov":mov,
            "inp_num_empl_sup":inp_num_empl_sup,
            "slc_areas_pal_new":slc_areas_pal_new
         }
         break;
   }

   $.ajax({
      data: parametros,
      url: "script_reportes/fun_areas.php",
      type: "POST",
      error: function(){
         alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
      },
      success: function(data){
         console.log(data);
         switch (mov) {
            case 1:
               alertify.success('Actualizacion correcta')
               slc_areas_nn_list = document.getElementById('slc_areas_nn_list').value
               $("[data-bs-dismiss=modal]").trigger({ type: "click" });
               $('#mdl_listas_nn').modal('show');
               setTimeout(function(){
                  listas_tabla_nn(slc_areas_nn_list)
               }, 1000);
               break;
         
            case 2:
               alertify.success('Se elimino el registro')
               slc_areas_nn_list = document.getElementById('slc_areas_nn_list').value
               setTimeout(function(){
                  listas_tabla_nn(slc_areas_nn_list)
               }, 1000);
               break;
      
            case 3:
               $("#response_reubicacion").empty();
               $("#response_reubicacion").append(data);
               break;

            case 4:
               alertify.success('Se agrego correctamente')
               $("[data-bs-dismiss=modal]").trigger({ type: "click" });
               $('#mdl_listas_nn').modal('show');
               setTimeout(function(){
                  listas_tabla_nn(slc_areas_pal_new)
               }, 1000);
               break;
         }
      }
   })
}

function busca_rep_rh() {
   inp_num_empl_rh = document.getElementById('inp_num_empl_rh').value
   inp_f_busqueda = document.getElementById('inp_f_busqueda').value
   slc_semana = document.getElementById('slc_semana').value
   slc_quincena = document.getElementById('slc_quincena').value

   if ((inp_num_empl_rh == '') && (inp_f_busqueda == '') && (slc_semana == '') && (slc_quincena == '')) {
      alertify.warning('Favor de realizar una busqueda permitida')
   }else if((inp_num_empl_rh != '') && (inp_f_busqueda != '') && ((slc_semana != '') || (slc_quincena != ''))) {
      alertify.warning('Favor de revisar sus parametros de busqueda')
   }else if((inp_num_empl_rh == '') && (inp_f_busqueda != '') && ((slc_semana != '') || (slc_quincena != ''))) {
      alertify.warning('Si selecciona semana o quincena, no puede seleccionar una fecha')
   }else if((inp_num_empl_rh == '') && (inp_f_busqueda == '') && (slc_semana != '') && (slc_quincena != '')) {
      alertify.warning('Unicamente seleccionar semana o quincena')
   }else if((inp_num_empl_rh != '') && (inp_f_busqueda == '') && ((slc_semana != '') || (slc_quincena != ''))) {
      alertify.warning('Si selecciona semana o quincena, no puede colocar un empleado')
   }else{
      var parametros={
         "inp_num_empl_rh":inp_num_empl_rh,
         "inp_f_busqueda":inp_f_busqueda,
         "slc_semana":slc_semana,
         "slc_quincena":slc_quincena
      }

      $.ajax({
         data: parametros,
         url: "script_reportes/busca_rep_rh.php",
         type: "POST",
         beforeSend: function(){
            $("#response_rep_rh").html('<div class="d-flex align-items-center text-success"><strong>Creando reporte...</strong><div class="spinner-border ms-auto text-success" role="status" aria-hidden="true"></div></div>');
         },
         error: function(){
            alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
         },
         success: function(data){
            console.log(data);
            $("#response_rep_rh").empty();
            $("#response_rep_rh").append(data);
         }
      })
   }
}

function fun_year_semana_r(year) {
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

function reporte_te_r() {
   slc_year = document.getElementById('slc_year').value
   slc_semana = document.getElementById('slc_semana').value
   slc_quincena = document.getElementById('slc_quincena').value

   if ((slc_semana == '') && (slc_quincena == '')) {
      alertify.warning('Favor de seleccionar una semana o quincena.')
      return false;
   }

   if ((slc_semana != '') && (slc_quincena != '')) {
      alertify.warning('Favor de seleccionar unicamente una semana o quincena.')
      return false;
   }

   var parametros={
      "slc_year":slc_year,
      "slc_semana":slc_semana,
      "slc_quincena":slc_quincena
   }

   $.ajax({
      data: parametros,
      url: "script_reportes/reporte_te_r.php",
      type: "POST",
      beforeSend: function(){
         //Texto de busqueda
         $("#response_reporte_te_r").html('<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
      },
      error: function(){
         alert("Error inesperado, favor de intentarlo nuevamente o contactar a su administrador de sistema.");
      },
      success: function(data){
         console.log(data);
         $("#response_reporte_te_r").empty();
         arr_data = data.split("||")
         var v_error = arr_data[0];
         var v_erdes = arr_data[1];
         
         $("#response_reporte_te_r").append(data);
      }
   })
}
//******************
//***modulos/sistema.php***
function cambiar_estatus(num_emp_super) {
   //alert(num_emp_super);
   var parametros={
      "num_emp_super":num_emp_super
   }
   $.ajax({
      data: parametros,
      url: "script/cambiar_estatus.php",
      type: "POST",
      success: function(data){
         console.log(data);
         if (data == 1) {
            alertify.success('Se agrego el permiso')
         }else{
            alertify.warning('Se elimino el permiso')
         }
      }
   })
}
//*************************