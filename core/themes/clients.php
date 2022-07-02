<?php
$position = array('Clientes', 'Listado de Clientes', 'clients');

$theme_script = "clients";
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
<!-- Main Table Wrapper -->
<main class="main_wrapper mx-auto flex-shrink-0">
    <div class='d-flex justify-content-end'>
        <?php if (_DEBUG) { ?>
            <div class="loader generate-loader hide"></div>
            <span data-tooltip="Generar cliente de forma aleatoria (DEBUG)">
                <button id="button_generate_client" type="button" class="btn btn-primary m-2 btn-icon-split">
                    <span class="icon text-white"><i class="fas fa-circle-plus"></i></span>
                    <span class="text">Generar Cliente</span>
                </button>
            </span>
        <?php } ?>
        <span data-tooltip="Agregar nuevo cliente">
            <button id="button_client_add" type="button" class="btn btn-success btn-icon-split m-2">
                <span class="icon text-white"><i class="fas fa-circle-plus"></i></span>
                <span class="text">Agregar</span>
            </button>
        </span>
    </div>


    <table id='main-table' class="table table-striped table-responsive table-hover">
        <thead class="table-dark fs-5 text-muted">
            <tr>
                <th></th>
                <th class="align-start" data-order-id='lastname'>Nombre</th>
                <th class="align-start" data-order-id='country'>Pais / Pasaporte</th>
                <th data-order-id='status'>Estado</th>
                <th data-order-id='date_added'>Fecha</th>
                <th data-order-id='company'>Empresa</th>
                <th>Accion</th>
            </tr>
        </thead>
        <tbody id='main-table-body'>
            <!-- Data ROW -->
            <tr class='hide' id='data-default' data-user-id="u01">
                <td style='width: 100px;' class="align-middle"><span class="badge badge-id bg-danger">{id_number}</span>{profile_picture}</td>
                <td style='width: auto;' class="align-start">
                    <p class="fw-bold mb-0"><strong>{prefix}</strong> {name} {lastname}</p>
                    <p class="fw-bolder ms-2 mb-0"><a class='text-info' href='mailto:{email}'>{email}</a></p>
                    <p class="fw-bolder ms-2 mb-0"><a class='text-info' href='tel:{phone}'>{phone}</a></p>
                </td>
                <td class="align-middle align-start">
                    <p class="fw-bolder mb-0"><span data-tooltip="{country} / {country_full}"><span class="fi fi-{country_lowercase} fa-lg rounded" alt="{country_full}"></span></span><span class='ms-2'>{passport}</span></p>
                </td>
                <td style='width: auto;' class="align-middle">
                    <h5><span class="badge bg-{status_type} p-2">{status}</span></h5>
                </td>
                <td style='width: auto;' class="align-middle">{date_added}</td>
                <td style='width: auto;' class="align-middle">{company}</td>
                <td style='width: 150px;' class="align-middle">
                    <div class='hide' id='client-buttons-{id}'>
                        <span data-tooltip="Agregar reserva al cliente">
                            <button id="button_voucher_add" onclick="button_voucher_add(this)" data-user-id="{id}" data-user-name="{full_name}" type="button" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i>
                            </button>
                        </span>
                        <span data-tooltip="Modificar los datos del cliente">
                            <button id="button_user_mod" onclick="button_user_mod(this)" data-user-id="u{id}" type="button" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></button>
                        </span>
                        <span data-tooltip="Eliminar el cliente">
                            <button id="button_user_del" onclick="button_user_del(this)" data-user-id="u{id}" type="button" class="btn btn-danger btn-sm"><i class="fas fa-ban"></i></button>
                        </span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <nav aria-label="...">
        <div class='table-label'>Mostrando <strong id='table-label-min'>0</strong>-<strong id='table-label-max'>0</strong> de un total de <strong id='table-label-total'>0</strong></div>
        <ul id='main_client_pagination' class="pagination pagination-circle m-3 justify-content-end">
        </ul>
    </nav>
</main>
<br><br>