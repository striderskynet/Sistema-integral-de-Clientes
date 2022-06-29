<!-- ADD CLIENT MODAL -->
<div class="modal fade " id="add_client_modal" tabindex="-1" aria-labelledby="add_client_modal" aria-hidden="true">
    <div class="modal-dialog w-50">
        <form method='post' id="add_client_form" autocomplete="on">
            <div class="modal-content modal-center">
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title">
                        <i class="fa fa-send"></i>Agregar Cliente
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group align-items-center w-100">
                        <div class="container col-md-6 text-center my-auto" id="upload_client_picture" onclick="upload_client_picture()">
                            <i id="upload_client_icon" class="fa fa-user-circle fa-7x my-4" aria-hidden="true"></i>
                            <img id='upload_client_image' src="" class="hide rounded-circle shadow-5-strong" style='width: 150px;'>
                        </div>
                        <input class='hide' type="file" name="acf_client_picture" value="" id='upload_client_file'>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text"><i class="fas fa-user"></i></label>
                            <select class="form-control" style='max-width: 70px;' name='acf_contact_prefix' id="acf_contact_prefix">
                                <option value="Sr." selected>Sr.</option>
                                <option value="Sra.">Sra.</option>
                                <option value="Dr.">Dr.</option>
                                <option value="Msc">MSc</option>
                            </select>


                            <input type="name" id="acf_contact_name" placeholder="Nombre" name="acf_contact_name" class="form-control" required />
                            <label class="input-group-text"><i class="fas fa-link"></i></label>
                            <input type="lastname" id="acf_contact_lastname" placeholder="Apellidos" name="acf_contact_lastname" class="form-control" required />
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text"><i class="fas fa-phone"></i></label>
                            <input type="tel" id="acf_contact_phone" placeholder="Telefono" name="acf_contact_phone" class="form-control" />

                            <label class="input-group-text"><i class="fas fa-envelope"></i></label>
                            <input type="email" id="acf_contact_email" placeholder="Email" name="acf_contact_email" class="form-control" required />
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text"><i class="fas fa-link"></i></label>
                            <input type="text" id="acf_contact_passport" placeholder="Pasaporte" name="acf_contact_passport" class="form-control form-icon-trailing" />

                            <label class="input-group-text"><i class="fas fa-flag"></i></label>
                            <select class="form-control" id="acf_contact_country" name='acf_contact_country' value='DE' text="Pais / Country" id="acf_contact_country" class='form-select' required>
                                <option value="DE" selected>Pais / Country</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="input-group">

                            <label class="input-group-text"><i class="fas fa-building"></i></label>
                            <input type="text" id="acf_contact_company" placeholder="Empresa" name="acf_contact_company" class="form-control" />

                            <label class="input-group-text"><i class="fas fa-flag"></i></label>
                            <select id="acf_contact_status" name='acf_contact_status' value="Unknown" text="Estado" class="form-select form-control">
                                <option selected disabled>Estado</option>
                            </select>

                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="input-group">

                            <label class="input-group-text"><i class="fas fa-address-card"></i></label>
                            <input type="address" id="acf_contact_address" placeholder="Direccion" name="acf_contact_address" class="form-control" />
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text"><i class="fas fa-building"></i></label>
                            <select id="acf_contact_agency" name='acf_contact_agency' value="Unknown" text="Estado" class="form-select form-control">
                                <option value='0' selected>Directo</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text"><i class="fas fa-flag"></i></label>
                            <textarea id="acf_contact_observations" placeholder="Informacion" name="acf_contact_observations" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-primary">ENVIAR</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- ADD VOUCHER MODAL -->
<div class="modal fade" id="add_voucher_modal" tabindex="-1" aria-labelledby="add_voucher_modal" aria-hidden="true">
    <div class="modal-dialog">
        <form method='post' id="add_voucher_form" autocomplete="on">
            <div class="modal-content modal-center">
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title">
                        <i class="fa fa-send"></i> Agregar Reserva
                    </h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="input-group">
                            <span id="mres_client_name_button" class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="name" id="mres_client_name" name="avf_client_name" class="form-control basicAutoComplete" placeholder="Nombre del Cliente" autocomplete="off" required />
                            <input type="hidden" id="mres_client_name_id" name="avf_client_id" />

                        </div>
                        <br>
                        <label>Acompanantes:</label> <span style="position: absolute;right: 30px;">
                            <button id='add_voucher_companion' type="button" class='btn btn-primary btn-sm'><i class="fa fa-plus"></i></button>
                            <button id='remove_voucher_companion' type="button" class='btn btn-danger btn-sm'><i class="fa fa-minus"></i></button>
                        </span>
                        <div class="form-group">
                            <div id='voucher_companion_div'>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text" for="inputGroupSelect01">ID</label>
                            <input class="form-control priceAutoComplete" name="avf_type" placeholder="Escojer la reserva" autocomplete="off" required />

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-floating">
                            <textarea class="form-control p-1" placeholder="Detalles" id="avf_data" name="avf_data" style="resize:both; height: 100px;" required></textarea>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="input-group d-flex">
                            <div class="date_picker input-group date" id="datepicker">
                                <input type="date" class="form-control" name="avf_inDate" id="avf_inDate" title="Fecha de Entrada" autocomplete="off" required>

                                <input type="date" class="form-control" name="avf_outDate" id="avf_outDate" title="Fecha de Salida" autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-edit"></i></span>
                            <input type="text" id="avf_servicePartner" name="avf_servicePartner" class="form-control" placeholder="Prestatario del Servicio">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <label class="input-group-text"><i class="fas fa-flag"></i></label>
                            <textarea class="form-control" placeholder="Informacion" name="avf_details" id="avf_information" style="resize:both;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
                            <input type="text" name="avf_confirmationNumber" class="form-control" placeholder="Numero de Confirmacion" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-primary">ENVIAR</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- SHOW CLIENT MODAL CARD -->
<div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientModalLabel">Visualizar cliente</h5>
            </div>
            <div class="modal-body" id="clientModalBody">

                <!-- Tabs navs -->
                <ul class="nav nav-tabs mb-3" id="ex-with-icons" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-color="blue" id="ex-with-icons-tab-1" data-bs-toggle="tab" href="#ex-with-icons-tabs-1" role="tab" aria-controls="ex-with-icons-tabs-1" aria-selected="true"><i class="fas fa-user-alt fa-fw me-2"></i> Info</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link disabled" data-color="red" id="ex-with-icons-tab-2" data-bs-toggle="tab" href="#ex-with-icons-tabs-2" role="tab" aria-controls="ex-with-icons-tabs-2" aria-selected="false" disabled="disabled"><i class="fas fa-chart-line fa-fw me-3"></i> Registro</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link disabled" data-color="green" id="ex-with-icons-tab-3" data-bs-toggle="tab" href="#ex-with-icons-tabs-3" role="tab" aria-controls="ex-with-icons-tabs-3" aria-selected="false" disabled="disabled"><i class="fas fa-list fa-fw me-2"></i> Detalles</a>
                    </li>
                </ul>
                <!-- Tabs navs -->

                <!-- Tabs content -->
                <div class="tab-content" id="ex-with-icons-content">
                    <div class="tab-pane fade show active" id="ex-with-icons-tabs-1" role="tabpanel" aria-labelledby="ex-with-icons-tab-1">
                        <section class="w-auto" style="background-color: #f4f5f7;">
                            <div class="container py-5">
                                <div class="row d-flex justify-content-center align-items-center h-100">
                                    <div class="col  mb-4 mb-lg-0 no-flex">
                                        <div class="card mb-3" style="border-radius: .5rem;">
                                            <div class="row g-0">
                                                <div class="col-md-4 gradient-custom  align-items-center justify-content-center text-center text-white" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                                    {profile_picture}
                                                    <h5>{prefix}</h5>
                                                    <h5>{full_name}</h5>
                                                    <p>{company}</p>
                                                    <p>{country} / {country_full}<br><i class="fi fi-{country_lowercase} mb-4"></i></p>
                                                    <i class="far fa-edit"></i>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body p-4">
                                                        <h6>Informacion</h6>
                                                        <hr class="mt-0 mb-4">
                                                        <div class="row pt-1">
                                                            <div class="col-6 mb-3">
                                                                <h7 class='detail_info'>Email</h7 class='detail_info'>
                                                                <p class="text-muted"><a href="mailto:{email}">{email}</a></p>
                                                                <h7 class='detail_info'>Fecha</h7 class='detail_info'>
                                                                <p class="text-muted">{date_added}</p>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <h7 class='detail_info'>Telefono</h7 class='detail_info'>
                                                                <p class="text-muted"><a href="tel:{phone}">{phone}</a></p>
                                                                <h7 class='detail_info'>Pasaporte</h7 class='detail_info'>
                                                                <p class="text-muted">{passport}</p>
                                                            </div>
                                                        </div>
                                                        <h6>Detalles</h6>
                                                        <hr class="mt-0 mb-4">
                                                        <div class="row pt-1">
                                                            <div class="col-6 mb-3">
                                                                <h7 class='detail_info'>Observaciones</h7 class='detail_info'>
                                                                <p class="text-muted">{observations}</p>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <h7 class='detail_info'>Estado</h7 class='detail_info'><br><br>
                                                                <h5><span class="p-2 badge bg-{status_type} d-inline">{status}</span></h5>
                                                            </div>
                                                        </div>
                                                        <div class="row pt-1">
                                                            <div class="col-6 mb-3">
                                                                <h7 class='detail_info'>Direccion</h7 class='detail_info'>
                                                                <p class="text-muted">{address}</p>
                                                            </div>
                                                            <div class="col-6 mb-3">
                                                                <h7 class='detail_info'>Agencia / Operadora</h7 class='detail_info'>
                                                                <p class="text-muted">{agency_full}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="tab-pane fade" id="ex-with-icons-tabs-2" role="tabpanel" aria-labelledby="ex-with-icons-tab-2">
                        Registry Content
                    </div>
                    <div class="tab-pane fade" id="ex-with-icons-tabs-3" role="tabpanel" aria-labelledby="ex-with-icons-tab-3">
                        Details Content
                    </div>
                </div>
                <!-- Tabs content -->

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>