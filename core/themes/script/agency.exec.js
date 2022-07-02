// Constant declaration
const prices_table_row = $("#data-default");
var prices_table = $("#main-table-body");

const transport_table_row = $("#data-transport-default");
var transport_table = $("#main-transport-table");

var selected_items = []; // Selected items
let lastSelectedItem = [];
var dTable = "-default"; // Actual Table Used

const main_table_row = $("#data-default");
var main_table = $("#main-table-body");

pagination = 25; // Local Pagination

$.get("./api/?prices&list&table=price_agency", function (data) {
  populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "agency");
  editable_table_reload("price_agency");
});

// Execute every time there is a search in the search bar
$("#main_search,#main_search_button").prop("disabled", true);
$("#main_search,#main_search_button").prop("title", "Deshabilitada la busqueda hasta nueva version");

// Toggle the Card Reader Animation
$(".card-header").click(function () {
  $(".card-body").slideToggle();
  $("#apf_code").focus();
});

// Default Agency Form Action
$("#add_agency_form").submit(function (e) {
  e.preventDefault(); // Avoid form to execute
  var form = $(this);

  // Execute only of validator is passed
  var form_data = new FormData(form[0]);

  $.ajax({
    url: "./api/?prices&add&type=agency",
    type: "POST",
    data: form_data,
    contentType: false,
    processData: false,
    success: function (msg) {
      console.log(msg);
      show_alert("success", "Se ha agregado correctamente el listado");

      $("#add_agency_form")[0].reset(); // Reset the Form

      // Reload the main table data
      $.get("./api/?prices&list&table=price_agency", function (data) {
        populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "agency");
        editable_table_reload("price_agency");
      });
    },
  });
});

$("#delete_agency_button").click(function (e) {
  e.preventDefault();

  if (selected_items.length > 0) {
    //console.log(selected_items);
    console.log("Deleting some items");

    $.ajax({
      url: "./api/?prices&delete&table=agency",
      type: "POST",
      data: { info: selected_items },
      success: function (msg) {
        $.get("./api/?prices&list&table=price_agency", function (data) {
          populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "agency");
          editable_table_reload("price_agency");
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

    $.ajax({
      url: "./api/?prices&duplicate",
      type: "POST",
      data: { info: selected_items },
      success: function (msg) {
        $.get("./api/?prices&list", function (data) {
          populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "prices");
          editable_table_reload("price_agency");
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
