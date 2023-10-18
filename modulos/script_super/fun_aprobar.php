<?php
    session_start();
    date_default_timezone_set("America/Mazatlan");
    /*while($post = each($_SESSION)){
		echo $post[0]." = ".$post[1]."<br>";
	}*/
    $num_empleado_session = $_SESSION['num_empleado_a'];
    $name_empleado_session = $_SESSION['name_a'];
    $fecha_now = date('Y-m-d H:i:s');

    $txt_historico = "Supervisor ".$num_empleado_session." ".$name_empleado_session." aprueba los horarios, fecha(".$fecha_now.")";
    
    $response_btn = array();

    ini_set('max_execution_time', 0);

    include ('../../php/conn.php');

    $dia_dia = $_POST['dia_dia'];
    $slc_year = $_POST['slc_year'];
    $slc_semana = $_POST['slc_semana'];
    $emp_code = $_POST['emp_code_b'];
    $f_recorre = $_POST['f_recorre'];
    $Hora_normal = $_POST['Hora_normal'];
    $Hora_normal_end = $_POST['Hora_normal_end'];
    $Hora_extra = $_POST['Hora_extra'];
    $Hora_extra_end = $_POST['Hora_extra_end'];
    $hrs_nml = $_POST['hrs_nml'];
    $min_nml = $_POST['min_nml'];
    $hrs_ext = $_POST['hrs_ext'];
    $min_ext = $_POST['min_ext'];
    $shift_id = $_POST['shift_id'];
    $min_retardo = $_POST['min_retardo'];
    $inphin_ = $_POST['inphin_'];
    $inphout_ = $_POST['inphout_'];
    $inphinext_ = $_POST['inphinext_'];
    $inphoutext_ = $_POST['inphoutext_'];
    $mensaje_te = $_POST['mensaje_te'];

    $query_revisa = "SELECT id, tot_hrs_e_new FROM [rh_novag_system].[dbo].[rh_master_emptime] WHERE year = '$slc_year' AND n_semana = '$slc_semana' AND f_recorre = '$f_recorre' AND emp_code = '$emp_code'";
    $exe_revisa = sqlsrv_query($cnx, $query_revisa);
    $fila_revisa = sqlsrv_fetch_array($exe_revisa, SQLSRV_FETCH_ASSOC);
    $id_revisa = $fila_revisa['id'];
    if (is_numeric($id_revisa)) {
        $tot_hrs_e_new = $fila_revisa['tot_hrs_e_new'];
        $txt_historico = "||Supervisor ".$num_empleado_session." ".$name_empleado_session." modifica total de horas extra de ".$tot_hrs_e_new." a ".$inphinext_." horas, fecha(".$fecha_now.")";
        $query_insert = "UPDATE [rh_novag_system].[dbo].[rh_master_emptime] SET rh_master_emptime.historico = CONCAT(historico, '$txt_historico'), rh_master_emptime.tot_hrs_e_new = '$inphinext_' WHERE id = '$id_revisa'";
    }else{
        $query_insert = "INSERT INTO [dbo].[rh_master_emptime]
                ([year], [n_semana], [emp_code],
                [f_recorre], [hora_nin], [hora_nout],
                [hora_ein], [hora_eout], [tot_hrs_n],
                [tot_min_n], [tot_hrs_e], [tot_min_e],
                [tot_hrs_n_new], [tot_min_n_new], [tot_hrs_e_new], 
                [tot_min_e_new], [aut_sup], [aut_rh],
                [estatus], [historico], [txt_textra],
                [id_horario_turno], [min_retardo])
            VALUES
                ('$slc_year', '$slc_semana', '$emp_code',
                '$f_recorre', '$Hora_normal', '$Hora_normal_end',
                '$Hora_extra', '$Hora_extra_end', '$hrs_nml',
                '$min_nml', '$hrs_ext', '$min_ext',
                '$inphin_', '$inphout_', '$inphinext_',
                '$inphoutext_', '1', '0',
                '1', '$txt_historico', '$mensaje_te',
                '$shift_id', '$min_retardo')";
    }

    sqlsrv_query($cnx, $query_insert);
        
    $response_btn['msg_success'] = "
    <td style='border: 1px solid;' colspan='4' id='btn_".$emp_code."_".$dia_dia."'>
        <center>
            <div class='alert alert-success' role='alert' style='font-size: small;'>
                Aprobado!
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <button type='button' class='btn btn-success btn-sm' onclick='editar_hrs_extra(`".$dia_dia."`, `".$emp_code."`)'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit-2'><path d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'></path></svg>
                </button>
            </div>
        </center>
    </td>
    ";

    echo json_encode($response_btn);
?>