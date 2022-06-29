var clientModalShow = true;
var add_client_modal = new bootstrap.Modal(document.getElementById("add_client_modal"));
var add_voucher_modal = new bootstrap.Modal(document.getElementById("add_voucher_modal"));

var clientModalLabel = new bootstrap.Modal(document.getElementById("clientModal"));
var clientModalBody = document.getElementById("clientModalBody");

const main_table_row = $("#data-default");
var main_table = $("#main-table-body");

// Execute every time there is a search in the search bar
$("#main_search").on("input propertychange", function () {
  // only if value.length is bigger than the min_lenght
  if (this.value.length >= 3) {
    let search_value = this.value;
    console.log(`Searching for '${search_value}'`);

    $data = "";
    // Executing API request for clients and especific search value
    $.get("./api/?clients&list&data=" + search_value, function (data) {
      // IF the API requst is not empty
      if (data.toString() != null) populate_data(JSON.parse(data), 1, main_table, main_table_row);
    });
  } else {
    // if value.lenght is shorter that min_lenght, show all results
    if (this.value == "") {
      $.get("./api/?clients&list", function (data) {
        populate_data(JSON.parse(data), 1, main_table, main_table_row);
      });
    }
  }
});

// Execute the upload form
function upload_client_picture() {
  //var input_dom = document.createElement('input');
  setTimeout(function () {
    $("#upload_client_file").click();
  }, 200);
}

// Reload image frame on upload
$("#upload_client_file").on("change", function () {
  $("#upload_client_icon").hide();
  $("#upload_client_image")[0].src = URL.createObjectURL(this.files[0]);
  $("#upload_client_image").removeClass("hide");
});

// Populate the Add Client Form countries select with C
function populate_countries_select() {
  country_select = $("#acf_contact_country");
  Object.keys(C["countries"]).forEach((key) => {
    country_select.append(
      $("<option>", {
        value: key,
        text: C["countries"][key],
      })
    );
  });
}

// Add new client button
$("#add_client_form").submit(function (e) {
  e.preventDefault();

  // Getting the form and the validator data
  var form = $(this);

  // Execute only of validator is passed
  var form_data = new FormData();
  var files = form[0].acf_client_picture.files[0];
  form_data.append("file", files);
  form_data.append("passport", form[0].acf_contact_passport.value);
  form_data.append("name", form[0].acf_contact_name.value);
  form_data.append("lastname", form[0].acf_contact_lastname.value);

  $.ajax({
    url: "./api/?clients&upload",
    type: "post",
    data: form_data,
    contentType: false,
    processData: false,
    success: function (msg) {
      //console.log(msg);
    },
  });

  // Executing API request to Insert Clients into Database
  $.ajax({
    method: "POST",
    url: "./api/?clients&add",
    // Passing all the variables
    data: {
      name: form[0].acf_contact_name.value,
      lastname: form[0].acf_contact_lastname.value,
      prefix: form[0].acf_contact_prefix.value,
      passport: form[0].acf_contact_passport.value,
      phone: form[0].acf_contact_phone.value,
      email: form[0].acf_contact_email.value,
      address: form[0].acf_contact_address.value,
      country: form[0].acf_contact_country.value,
      company: form[0].acf_contact_company.value,
      status: form[0].acf_contact_status.value,
      observations: form[0].acf_contact_observations.value,
      agency: form[0].acf_contact_agency.value,
      last_touch: "ahora",
    },
  }).done(function (msg) {
    // if result is OK then close de Modal
    add_client_modal.hide();
    show_alert("success", "Se ha agregado correctamente el usuario " + form[0].acf_contact_name.value + " " + form[0].acf_contact_lastname.value);

    $("#add_client_form")[0].reset();
    // Reload the main table data
    $.get("./api/?clients&list", function (data) {
      populate_data(JSON.parse(data), 1, main_table, main_table_row);
    });
  });

  //}
});

function button_user_del(button) {
  //$("#button_user_del").click(function(){
  clientModalShow = false;

  var user_id = button.dataset.userId.replace("u", "");
  let url_del_user = "./api/?clients&delete&id=" + user_id;
  console.log("Llamando a api: " + url_del_user);

  $.get(url_del_user, function (data) {
    show_alert("danger", "Se ha eliminado el usuario con ID: " + user_id, 5);
    //console.log(data);

    $.get("./api/?clients&list", function (data) {
      populate_data(JSON.parse(data), 1, main_table, main_table_row);
    });
  });

  setTimeout(function () {
    clientModalShow = true;
  }, 200);
}

function button_voucher_add(button) {
  clientModalShow = false;

  add_voucher_modal.show();

  let id = button.dataset.userId;
  let name = button.dataset.userName;

  select_name("#mres_client_name", name, id);

  setTimeout(function () {
    clientModalShow = true;
  }, 200);
}

$("#button_client_add").click(function () {
  console.log("Mostrando modal de Cliente Nuevo");
  add_client_modal.show();
  console.log("Populando lista de paises");
  populate_countries_select();

  //show_alert('success', 'Nuevo cliente agregado', 5);
});

$("#button_generate_client").click(function () {
  let name_list,
    lastname_list,
    country_list = "";
  const info = [];
  const prefix = ["Sr.", "Sra.", "Dr.", "Msc"];
  const traveling = ["Traveling", "Arriving", "Arrived", "Overseas", "Unknown"];
  const email = ["gmail.com", "apple.com", "yahoo.com", "outlook.com", "aol.com"];
  const company = ["Google", "Apple", "Facebook", "Yahoo", "Samsung", "Microsoft"];

  jQuery.ajax({
    //url: 'https://raw.githubusercontent.com/dominictarr/random-name/master/first-names.txt',
    url: "./debug/first-names.txt",
    success: function (data) {
      name_list = data.split("\n");
      //console.dir(name_list);
      info["name"] = name_list[Math.floor(Math.random() * name_list.length + 1)];
    },
    async: false,
  });

  jQuery.ajax({
    //url: 'https://gist.githubusercontent.com/craigh411/19a4479b289ae6c3f6edb95152214efc/raw/d25a1afd3de42f10abdea7740ed098d41de3c330/List%2520of%2520the%25201,000%2520Most%2520Common%2520Last%2520Names%2520(USA)',
    url: "./debug/last-names.txt",
    success: function (data) {
      lastname_list = data.split(",");
      info["lastname"] = lastname_list[Math.floor(Math.random() * lastname_list.length + 1)].trim();
    },
    async: false,
  });

  info["passport"] = Math.floor(Math.random() * 100000000000);
  info["phone"] = "+" + (Math.floor(Math.random() * 100) + 1) + " " + (Math.floor(Math.random() * 10) + 1) + " (" + Math.floor(Math.random() * 1000) + ") " + Math.floor(Math.random() * 10000);
  info["country"] = Object.keys(C.countries)[Math.floor(Math.random() * Object.keys(C.countries).length)];
  info["prefix"] = prefix[Math.floor(Math.random() * (prefix.length - 1 + 1))];
  info["traveling"] = traveling[Math.floor(Math.random() * (traveling.length - 1 + 1))];
  info["email"] = info["name"].substr(0, 4).toLowerCase() + "_" + info["lastname"].toLowerCase() + "@" + email[Math.floor(Math.random() * (email.length - 1 + 1))];
  info["company"] = company[Math.floor(Math.random() * (company.length - 1 + 1))];
  info["date_added"] = randomDate(new Date(2021, 12, 1), new Date());
  info["date_added"] = info["date_added"].getFullYear() + "-" + (info["date_added"].getMonth() + 1) + "-" + info["date_added"].getDate();

  $.ajax({
    method: "POST",
    url: "./api/?clients&gen",
    // Passing all the variables
    data: {
      name: info["name"],
      lastname: info["lastname"],
      prefix: info["prefix"],
      passport: info["passport"],
      phone: info["phone"],
      email: info["email"],
      address: "Lorem ipsum dolor sit amet consectetur adipisicing elit.",
      date_added: info["date_added"],
      country: info["country"],
      company: info["company"],
      status: info["traveling"],
      observations: "Lorem ipsum dolor sit amet consectetur adipisicing elit.",
      last_touch: "ahora",
    },
    beforeSend: function () {
      // console.dir(info);
      $(".generate-loader").toggle();
    },
    success: function () {
      $(".generate-loader").toggle();
    },
    error: function (e) {
      // console.log(e);
    },
  });

  show_alert("primary", "Generando cliente nuevo aleatorio", 5);

  $.get("./api/?clients&list", function (data) {
    populate_data(JSON.parse(data), 1, main_table, main_table_row);
  });
});

function randomDate(start, end) {
  return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
}

let oPost = false;

$("#main-table")
  .children("thead")
  .children("tr")
  .children("th")
  .click(function (e) {
    if (e.currentTarget.innerHTML.toLowerCase() === "accion") {
      return false;
    }
    const oElement = e.currentTarget;

    $elements = $("#main-table").children("thead").children("tr").children("th");
    $elements.each(function (el) {
      $elements[el].classList.remove("text-info");
      //$elements[el].classList.del("text-info");
    });

    oPost === "ASC" ? (oPost = "DESC") : (oPost = "ASC");
    oElement.classList.contains("text-info") === false ? oElement.classList.add("text-info") : oElement.classList.remove("text-info");
    oElement.dataset.orderPos = oPost;

    if (typeof oElement.dataset.orderId !== "undefined") {
      $.get(`./api/?clients&list&orderBy=${oElement.dataset.orderId}&dir=${oPost}`, function (data) {
        populate_data(JSON.parse(data), 1, main_table, main_table_row);
      });

      show_alert("info", `Ordenando elementos por '${oElement.dataset.orderId}' '${oPost}'`, 2);
    }
  });

$.get("./api/?clients&list", function (data) {
  populate_data(JSON.parse(data), offset, main_table, main_table_row);
});

var $select_modal = $("#acf_contact_status");

C_status.forEach(function (key) {
  var option = document.createElement("option");
  option.innerHTML = key[1];
  option.value = key[0].toLowerCase();
  option.classList.add(`btn-${key[2]}`, "select-item");
  $select_modal.append(option);
});

$("#add_client_modal").on("shown.bs.modal", function (e) {
  $.get("./api/?prices&list&table=price_agency", function (data) {
    populate_agency("#acf_contact_agency", JSON.parse(data));
    //populate_data(JSON.parse(data), offset, main_table, main_table_row);
  });
});

function populate_agency(elem, data) {
  let list = $(elem);

  delete data.info;

  Object.keys(data).forEach((key) => {
    let new_option = document.createElement("option");
    new_option.value = data[key].id;
    new_option.innerHTML = data[key].type + ": " + data[key].name;

    list.append(new_option);
    console.log(data[key]);
  });
}
