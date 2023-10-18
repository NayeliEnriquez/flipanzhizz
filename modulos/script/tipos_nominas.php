<?php
    include ('../../php/conn.php');
    $emp_code = $_POST['emp_code'];
    $mov = $_POST['mov'];
    if ($mov == 1) {
        ?>
        <center>
            <select class="form-select form-select-sm" aria-label=".form-select-sm example" name="slc_opcion" id="slc_opcion">
                <option disabled selected>Selecciona una opci&oacute;n</option>
                <option value="1">SEMANA TIZAYUCA</option>
                <option value="2">QUINCENA TIZAYUCA</option>
                <option value="3">SEMANA TLALPAN</option>
                <option value="4">QUINCENA TLALPAN</option>
            </select>
            <input type="hidden" name="inp_emp_code" id="inp_emp_code" value="<?php echo($emp_code); ?>">
        </center>
        <?php
    } else {
        $slc_opcion = $_POST['slc_opcion'];
        switch ($slc_opcion) {
            case '1':
                $desc_opcion = "SEMANA TIZAYUCA";
                break;
            
            case '2':
                $desc_opcion = "QUINCENA TIZAYUCA";
                break;

            case '3':
                $desc_opcion = "SEMANA TLALPAN";
                break;

            case '4':
                $desc_opcion = "QUINCENA TLALPAN";
                break;
        }

        $v_update = "UPDATE [rh_novag_system].[dbo].[rh_employee_gen] SET [NOMINA] = '$desc_opcion' WHERE CLAVE = '$emp_code'";
        sqlsrv_query($cnx, $v_update);
    }
    
?>