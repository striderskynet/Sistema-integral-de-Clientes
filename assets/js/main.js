// CLIENTS STATUS VARIABLE //
var C_status = [
  ["Unknown", "Desconocido", "danger"],
  ["Arrived", "en Cuba", "success"],
  ["Overseas", "en el Extranjero", "warning"],
  ["Arriving", "Llegando", "primary"],
  ["Traveling", "Viajando", "info"],
];

// SOME MODAL DEFINITIONS //
var default_clientModalBody = null;
var comp = 1;
var offset = 1;

// COOKIES FUNCTIONS //
function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  let expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function deleteAllCookies() {
  var cookies = document.cookie.split(";");

  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i];
    var eqPos = cookie.indexOf("=");
    var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
  }
}

// Function to set the actual position in the NavBar
function set_position(login = false) {
  document.title = document.title + " - " + position["title"];
  document.getElementById("position_title").innerHTML = position["title"];
  document.getElementById("position_sub_title").innerHTML = position["sub_title"];

  if (login === false) document.getElementById("nav_link_" + position["var"]).classList.add("active");
}

// Function show_alert with a modified BootStrapGrowl
function show_alert(type, message, timer = 10) {
  console.log('Mostrando alerta de tipo "' + type + '" con mensaje "' + message + '"');

  $.bootstrapGrowl(message, {
    type: type,
    width: "auto",
    offset: { from: "top", amount: 80 },
    align: "right",
    delay: timer * 1000,
  });
}

// Tokenize Function (Data Array to Replace, Data)
const tokenize = (rep_array, value) => {
  for (var key in rep_array) {
    if (key === "status") {
      switch (rep_array[key].toLowerCase()) {
        case C_status[1][0].toLowerCase():
          rep_array[key] = C_status[1][1];
          break;
        case C_status[2][0].toLowerCase():
          rep_array[key] = C_status[2][1];
          break;
        case C_status[3][0].toLowerCase():
          rep_array[key] = C_status[3][1];
          break;
        case C_status[4][0].toLowerCase():
          rep_array[key] = C_status[4][1];
          break;
        default:
          rep_array[key] = C_status[0][1];
          break;
      }
    }

    value = value.replaceAll("{" + key + "}", rep_array[key]);
  }

  return value;
};

function populate_data(clients_data, offset = 1, m_table, m_table_row, type = "client", body = "main-table-body") {
  m_table[0].innerHTML = "";

  pag_level = Math.ceil(clients_data["info"][0].total / pagination);

  show_total(clients_data["info"][0].total, pagination, offset);
  generate_pagination(pag_level, offset, type);

  delete clients_data.info;

  if (Object.keys(clients_data).length === 0) {
    const error_row = document.createElement("td");
    error_row.classList.add("alert-danger", "text-center");
    error_row.style = "padding: 10px;";

    error_row.colSpan = m_table_row[0].cells.length;
    /*switch (type){
        case "client": error_row.colSpan=7; break;
        case "voucher": error_row.colSpan=8; break;
    }*/

    error_row.innerHTML = "No existen elementos...";
    m_table.append(error_row);
    return false;
  }

  $q = 0;
  Object.keys(clients_data).forEach((key) => {
    const new_row = document.createElement("tr");

    switch (type) {
      case "client":
        new_row.id = "data_u" + $q;
        new_row.dataset.userId = "u" + clients_data[key].id;
        new_row.setAttribute("onclick", "show_client_modal(" + clients_data[key].id + ")");
        clients_data[key].status_type = status_type(clients_data[key].status);
        clients_data[key].full_name = clients_data[key].prefix + " " + clients_data[key].name + " " + clients_data[key].lastname;
        clients_data[key].country_lowercase = clients_data[key].country.toLowerCase();
        clients_data[key].country_full = C.countries[clients_data[key].country];
        if (clients_data[key].id.toString().length <= 2) clients_data[key].id_number = ("00" + clients_data[key].id).slice(-3);
        else clients_data[key].id_number = clients_data[key].id;
        break;

      case "voucher":
        new_row.dataset.voucherId = "v" + clients_data[key].voucher_id;
        clients_data[key].data = nl2br(clients_data[key].data, false);
        clients_data[key].profile_picture = '<a class="text-dark" onclick="show_client_modal(' + clients_data[key].id + ")\" href='#'>" + clients_data[key].profile_picture + "</a>";
        clients_data[key].additional_clients = show_companions(clients_data[key].companions);
        break;

      case "prices":
      case "agency":
        new_row.id = "data_p" + $q;
        new_row.setAttribute("onclick", `select_tr(this, ${clients_data[key].id}, event)`);
        new_row.dataset.priceId = clients_data[key].id;
        clients_data[key].type = clients_data[key].type.charAt(0).toUpperCase() + clients_data[key].type.slice(1);
        break;

      default:
        break;
    }

    let in_html = m_table_row[0].innerHTML;

    in_html = tokenize(clients_data[key], in_html);

    new_row.innerHTML = in_html;
    m_table.append(new_row);

    m_table = $("#" + body);

    $q++;
  });
  console.log("Populating database");

  $("tr").on("mouseenter mouseleave", function () {
    if ($(this).attr("data-user-id")) {
      let valID = $(this).attr("data-user-id").replace("u", "");
      $("#client-buttons-" + valID).fadeToggle(300);
    }

    if ($(this).attr("data-voucher-id")) {
      let valID = $(this).attr("data-voucher-id").replace("v", "");
      $("#voucher-buttons-" + valID).fadeToggle(300);
    }
  });
}

function show_client_modal(id) {
  if (clientModalShow == true) {
    $.get("./api/?clients&show&id=" + id, function (data) {
      modal_data = JSON.parse(data);

      let in_html = clientModalBody.innerHTML;
      default_clientModalBody = in_html;

      modal_data.status_type = status_type(modal_data.status);
      modal_data.country_lowercase = modal_data.country.toLowerCase();
      modal_data.full_name = modal_data.name + " " + modal_data.lastname;
      modal_data.country_full = C.countries[modal_data.country];

      if (modal_data.agency != null) {
        $.get("./api/?prices&list&table=price_agency&wh=WHERE id=" + modal_data.agency, function (data) {
          modal_data.agency_data = JSON.parse(data);
          modal_data.agency_full = modal_data.agency_data[0].name + ": " + modal_data.agency_data[0].contact_email;

          in_html = tokenize(modal_data, in_html);

          clientModalBody.innerHTML = in_html;
          clientModalLabel.show();
        });
      } else {
        modal_data.agency_full = "Directo";

        in_html = tokenize(modal_data, in_html);

        clientModalBody.innerHTML = in_html;
        clientModalLabel.show();
      }

      console.log("Mostrar modal con Usuario: " + id);
      //console.log(modal_data.agency_data);
    });
  }
}

//function populate_agency(elem, data) {
const populate_agency = (elem, data) => {
  let list = $(elem);
  list.html("");

  delete data.info;

  Object.keys(data).forEach((key) => {
    let new_option = document.createElement("option");
    new_option.value = data[key].id;
    new_option.innerHTML = data[key].name + ": " + data[key].type;

    list.append(new_option);
  });
};

function randomDate(start, end) {
  return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
}

//function button_voucher_add(button) {
const button_voucher_add = (button) => {
  clientModalShow = false;

  add_voucher_modal.show();

  let id = button.dataset.userId;
  let name = button.dataset.userName;

  select_name("#mres_client_name", name, id);

  setTimeout(function () {
    clientModalShow = true;
  }, 200);
};

const getAgency = (id) => {
  $.get(`./api/?prices&list&table=price_agency&wh=WHERE id=${id}`);
};

//function nl2br(str, is_xhtml) {
const nl2br = (str, is_xhtml) => {
  if (typeof str === "undefined" || str === null) {
    return "";
  }
  var breakTag = is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
  $res = (str + "").replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, "$1" + breakTag + "$2");

  if ($res.length > 30) $res = $res.substring(0, 20) + "...";

  return $res;
};

function show_companions(companions) {
  var ret = "";

  if (companions) {
    companions.forEach((val) => {
      ret += ` <span data-tooltip="${val.name}"><a href="#" onclick="show_client_modal(${val.id})" class="client-sidemen">${val.profile_picture}</a></span>`;
    });
  } else {
    ret = "Ninguno";
  }

  return ret;
}

function status_type(status) {
  let status_type;
  switch (status.toLowerCase()) {
    case C_status[1][0].toLowerCase():
      status_type = C_status[1][2];
      break;
    case C_status[3][0].toLowerCase():
      status_type = C_status[3][2];
      break;
    case C_status[2][0].toLowerCase():
      status_type = C_status[2][2];
      break;
    case C_status[4][0].toLowerCase():
      status_type = C_status[4][2];
      break;
    default:
      status_type = C_status[0][2];
      break;
  }
  return status_type;
}

//Generate the pagination based of total pages, and the offset
function generate_pagination(total_pages, offset, type = "client") {
  // Getting the pagination start DOM
  pag_nav = $("#main_" + type + "_pagination");
  pag_nav.empty();

  // Creating pagination "BACK" element
  let pag_nav_back = document.createElement("li");
  pag_nav_back.classList.add("page-item");

  let pag_nav_back_link = document.createElement("a");
  pag_nav_back_link.classList.add("page-link");
  pag_nav_back_link.innerHTML = "Atras";

  pag_nav_back.append(pag_nav_back_link);

  // Populating the pagination
  if (offset > 1) pag_nav_back.setAttribute("onclick", "pag_offset(" + (offset - 1) + ")");

  let pag_nav_foward = document.createElement("li");
  pag_nav_foward.classList.add("page-item");

  let pag_nav_foward_link = document.createElement("a");
  pag_nav_foward_link.classList.add("page-link");
  pag_nav_foward_link.innerHTML = "Siguiente";

  pag_nav_foward.append(pag_nav_foward_link);

  if (offset != total_pages) pag_nav_foward.setAttribute("onclick", "pag_offset(" + (offset + 1) + ")");

  pag_nav.append(pag_nav_back);

  for (q = 1; q <= total_pages; q++) {
    let pag_nav_q = document.createElement("li");
    let pag_nav_q_link = document.createElement("a");

    pag_nav_q.classList.add("page-item");

    if (q == offset) pag_nav_q.classList.add("active");
    else pag_nav_q.setAttribute("onclick", "pag_offset(" + q + ")");

    pag_nav_q_link.id = "pag_offset";
    pag_nav_q_link.classList.add("page-link");
    pag_nav_q_link.innerHTML = q;

    pag_nav_q.append(pag_nav_q_link);

    if ((q === 1 || q === total_pages) && q !== offset) {
      if (offset > 3 && q === 1) {
        pag_nav_q_link.innerHTML = "Primera...";
        pag_nav_q_link.title = "Ir a la primera pagina";
        pag_nav_q_link.classList.add("btn-white", "text-primary");
      } else if (offset < total_pages - 3 && q === total_pages) {
        pag_nav_q_link.innerHTML = "...Ultima";
        pag_nav_q_link.title = "Ir a la ultima pagina";
        pag_nav_q_link.classList.add("btn-white", "text-primary");
      }
    }

    if (q === 1 || (q >= offset - 2 && q <= offset + 2) || q === total_pages) pag_nav.append(pag_nav_q);
  }

  pag_nav.append(pag_nav_foward);
  //console.log(total_pages)
  if (total_pages <= 1) pag_nav.hide();
  else pag_nav.show();
}

// get the data depending the offset of the table
function pag_offset(offset, type = "client") {
  let search_value = $("#main_search")[0].value;

  $.get("./api/?" + position["var"] + "&list&pagination=" + pagination + "&offset=" + offset + "&data=" + search_value, function (data) {
    switch (position["var"]) {
      default:
        populate_data(JSON.parse(data), offset, main_table, main_table_row);
        break;
      case "prices":
        //console.log(data);
        $("#data-check-all").prop("checked", false);
        populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "prices");
        editable_table_reload();
        break;
    }
  });
}

function editable_table_reload(table = "price_list") {
  $(".editable").dblclick(function (e) {
    $(e.currentTarget.children[0]).toggle();
    $(e.currentTarget.children[1]).toggle();
    $(e.currentTarget.children[1]).focus();
  });

  $(".editable").keydown(function (event) {
    var id = event.key || event.which || event.keyCode || 0;
    if (id === "Enter") {
      $(event.currentTarget.children[0]).text($(event.target).val());
      $(event.currentTarget.children[0]).parent().addClass("bg-warning text-white font-weight-bold");
      $(event.currentTarget.children[0]).toggle();
      $(event.target).toggle();

      var query = `UPDATE \`${table}\` SET \`${event.currentTarget.dataset.type}\`='${$(event.target).val()}' WHERE \`id\`='${event.currentTarget.parentElement.dataset.priceId}'`;

      $.get("./api/?query&query=" + query, function (data) {
        //console.log(data);
      });
    }

    if (id === "Escape") {
      $(event.currentTarget.children[0]).toggle();

      $(event.target).toggle();
    }
  });

  $(".editable input").hide();
}
function show_total(total_results, pagination, offset) {
  let table_label_total = $("#table-label-total")[0];
  let table_label_min = $("#table-label-min")[0];
  let table_label_max = $("#table-label-max")[0];

  let total_min = pagination * (offset - 1);
  let total_max = pagination * offset;

  if (total_max >= total_results) total_max = total_results;

  table_label_min.innerHTML = total_min;
  table_label_max.innerHTML = total_max;
  table_label_total.innerHTML = total_results;

  // if TOTAL is less than the pagination minimun, hide the pagination
  if (total_results == 0) $(".table-label").hide();
  else $(".table-label").show();
}

function select_name(element, name, id) {
  $(element + "_autocomplete").empty();

  $(element + "_button")
    .removeClass("input-group-text")
    .addClass("btn btn-danger");
  $(element + "_button").attr("onclick", 'deselect("' + element + '")');
  $(element + "_button").attr("title", "Cambiar cliente");

  $(element)[0].value = name;
  $(element + "_id")[0].value = id;

  $(element)[0].disabled = true;

  console.log("Selecting client " + name + " with ID: " + id);
}

function deselect(element) {
  $(element + "_button")
    .removeClass("btn btn-danger")
    .addClass("input-group-text");
  $(element + "_button").attr("title", "");
  $(element)[0].disabled = false;
  $(element)[0].value = "";
}

function autocomplete_clients(element) {
  $("#" + element.id + "_autocomplete").empty();

  if (element.value.length > 2) {
    $.get("./api/?clients&list_min&data=" + element.value, function (data) {
      data = JSON.parse(data);
      Object.keys(data).forEach((key) => {
        main_element = document.createElement("div");
        main_element.classList.add("auto-element", "p-2");
        let full_name = data[key].name + " " + data[key].lastname;
        main_element.innerHTML = full_name;

        main_element.setAttribute("onclick", 'select_name("#' + element.id + '", "' + full_name + '", ' + data[key].id + " )");

        $("#" + element.id + "_autocomplete").append(main_element);
      });
    });
  }
}

function reload_autocomplete() {
  $(".basicAutoComplete").autoComplete({
    resolver: "custom",
    events: {
      search: function (qry, callback) {
        // let's do a custom ajax call
        $.ajax("./api/?clients&list_min", {
          data: { q: qry },
        }).done(function (res) {
          callback(JSON.parse(res));
        });
      },
    },
  });

  $(".basicAutoComplete").on("autocomplete.select", function (evt, item) {
    select_name("#" + this.id, item.text, item.value);
  });
}

main_alert = $(".main-alert");
alert_elem = $(".main-alert .main-alert-element");
alert_counter = $(".alert-counter");

var new_alert = alert_elem.html();

var alert_count = 0;
alert_elem.html("");

function alert(type = "normal", color = "default", value = "default", date = "default") {
  var data = [];

  if (type === "check") {
    if (alert_count > 0) alert_elem.html('<a class="dropdown-item d-flex align-items-center" href="#">No hay ninguna alerta</a>');
  } else {
    data["type"] = type;
    data["value"] = value;
    data["date"] = date;
    data["color"] = color;

    alert_elem.html(alert_elem.html() + tokenize(data, new_alert));

    alert_count++;
    alert_counter.html(alert_count);

    if (alert_count > 0) alert_counter.show();
    else alert_counter.hide();
  }
}

const select_item = (id, no) => {
  //function select_item(id, no) {
  if ($("#" + id).hasClass("price-selected")) {
    $("#" + id).removeClass("price-selected");
    $("#data" + dTable + "-check-" + no).prop("checked", false);
    delete selected_items.splice(selected_items.indexOf(no), 1);
  } else {
    $("#" + id).addClass("price-selected");
    $("#data" + dTable + "-check-" + no).prop("checked", true);
    selected_items.push(no);
    lastSelectedItem = [id, no];
  }
};

//function select_tr(element, no, event, suffix = "data_p") {
const select_tr = (element, no, event, suffix = "data_p") => {
  id = element.id.replace(suffix, "");
  //element.classlist.add("bg-primary");
  //console.log($("#data-check-" + no));

  if (event.shiftKey) {
    //console.log(lastSelectedItem);
    lastID = lastSelectedItem[0].replace(suffix, "");
    lastNO = lastSelectedItem[1];

    //console.log(`Selecting from ${lastID} to ${id}`);

    r = 0;
    for (q = parseInt(lastID) + 1; q <= id; q++) {
      select_item(`${suffix}${q}`, no - r);
      r++;
    }
  } else {
    select_item(element.id, no);
  }

  var len = selected_items.length;
  $("#delete_price_button span strong").html(len);
  $("#duplicate_price_button span strong").html(len);
  $("#copy_price_button span strong").html(len);

  if (len > 0) {
    $("#delete_price_button").fadeIn();
    $("#duplicate_price_button").fadeIn();
    $("#copy_price_button").fadeIn();
  } else {
    $("#delete_price_button").fadeOut();
    $("#duplicate_price_button").fadeOut();
    $("#copy_price_button").fadeOut();
  }
};

//function clear_selected() {
const clear_selected = () => {
  selected_items = [];
  var len = selected_items.length;
  $("#delete_price_button span strong").html(len);
  $("#duplicate_price_button span strong").html(len);
  $("#copy_price_button span strong").html(len);

  $("#delete_price_button").hide();
  $("#duplicate_price_button").hide();
  $("#copy_price_button").hide();

  $(".data-check-all").prop("checked", false);

  $("[id^='data" + dTable + "-check']").each(function () {
    $(this).prop("checked", false);
    $(this).parent().parent().removeClass("price-selected");
  });
};

$(".data-check-all").click(function (event) {
  if ($(this).is(":checked")) {
    $("[id^='data" + dTable + "-check-'").prop("checked", true);
    $("[id^='data" + dTable + "-check-'").each(function (event2) {
      if ($(this).attr("id") != "data-check-all") {
        if (event.shiftKey == true) {
          let elem = document.getElementById($(this).parents("tr").attr("id"));
          select_tr(
            elem,
            $(this)
              .attr("id")
              .replace("data" + dTable + "-check-", ""),
            elem
          );
        } else {
          if ($(this).parents("tr").hasClass("price-selected") === false) {
            let elem = document.getElementById($(this).parents("tr").attr("id"));
            select_tr(
              elem,
              $(this)
                .attr("id")
                .replace("data" + dTable + "-check-", ""),
              elem
            );
          }
        }
      }
    });
  } else {
    $("[id^='data" + dTable + "-check-'").prop("checked", false);
    $("[id^='data" + dTable + "-check-'").each(function (event) {
      if ($(this).attr("id") != "data-check-all") {
        let elem = document.getElementById($(this).parents("tr").attr("id"));
        select_tr(
          elem,
          $(this)
            .attr("id")
            .replace("data" + dTable + "-check-", ""),
          event
        );
      }
    });
  }
});
// END OF FUNCTIONS ---- START OF ACTIONS

// On Logout Button Click just LOGOUT and Reload
$("#logout_button").click(function () {
  $.get("./api/logout.php");
  console.log("Login out user");

  setTimeout(function () {
    document.location = "./";
  }, 1 * 500);
});

// Clear ClientModal and restoring
$("#clientModal").on("hidden.bs.modal", function () {
  clientModalBody.innerHTML = default_clientModalBody;
  console.log("Cerrando modal y restableciendo por defecto...");
});

// On Remove Companion inside Modal
$("#remove_voucher_companion").click(function () {
  //console.log( "#companion_div_element_" + (comp - 1) );
  var companion_div = $("#companion_div_element_" + (comp - 1));
  var companion_div_auto = $("#avf_companion_" + (comp - 1) + "_autocomplete");
  companion_div.remove();
  companion_div_auto.remove();

  if (comp > 1) {
    comp--;
  }
});

// On Add Companion inside Modal
$("#add_voucher_companion").click(function () {
  console.log("Adding voucher companion");

  var companion_div = $("#voucher_companion_div");

  var companion_input = document.createElement("input");
  companion_input.type = "name";
  companion_input.placeholder = "Nombre del acompañante";
  companion_input.id = "avf_companion_" + comp;
  companion_input.name = "avf_companion[]";
  companion_input.classList.add("form-control", "basicAutoComplete");
  companion_input.setAttribute("autocomplete", "off");
  //companion_input.setAttribute('oninput', "autocomplete_clients(this)");

  var companion_input_hidden = document.createElement("input");
  companion_input_hidden.type = "hidden";
  companion_input_hidden.name = "avf_companion_id[]";
  companion_input_hidden.id = companion_input.id + "_id";

  var companion_autocomplete = document.createElement("div");
  companion_autocomplete.id = companion_input.id + "_autocomplete";

  const input_group = document.createElement("div");
  input_group.classList.add("input-group");
  input_group.id = companion_input.id + "_group";

  const input_group_text = document.createElement("span");
  input_group_text.classList.add("input-group-text");
  input_group_text.id = companion_input.id + "_button";

  const icon = document.createElement("i");
  icon.classList.add("fa", "fa-edit");

  input_group_text.append(icon);
  input_group.append(input_group_text);
  input_group.append(companion_input);
  input_group.append(companion_input_hidden);

  const companion = document.createElement("div");
  companion.id = "companion_div_element_" + comp;
  companion.classList.add("w-100", "d-flex", "mt-2");
  companion.prepend(input_group);
  //companion.innerHTML = "<input type=\"name\" id=\"avf_companion[]\" placeholder=\"Nombre\" name=\"avf_companion[]\" class=\"form-control\"/>";

  companion_div.append(companion);
  companion_div.append(companion_autocomplete);
  comp++;

  reload_autocomplete();
});

// On submit the ADD Voucher Form
$("#add_voucher_form").submit(function (e) {
  e.preventDefault(); // Avoid form to execute

  // Getting the form and the validator data
  var form = $(this);

  // Execute only of validator is passed
  var form_data = new FormData(form[0]);

  // Execute the Database Query
  $.ajax({
    url: "./api/?vouchers&add",
    type: "POST",
    data: form_data,
    contentType: false,
    processData: false,
    success: function (msg) {
      add_voucher_modal.hide();
      show_alert("success", "Se ha agregado correctamente la reserva");

      $("#add_voucher_form")[0].reset();
      // Reload the main table data
      $.get("./api/?vouchers&list", function (data) {
        populate_data(JSON.parse(data), 1, voucher_main_table, voucher_default_row, "voucher");
      });
    },
  });
});

// Setting Cookie for table size
$("#small_table_value").click(function () {
  if (this.checked == true) {
    $("#main-table").addClass("table-sm");
    setCookie("sccs_visual_size", "small", 7);
  } else {
    $("#main-table").removeClass("table-sm");
    setCookie("sccs_visual_size", "normal", 7);
  }
  console.dir("Cambiando la vista de la tabla principal");

  // show_alert('success', "Agregar reserva a usuario: " + this.dataset.userId, 5);
  //  console.log(document.cookie);
});

// ACTIONS //
$(document).ready(function () {
  reload_autocomplete();
  alert("check");

  // Reading sccs_visual_size Cookie and executing code for table size formatting
  if (getCookie("sccs_visual_size") == "small") {
    $("#small_table_value").prop("checked", true);
    $("#main-table").addClass("table-sm");
  }

  /*
  https: $.ajax({
    url: "https://api.github.com/repos/striderskynet/Sistema-integral-de-Clientes/branches/master",
    //headers: {"Authorization": "token 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"}
  }).done(function (data) {
    //console.log(data);
    var com = [];
    com["message"] = data.commit.commit.message;
    com["user"] = data.commit.commit.committer.name;
    com["date"] = data.commit.commit.committer.date;

    var date_commit = new Date(com["date"]);
    var date_last = new Date(last_commit);

    if (date_commit > date_last)
      alert(
        "exclamation",
        "danger",
        `Existe una nueva actualizacion del sistema con fecha de <strong>${date_commit.toLocaleDateString("en-US")}</strong> por "<strong>${com["user"]}</strong>" con el mensaje "${com["message"]}"`,
        date_commit.toLocaleDateString("en-US")
      );
  });*/

  https: $.ajax({
    url: github_address,
    //headers: {"Authorization": "token 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"}
  }).done(function (data) {
    Object.keys(data).forEach((key) => {
      if (data[key].tag_name >= github_version) {
        $("#update_button").toggle();
        $("#update_button")
          .find(".text")
          .html("Actualizar: v" + data[key].name);
      }

      $("#update_button").click(function () {
        if (confirm("Estas seguro de querer actualizar?")) {
          $.ajax({
            url: "./core/verify_update.php",
            type: "POST",
            data: { data: data[key] },
            success: function (msg) {
              console.log(msg);
            },
          });
        }
      });
    });
  });

  $("input[type=password]").each(function (e) {
    if ($(this).hasClass("no-eye") === false) {
      var eye = document.createElement("i");
      eye.classList.add("fa", "fa-eye", "password-eye");
      eye.id = this.name + "-eye";
      this.after(eye);

      $("#" + eye.id).on("mousedown mouseup", function () {
        password = $("input[name='" + this.id.replace("-eye", "").replace("#", "") + "']");

        const type = password.attr("type") === "password" ? "text" : "password";
        this.classList.toggle("fa-eye-slash");
        this.classList.toggle("text-danger");

        password.attr("type", type);
      });
    }
  });

  $(".priceAutoComplete").autoComplete({
    resolver: "custom",
    minLength: 1,
    events: {
      search: function (qry, callback) {
        $.ajax("./api/?prices&list_min", { data: { q: qry } }).done(function (res) {
          callback(JSON.parse(res));
        });
      },
    },
  });

  $(".priceAutoComplete").on("autocomplete.select", function (evt, item) {
    const element = this;
    const pr_text = $("#avf_data");
    const in_date = $("#avf_inDate");
    const out_date = $("#avf_outDate");
    const service_partner = $("#avf_servicePartner");

    if (item.text.includes("<main_type>")) {
      $.get(`./api/?prices&list&wh=WHERE+id+=+${item.value}`, function (data) {
        data = JSON.parse(data);
        element.value = data[0].code;

        pr_text.text(`${data[0].name} (${data[0].place})\n\t${data[0].type}`);
        in_date.prop("min", data[0].from_date);
        in_date.prop("max", data[0].to_date);
        out_date.prop("min", data[0].from_date);
        out_date.prop("max", data[0].to_date);
      });
    } else if (item.text.includes("<transport_type>")) {
      $.get(`./api/?prices&list&table=price_transport&wh=WHERE+id+=+${item.value}`, function (data) {
        data = JSON.parse(data);
        element.value = data[0].code;
        service_partner[0].value = data[0].agency;

        in_date.prop("min", null);
        in_date.prop("max", null);
        out_date.prop("min", null);
        out_date.prop("max", null);

        pr_text.text(` Desde: ${data[0].from_place}\n Hacia: ${data[0].to_place}\n Vehiculo: ${data[0].vehicle_type}\n Max / personas: ${data[0].vehicle_max_passenger}`);
      });
    }
  });
});
