<?php
if (isset($_SESSION['USERID'])) {
    $user_avatar = "./assets/images/" . $_SESSION['AVATAR'] . ".png";
    $user_avatar = "<img class=\"main-avatar rounded-circle\" src=\"$user_avatar\" width=\"27\" />";
    $user_name = ucfirst($_SESSION['USERID']);
} else {
    $user_avatar = "<i class=\"fas fa-user-circle fs-3 float-end\" aria-hidden=\"true\"></i>";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title><?php echo $config['title'] ?></title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name="description" content='Client management system for Tourist Enterprises'>
    <link href="./assets/css/font.awesome.min.css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="./favicon.svg">

    <link href="./assets/css/bootstrap.5.css" rel="stylesheet" />
    <link href="./assets/css/main.css" rel="stylesheet" />
    <link href="./assets/css/flags.css" rel="stylesheet" />
    <link href="./assets/css/font.google.css" rel="stylesheet" />


    <script>
        const last_commit = "<?php echo _COMMIT ?>";
    </script>
    <script type="text/javascript" src="./assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/js/popper.min.js"></script>
    <script type="text/javascript" src="./assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/js/bootstrap-autocomplete.js"></script>
    <script type="text/javascript" src="./assets/js/jquery.bootstrap-growl.js"></script>
</head>

<body>

    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>


    <nav class="navbar navbar-light navbar-expand-md bg-white shadow-lg">
        <div class="container-fluid">
            <button data-toggle="collapse" data-target="#navcol-1" class="navbar-toggler">
                <span class="visually-hidden">Toggle navigation</span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#"><i class="fas fa-plane fs-1 text-primary"></i></a>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" id='nav_link_panel' href="?panel">Panel</a>
                    </li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-toggle="dropdown" href="">Modulos&nbsp;</a>
                        <div class="dropdown-menu align-start">
                            <a class="dropdown-item" id='nav_link_clients' href="?clients"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;Clientes</a>
                            <a class="dropdown-item" id='nav_link_voucher' href="?voucher"><i class="fa fa-building" aria-hidden="true"></i>&nbsp;&nbsp;Reservas</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" id='nav_link_prices' href="?prices"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Listado de Precios</a>
                            <a class="dropdown-item" id='nav_link_agency' href="?agency"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Listado de Agencias</a>

                        </div>
                    </li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-toggle="dropdown" href="#">Herramientas&nbsp;</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Reportes</a>
                            <a class="dropdown-item" href="#">Mensajes / Emails</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Automatizacion</a>

                        </div>
                    </li>

                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" aria-expanded="false" data-toggle="dropdown" href="#">Admin&nbsp;</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" id='nav_link_logs' href="?logs"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;&nbsp;Logs</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" id='nav_link_users' href="?users"><i class="fa fa-user" aria-hidden="true"></i>&nbsp;&nbsp;Usuarios</a>
                        </div>
                    </li>
                </ul>
            </div>
            <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 d-md-flex d-lg-flex d-xl-flex d-xxl-flex flex-shrink-1 navbar-search" style="margin-left: auto; right: 170px; float: right; position: absolute;">
                <div class="input-group shadow-lg">
                    <input class="bg-light form-control border-0 small" type="search" placeholder="Buscar ..." name="main_search" id="main_search" aria-label="Search">
                    <button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="navbar-nav flex-nowrap ms-auto">
                    <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link" aria-expanded="false" data-toggle="dropdown" href="#"><i class="fas fa-search"></i>&nbsp; Buscar</a>
                        <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in" aria-labelledby="searchDropdown">
                            <form class="me-auto d-flex navbar-search w-100">
                                <div class="input-group">
                                    <input class="bg-light form-control border-0 small" type="search" placeholder="Buscar ..." name="main_search" id="main_search" aria-label="Search">
                                    <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="nav-item dropdown no-arrow mx-1">
                        <div class="nav-item dropdown no-arrow mt-1">
                            <a class="float-end nav-link" aria-expanded="false" data-toggle="dropdown" href="#">
                                <span class="badge bg-danger badge-counter alert-counter hide" style="position: absolute; margin-top: -5px; margin-left: 20px;">0</span><i class="fas fa-bell fs-4 fa-fw"></i></a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-list animated--grow-in mt-5 main-alert" style="overflow: hidden; padding-bottom: 0px;">
                                <h6 class="dropdown-header bg-primary color-white" style="border-top-left-radius: 5px; border-top-right-radius: 5px;">Centro de Alertas</h6>
                                <div class="main-alert-element bg-pink">
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="me-3">
                                            <div class="bg-{color} icon-circle">
                                                <i class="fas fa-{type} text-white"></i>
                                            </div>
                                        </div>
                                        <div><span class="small text-gray-500">{date}</span>
                                            <p>{value}</p>
                                        </div>
                                    </a>
                                </div>
                                <a class="dropdown-item text-center small text-gray-500" href="#" style="background: var(--bs-primary);color: var(--bs-white);">Todas las Alertas</a>
                            </div>
                        </div>
                    </li>
                    <li class="nav-item dropdown no-arrow mx-1">
                        <div class="shadow dropdown-list dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown"></div>
                    </li>
                    <li class="nav-item dropdown no-arrow">
                        <div class="nav-item dropdown no-arrow">
                            <a class="float-end nav-link" aria-expanded="false" data-toggle="dropdown" href="#" style="max-width: 100px;">
                                <span class="fw-bold me-2 text-gray-600 small"></span>
                                <span data-tooltip-location="bottom" data-tooltip="<?php echo @$user_name ?>">
                                    <?php echo @$user_avatar ?>
                                </span>
                            </a>
                            <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in mt-5">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                    &nbsp;Perfil</a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>
                                    &nbsp;Ajustes</a>
                                <div class="dropdown-divider"></div><a id="logout_button" class="dropdown-item" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="p-4">
        <h1 class="" id='position_title'>Listado de Clientes</h1>
        <!-- Breadcrumb -->
        <nav class="d-flex">
            <a href="" class="text-reset"><i class="fa fa-home" aria-hidden="true"></i></a>&nbsp;&nbsp;/&nbsp;&nbsp;<a href="" class="text-reset"><u id='position_sub_title'>Clientes</u></a>
        </nav>
        <!-- Breadcrumb -->
    </div>