<?php
    include ('../php/conn.php');
    $yearr = date('Y');
    $mes = date('m');
    switch ($mes) {
        case '1':
            $mess = 'Enero';
            break;
        
        case '2':
            $mess = 'Febrero';
            break;

        case '3':
            $mess = 'Marzo';
            break;

        case '4':
            $mess = 'Abril';
            break;

        case '5':
            $mess = 'Mayo';
            break;

        case '6':
            $mess = 'Junio';
            break;

        case '7':
            $mess = 'Julio';
            break;

        case '8':
            $mess = 'Agosto';
            break;

        case '9':
            $mess = 'Septiembre';
            break;

        case '10':
            $mess = 'Octubre';
            break;

        case '11':
            $mess = 'Noviembre';
            break;

        case '12':
            $mess = 'Diciembre';
            break;
    }
    $dia = date('d');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="../images/logo_only.png">
        <title>Novag RH | Formatos</title>
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            html {
                min-height: 100%;
                position: relative;
                }
                body {
                margin: 0;
                margin-bottom: 40px;
                }
                footer {
                background-color: black;
                position: absolute;
                bottom: 0;
                width: 100%;
                height: 40px;
                color: white;
                }
        </style>
        <script type="text/javascript" src="../js/formatos.js"></script>
        <!-- include the script -->
        <script src="../js/alertifyjs/alertify.min.js"></script>
        <!-- include the style -->
        <link rel="stylesheet" href="../js/alertifyjs/css/alertify.min.css"/>
        <!-- include a theme -->
        <link rel="stylesheet" href="../js/alertifyjs/css/themes/default.min.css"/>
    </head>
    <body class="text-center">
        <?php
            $name_self = basename($_SERVER['PHP_SELF']);
            include('../php/navs/nav_forms.php');
        ?>
        <div class="container">
            <br><br><br>
            <div class="col-md-12 col-lg-12">
                <h4 class="mb-3">Formato de ausencia</h4>
                <div class="row">
                    <div class="col-md-6 col-sm-12"></div>
                    <div class="col-md-6 col-sm-12">
                        <p class="text-end">Ciudad de M&eacute;xico, a <?php echo($dia); ?> de <?php echo($mess); ?> del <?php echo($yearr); ?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <label for="inp_num_empl" class="form-label">Numero de empleado</label>
                        <!--<input class="form-control" list="datalistOptions" id="inp_num_empl" placeholder="Busqueda..." onkeyup="busca_empleado(event)">-->
                        <input class="form-control" list="datalistOptions" id="inp_num_empl" placeholder="Busqueda..." onkeyup="revisa_pin(this.value, event,'busca_empleado')">
                        <datalist id="datalistOptions">
                            <?php
                                $query1 = "SELECT emp_code, first_name, last_name FROM personnel_employee WHERE enable_att = '1' ORDER BY emp_code ASC";
                                $exe_1 = sqlsrv_query($conn, $query1);
                                while ($fila_1 = sqlsrv_fetch_array($exe_1, SQLSRV_FETCH_ASSOC)) {
                                    $emp_code_1 = $fila_1['emp_code'];
                                    $first_name_1 = trim(utf8_encode($fila_1['first_name']));
                                    $last_name_1 = trim(utf8_encode($fila_1['last_name']));
                                    echo '
                                        <option value="'.$emp_code_1.'">'.$last_name_1.' '.$first_name_1.'</option>
                                    ';
                                }
                            ?>
                        </datalist>
                    </div>
                    <div class="col-md-8 col-sm-12">
                        <br>
                        <div id="respose_srch_dtl_surt"></div>
                    </div>
                </div>
                <!--<form class="needs-validation" novalidate onsubmit="frm_ausencias()" id="frm_formulario">-->
                    <div id="resp_busca_empleado"></div>
                <!--</form>-->
                <!--<form class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-sm-6">
                        <label for="firstName" class="form-label">First name</label>
                        <input type="text" class="form-control" id="firstName" placeholder="" value="" required>
                        <div class="invalid-feedback">
                            Valid first name is required.
                        </div>
                        </div>

                        <div class="col-sm-6">
                        <label for="lastName" class="form-label">Last name</label>
                        <input type="text" class="form-control" id="lastName" placeholder="" value="" required>
                        <div class="invalid-feedback">
                            Valid last name is required.
                        </div>
                        </div>

                        <div class="col-12">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text">@</span>
                            <input type="text" class="form-control" id="username" placeholder="Username" required>
                        <div class="invalid-feedback">
                            Your username is required.
                            </div>
                        </div>
                        </div>

                        <div class="col-12">
                        <label for="email" class="form-label">Email <span class="text-muted">(Optional)</span></label>
                        <input type="email" class="form-control" id="email" placeholder="you@example.com">
                        <div class="invalid-feedback">
                            Please enter a valid email address for shipping updates.
                        </div>
                        </div>

                        <div class="col-12">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" placeholder="1234 Main St" required>
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                        </div>

                        <div class="col-12">
                        <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
                        <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
                        </div>

                        <div class="col-md-5">
                        <label for="country" class="form-label">Country</label>
                        <select class="form-select" id="country" required>
                            <option value="">Choose...</option>
                            <option>United States</option>
                        </select>
                        <div class="invalid-feedback">
                            Please select a valid country.
                        </div>
                        </div>

                        <div class="col-md-4">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="state" required>
                            <option value="">Choose...</option>
                            <option>California</option>
                        </select>
                        <div class="invalid-feedback">
                            Please provide a valid state.
                        </div>
                        </div>

                        <div class="col-md-3">
                        <label for="zip" class="form-label">Zip</label>
                        <input type="text" class="form-control" id="zip" placeholder="" required>
                        <div class="invalid-feedback">
                            Zip code required.
                        </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="same-address">
                        <label class="form-check-label" for="same-address">Shipping address is the same as my billing address</label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="save-info">
                        <label class="form-check-label" for="save-info">Save this information for next time</label>
                    </div>

                    <hr class="my-4">

                    <h4 class="mb-3">Payment</h4>

                    <div class="my-3">
                        <div class="form-check">
                        <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
                        <label class="form-check-label" for="credit">Credit card</label>
                        </div>
                        <div class="form-check">
                        <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
                        <label class="form-check-label" for="debit">Debit card</label>
                        </div>
                        <div class="form-check">
                        <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required>
                        <label class="form-check-label" for="paypal">PayPal</label>
                        </div>
                    </div>

                    <div class="row gy-3">
                        <div class="col-md-6">
                        <label for="cc-name" class="form-label">Name on card</label>
                        <input type="text" class="form-control" id="cc-name" placeholder="" required>
                        <small class="text-muted">Full name as displayed on card</small>
                        <div class="invalid-feedback">
                            Name on card is required
                        </div>
                        </div>

                        <div class="col-md-6">
                        <label for="cc-number" class="form-label">Credit card number</label>
                        <input type="text" class="form-control" id="cc-number" placeholder="" required>
                        <div class="invalid-feedback">
                            Credit card number is required
                        </div>
                        </div>

                        <div class="col-md-3">
                        <label for="cc-expiration" class="form-label">Expiration</label>
                        <input type="text" class="form-control" id="cc-expiration" placeholder="" required>
                        <div class="invalid-feedback">
                            Expiration date required
                        </div>
                        </div>

                        <div class="col-md-3">
                        <label for="cc-cvv" class="form-label">CVV</label>
                        <input type="text" class="form-control" id="cc-cvv" placeholder="" required>
                        <div class="invalid-feedback">
                            Security code required
                        </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button>
                </form>-->
            </div>
        </div>
        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/form-validation.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </body>
    <br>
    <footer class="d-flex flex-wrap justify-content-between align-items-center border-top navbar-dark">
        <div class="col-md-4 d-flex align-items-center">
            <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
                <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
            </a>
            <span class="mb-3 mb-md-0 text-muted">© Copyright <?php echo($yearr); ?></span>
        </div>
        <div class="badge bg-success text-wrap" style="width: 26rem;">
            Cualquier uso indebido de este portal puede ser causa de una sanci&oacute;n.
        </div>
    </footer>
</html>