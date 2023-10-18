<?php
    ini_set('display_errors', 1);
    include ('../../php/conn.php');
    date_default_timezone_set("America/Mazatlan");
    $fecha_hora_now = date('Y-m-d H:i:s');
    $inp_id_empleado = $_POST['inp_id_empleado'];
    $inp_id_depto = $_POST['inp_id_depto'];
    $inp_fecha_textra = $_POST['inp_fecha_textra'];
    $slc_tot_te = $_POST['slc_tot_te'];
    $inp_n_orden = $_POST['inp_n_orden'];

    //***Buscamos la orden en EPICOR
    $query1 = "SELECT COUNT(*) AS count1 FROM Erp.JobHead WHERE JobNum = '$inp_n_orden' AND JobClosed != '1'";
    if ($exe1 = sqlsrv_query($cone, $query1)) {
        $fila1 = sqlsrv_fetch_array($exe1, SQLSRV_FETCH_ASSOC);
        $count1 = $fila1['count1'];
        if ($count1 > 0) {
            $query2 = "SELECT * FROM Erp.JobHead WHERE JobNum = '$inp_n_orden' AND JobClosed != '1'";
            $exe2 = sqlsrv_query($cone, $query2);
            $fila2 = sqlsrv_fetch_array($exe2, SQLSRV_FETCH_ASSOC);
            $PartNum_1 = $fila2['PartNum'];
            $PartDescription_1 = str_replace("'", "", $fila2['PartDescription']);
            $SysRowID_1 = $fila2['SysRowID'];
            $query3 = "SELECT [lote_c] FROM [LiveDB].[Erp].[JobHead_UD] WHERE ForeignSysRowID = '$SysRowID_1'";
            $exe3 = sqlsrv_query($cone, $query3);
            $fila3 = sqlsrv_fetch_array($exe3, SQLSRV_FETCH_ASSOC);
            $lote_c = $fila3['lote_c'];

            $query4 = "SELECT COUNT([id]) AS tot_reg FROM [rh_novag_system].[dbo].[rh_te_hrs] WHERE [rh_te_hrs].[num_emp] = '$inp_id_empleado' AND [rh_te_hrs].[fecha_te] = '$inp_fecha_textra'";
            $exe4 = sqlsrv_query($cnx, $query4);
            $fila4 = sqlsrv_fetch_array($exe4, SQLSRV_FETCH_ASSOC);
            $tot_reg = $fila4['tot_reg'];
            if ($tot_reg > 0) {
                echo '
                    0|<br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="card border-danger">
                                <div class="card-body text-danger">
                                    <h5 class="card-title">Ya cuenta con un registro de horas para el dia <strong>'.$inp_fecha_textra.'</strong>.</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
            } else {
                $query5 = "INSERT INTO [dbo].[rh_te_hrs]
                    ([num_orden],[parte],[lote]
                    ,[desc_parte],[fecha_te],[tot_hrs]
                    ,[num_emp],[insert_date])
                VALUES
                    ('$inp_n_orden','$PartNum_1','$lote_c'
                    ,'$PartDescription_1','$inp_fecha_textra','$slc_tot_te'
                    ,'$inp_id_empleado','$fecha_hora_now')";
                
                sqlsrv_query($cnx, $query5);
                echo '
                    1|<br>
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="card border-success">
                                <div class="card-body text-success">
                                    <h5 class="card-title">Se envio la informaci&oacute;n de las horas extra</h5>
                                    <center>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Parte</th>
                                                    <th>Descripci&oacute;n</th>
                                                    <th>Lote</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>'.$PartNum_1.'</td>
                                                    <td>'.$PartDescription_1.'</td>
                                                    <td>'.$lote_c.'</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
            }
        }else{
            echo '
            0|<br>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="card border-danger">
                        <div class="card-body text-danger">
                            <h5 class="card-title">No se encontro informaci&oacute;n de la orden <strong>'.$inp_n_orden.'</strong>.</h5>
                        </div>
                    </div>
                </div>
            </div>
            ';
        }
    }
    //******************************
?>
<div class="row">
    <div class="col-md-9 col-sm-12">
    </div>
    <div class="col-md-3 col-sm-12">
        <br>
        <button class="w-100 btn btn-primary btn-lg" id="btn_clean" onclick="clean_te()">Terminar registro</button>
    </div>
</div>