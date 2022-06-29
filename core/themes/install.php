<?php
$position = array('Instalacion', 'Instalacion del Sistema', 'install');

$theme_script = "";
//$clients_data = api("clients", "list");
?>
<script defer>
    let pagination = <?php echo $config['misc']['pagination'] ?>;
    let position = [];

    position['sub_title'] = '<?php echo $position[0] ?>';
    position['title'] = '<?php echo $position[1] ?>';
    position['var'] = '<?php echo $position[2] ?>';

    var clients_data_api = null;
</script>

<div id="install-user-modal" data-backdrop="static" class="modal fade show" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id='install-user-form' method="post" autocomplete="on">
                <div class="modal-header bg-success text-light">
                    <h4 class="modal-title">Crear Usuario</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label for="user_data" class="ms-3">Usuario:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input autocomplete='username' placeholder="Usuario" type="text" id="username_login" name="username_login" class="form-control form-icon-trailing" required="required" />
                        </div>
                    </div><br>
                    <div class="form-group"><label for="user_data" class="ms-3">Contraseña:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                            <input autocomplete='current-password' placeholder="Contraseña" type="password" id="password_login" name="password_login" class="form-control form-icon-trailing" required="required" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                            <input autocomplete='current-password' placeholder="Repetir Contraseña" type="password" id="password2_login" name="password2_login" class="form-control form-icon-trailing" required="required" />
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <label id='error_user_label' class="alert-danger bootstrap-growl alert p-2 mb-0 mt-0"></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <p>Paso 2 de 2</p>
                    <input type="submit" class="btn btn-success" value="Crear Usuario">
                </div>
            </form>
        </div>
    </div>
</div>

<div id="install-database-modal" data-backdrop="static" class="modal fade show" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id='install-database-form' method="post" autocomplete="on">
                <div class="modal-header bg-success text-light">
                    <h4 class="modal-title">Crear Base de Datos</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group"><label for="user_data" class="ms-3">Usuario:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input autocomplete='username' placeholder="Usuario" value='root' type="text" id="user_data" name="user_data" class="form-control form-icon-trailing" required="required" />
                        </div>
                    </div>
                    <div class="form-group"><label for="pass_data" class="ms-3">Contraseña:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                            <input autocomplete='current-password' placeholder="Contraseña" type="password" id="pass_data" name="pass_data" class="form-control form-icon-trailing" />
                        </div>
                    </div>
                    <div class="form-group"><label for="host_data" class="ms-3">Servidor:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                            <input autocomplete='hostname' placeholder="Servidor" value='127.0.0.1' type="text" id="host_data" name="host_data" class="form-control form-icon-trailing" required="required" />
                        </div>
                    </div>
                    <div class="form-group"><label for="db_data" class="ms-3">Base de datos:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-asterisk"></i></span>
                            <input autocomplete='database' placeholder="Base de datos" value='icc_<?php echo $config['title'] ?>' type="text" id="db_data" name="db_data" class="form-control form-icon-trailing" required="required" />
                        </div>
                    </div>
                    <div class="form-group d-flex justify-content-center">
                        <label id='error_data_label' class="alert-danger bootstrap-growl alert p-2 mb-0 mt-0"></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <p>Paso 1 de 2</p>
                    <input type="submit" class="btn btn-success" value="Crear Base de Datos">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#error_data_label").hide();
    $("#error_user_label").hide();
    $('#install-database-modal').modal({
        backdrop: 'static'
    });

    $("#install-database-form").submit(function(e) {
        e.preventDefault();

        var form_login = $(this);
        var info = [];

        info['log_user'] = form_login[0].user_data.value;
        info['log_pass'] = form_login[0].pass_data.value;
        info['log_host'] = form_login[0].host_data.value;
        info['log_db'] = form_login[0].db_data.value;

        $.ajax({
            method: "POST",
            url: "./install/install.php",
            // Passing all the variables
            data: {
                user: info['log_user'],
                pass: info['log_pass'],
                host: info['log_host'],
                data: info['log_db']
            }
        }).done(function(msg) {

            if (msg.length === 0) {
                $('#install-database-modal').modal('hide');
                $('#install-user-modal').modal({
                    backdrop: 'static'
                });
            } else {

                $("#error_data_label").html(msg);
                $("#error_data_label").show();
            }

        });

        //console.log ( info );
    });

    $("#install-user-form").submit(function(e) {
        e.preventDefault();

        var form_login = $(this);

        var log_user = form_login[0].username_login.value;
        var log_pass = form_login[0].password_login.value;
        var log_pass2 = form_login[0].password2_login.value;


        if (log_pass === log_pass2) {
            $.ajax({
                method: "POST",
                url: "./api/?users&install",
                // Passing all the variables
                data: {
                    username: log_user.toLowerCase(),
                    password: log_pass,
                    password2: log_pass2
                }
            }).done(function(msg) {
                $('#install-user-modal').modal('hide');

                setTimeout(function() {
                    document.location = "./";
                }, 500);

            });
        } else {
            form_login[0].password_login.classList.add("bg-danger", "text-white");
            form_login[0].password2_login.classList.add("bg-danger", "text-white");

            $("#error_user_label").html("Las contraseñas no coinciden");
            $("#error_user_label").show();
        }
    });
</script>