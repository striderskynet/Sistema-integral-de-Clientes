const prices_table_row = $("#data-default");
var prices_table = $("#main-table-body");

const transport_table_row = $("#data-transport-default");
var transport_table = $("#main-transport-table");

var selected_items = [];
let lastSelectedItem = [];

const main_table_row = $("#data-default");
var main_table = $("#main-table-body");

pagination = 25;

$.get("./api/?prices&list&orderBy=code", function (data) {
  populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "prices");
  editable_table_reload();
});

$.get("./api/?prices&list&table=price_transport", function (data) {
  populate_table(JSON.parse(data), transport_table, transport_table_row);
  //populate_data(JSON.parse(data), offset, transport_table, transport_table_row, "transport", "transport-table-body");
  //editable_table_reload("price_transport");
});

function populate_table(data, table, table_row) {
  delete data.info;
  let default_row = table_row.html();
  table.find("tbody").html("");

  q = 0;
  Object.keys(data).forEach((key) => {
    let new_row = document.createElement("tr");
    new_row.innerHTML = tokenize(data[key], default_row);

    new_row.id = "data_t" + q;
    new_row.setAttribute("onclick", `select_tr(this, ${data[key].id}, event, "data_t")`);
    new_row.dataset.priceId = data[key].id;

    table.find("tbody").append(new_row);
    q++;
  });
}
// Execute every time there is a search in the search bar
$("#main_search,#main_search_button").prop("disabled", true);
$("#main_search,#main_search_button").prop("title", "Deshabilitada la busqueda hasta nueva version");

$(".card-header").click(function () {
  $(".card-body").slideToggle();
  $("#apf_code").focus();
  //console.dir(this.parentElement.children[1]);
});

$("#add_prices_form").submit(function (e) {
  e.preventDefault(); // Avoid form to execute
  var form = $(this);

  // Execute only of validator is passed
  var form_data = new FormData(form[0]);

  $.ajax({
    url: "./api/?prices&add",
    type: "POST",
    data: form_data,
    contentType: false,
    processData: false,
    success: function (msg) {
      show_alert("success", "Se ha agregado correctamente el listado");

      $("#add_prices_form")[0].reset();

      // Reload the main table data
      $.get("./api/?prices&list", function (data) {
        populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "prices");
        editable_table_reload();
      });
    },
  });
});

$("#add_transport_form").submit(function (e) {
  e.preventDefault(); // Avoid form to execute
  var form = $(this);

  // Execute only of validator is passed
  var form_data = new FormData(form[0]);

  $.ajax({
    url: "./api/?prices&add&type=transport",
    type: "POST",
    data: form_data,
    contentType: false,
    processData: false,
    success: function (msg) {
      show_alert("success", "Se ha agregado correctamente el listado");
      $("#add_transport_form")[0].reset();

      // Reload the main table data
      $.get("./api/?prices&list&table=price_transport", function (data) {
        populate_table(JSON.parse(data), transport_table, transport_table_row);
        //populate_data(JSON.parse(data), offset, transport_table, transport_table_row, "transport", "transport-table-body");
        editable_table_reload("price_transport");
      });
    },
  });
});

$("#delete_price_button").click(function (e) {
  e.preventDefault();

  if (selected_items.length > 0) {
    //console.log(selected_items);
    console.log("Deleting some items");

    var table = "list";
    switch (dTable) {
      default:
        table = "list";
        break;
      case "-transport":
        table = "transport";
        break;
    }

    $.ajax({
      url: "./api/?prices&delete&table=" + table,
      type: "POST",
      data: { info: selected_items },
      success: function (msg) {
        $.get("./api/?prices&list", function (data) {
          populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "prices");
          editable_table_reload();
          clear_selected();
        });

        $.get("./api/?prices&list&table=price_transport", function (data) {
          populate_table(JSON.parse(data), transport_table, transport_table_row);
          //populate_data(JSON.parse(data), offset, transport_table, transport_table_row, "transport", "transport-table-body");
          editable_table_reload("price_transport");
        });
      },
    });
  }

  clear_selected();
});

$("#duplicate_price_button").click(function (e) {
  e.preventDefault();

  if (selected_items.length > 0) {
    console.log("Duplicating items");

    var table = "list";
    switch (dTable) {
      default:
        table = "list";
        break;
      case "-transport":
        table = "transport";
        break;
    }

    $.ajax({
      url: "./api/?prices&duplicate&table=" + table,
      type: "POST",
      data: { info: selected_items },
      success: function (msg) {
        $.get("./api/?prices&list", function (data) {
          populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "prices");
          editable_table_reload();
          clear_selected();
        });
        $.get("./api/?prices&list&table=price_transport", function (data) {
          populate_table(JSON.parse(data), transport_table, transport_table_row);
          editable_table_reload("price_transport");
          clear_selected();
        });
      },
    });
  }
});

$("#apf_clear_button").click(function () {
  $("#add_prices_form")[0].reset();
  $("#apf_code").focus();
});

$("#offert_div").toggle();

$("#apf_offert_check").click(function () {
  $("#offert_div").toggle();

  $("#apf_offert_from").prop("min", $("#apf_from_date").val());
  $("#apf_offert_to").prop("min", $("#apf_from_date").val());
  $("#apf_offert_from").prop("max", $("#apf_to_date").val());
  $("#apf_offert_to").prop("max", $("#apf_to_date").val());
});

$("#apf_from_date").on("change", function () {});

var dTable = "-default";
$('a[data-toggle="tab"]').on("shown.bs.tab", function (event) {
  switch (event.target.id) {
    default:
      dTable = "-default";
      editable_table_reload();
      break;
    case "ex-with-icons-tab-2":
      dTable = "-transport";
      editable_table_reload("price_transport");
      break;
  }
  clear_selected();
});
