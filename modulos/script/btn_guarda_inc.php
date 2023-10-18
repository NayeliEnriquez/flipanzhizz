<?php
    /*while($post = each($_POST)){
		echo $post[0]." = ".$post[1]."||";
	}
    echo "||||Nombre: " . $_FILES['inp_file']['name'] . "<br>";//NOMBRE DEL ARCHIVO
  	echo "||||Tipo: " . $_FILES['inp_file']['type'] . "<br>";//TIPO DE ARCHIVO
  	echo "||||Tamaño: " . ($_FILES["inp_file"]["size"] / 1024) . " kB<br>";//TAMAÑAN DE ARCHIVO
  	echo "||||Carpeta temporal: " . $_FILES['inp_file']['tmp_name'];//NOMBRE EN LA CARPETA TEMPORAL DE PHP*/

    include ('../../php/conn.php');
    session_start();
    $num_empleado_session = $_SESSION['num_empleado_a'];
    $name_session = $_SESSION['name_a'];
    /*while($post = each($_SESSION)){
		echo $post[0]." = ".$post[1]."||";
	}*/
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');

    $inp_id_empleado = $_POST['inp_id_empleado'];
    $inp_id_depto = $_POST['inp_id_depto'];
    $inp_fname = $_POST['inp_fname'];
    $inp_lname = $_POST['inp_lname'];
    $inp_depto = $_POST['inp_depto'];
    $inp_pos = $_POST['inp_pos'];
    $inp_fini = $_POST['inp_fini'];
    $inp_ffin = $_POST['inp_ffin'];
    $inp_tdias = $_POST['inp_tdias'];
    $txt_obs = $_POST['txt_obs'];

    $file_name = $_FILES['inp_file']['name'];
    $file_type = $_FILES['inp_file']['type'];
    $file_size = ($_FILES['inp_file']['size'] / 1024);
    $file_temp = $_FILES['inp_file']['tmp_name'];

    $ruta_destino = "../../files/incapacidades/".$inp_id_empleado."-".$file_name;

    if (!empty($file_name)) {
      if ($file_size > 25000) {
            echo 2;//***Archivo demasiado grande
            exit();
      }
      if ($file_type != 'application/pdf') {
            echo 3;//***Archivo no es PDF
            exit();
      }
        move_uploaded_file($file_temp, $ruta_destino);
    }

    $query_a = "SELECT id FROM personnel_employee WHERE emp_code = '$inp_id_empleado'";
    $exe_a = sqlsrv_query($conn, $query_a);
    $fila_a = sqlsrv_fetch_array($exe_a, SQLSRV_FETCH_ASSOC);
    $id_1 = $fila_a['id'];

    $id_zks = "";

    $sql_zk1 = "INSERT INTO [dbo].[workflow_abstractexception]
            ([audit_status])
        VALUES
            ('1');
        SELECT SCOPE_IDENTITY()";
    if ($exe_zk1 = sqlsrv_query($conn, $sql_zk1)) {
        sqlsrv_next_result($exe_zk1); 
        sqlsrv_fetch($exe_zk1);
        $abstractexception_ptr_id = sqlsrv_get_field($exe_zk1, 0);
    }

    $id_zks = $abstractexception_ptr_id;

    $sql_zk2 = "INSERT INTO [dbo].[att_leave]
            ([abstractexception_ptr_id],[start_time],[end_time]
            ,[type],[apply_reason],[apply_time]
            ,[audit_time],[vacation_number]
            ,[category_id],[employee_id])
        VALUES
            ('$abstractexception_ptr_id','$inp_fini 00:00:00.000','$inp_ffin 23:00:00.000'
            ,'1','INCAPACIDAD','$fecha_hora_now'
            ,'$fecha_hora_now','0'
            ,'1','$id_1')";
    sqlsrv_query($conn, $sql_zk2);
    

    $sql_insert_rh = "INSERT INTO [dbo].[rh_solicitudes]
            ([id_empleado],[tipo_ausencia],[tipo_goce]
            ,[f_ini],[f_fin],[observaciones]
            ,[f_solicitud],[id_solicitante],[id_depto]
            ,[estatus],[historico]
            ,[tipo_permiso],[abstractexception_ptr_id]
            ,[tot_dias_inc],[file_evidencia])
        VALUES
            ('$inp_id_empleado','Permiso','Sin goce de sueldo'
            ,'$inp_fini','$inp_ffin','$txt_obs'
            ,'$fecha_hora_now','$num_empleado_session','$inp_id_depto'
            ,'1','Solicitud creada y aceptada por $name_session con fecha $fecha_hora_now'
            ,'3','$id_zks'
            ,'$inp_tdias','files/incapacidades/$inp_id_empleado-$file_name');
        SELECT SCOPE_IDENTITY()";
    if ($exe_insert_rh = sqlsrv_query($cnx, $sql_insert_rh)) {
        sqlsrv_next_result($exe_insert_rh); 
        sqlsrv_fetch($exe_insert_rh);
        $id_ausencia = sqlsrv_get_field($exe_insert_rh, 0);
    }
    echo '
        <button type="button" class="btn btn-info" title="Descargar formato" onclick="download_formatos(`rh_solicitudes`, '.$id_ausencia.', '.$inp_id_empleado.')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
        </button>
    ';
?>