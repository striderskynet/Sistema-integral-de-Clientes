const prices_table_row = $("#data-default");
var prices_table = $("#main-table-body");

const transport_table_row = $("#data-transport-default");
var transport_table = $("#main-transport-table");

var selected_items = [];
var dTable = "-default";

const main_table_row = $("#data-default");
var main_table = $("#main-table-body");

pagination = 25;

$.get("./api/?prices&list&table=price_agency", function (data) {
  populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "agency");
  editable_table_reload("price_agency");
});

// Execute every time there is a search in the search bar
$("#main_search,#main_search_button").prop("disabled", true);
$("#main_search,#main_search_button").prop("title", "Deshabilitada la busqueda hasta nueva version");

$(".card-header").click(function () {
  $(".card-body").slideToggle();
  $("#apf_code").focus();
  //console.dir(this.parentElement.children[1]);
});

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

      $("#add_agency_form")[0].reset();

      // Reload the main table data
      $.get("./api/?prices&list&table=price_agency", function (data) {
        populate_data(JSON.parse(data), offset, prices_table, prices_table_row, "agency");
        editable_table_reload("price_agency");
      });
    },
  });
});

let lastSelectedItem = [];
function select_tr(element, no, event, suffix = "data_p") {
  //delete selected_items[selected_items.indexOf(element.id)];

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
  $("#delete_agency_button span strong").html(len);
  $("#duplicate_agency_button span strong").html(len);
  $("#copy_agency_button span strong").html(len);

  if (len > 0) {
    $("#delete_agency_button").fadeIn();
    $("#duplicate_agency_button").fadeIn();
    $("#copy_agency_button").fadeIn();
  } else {
    $("#delete_agency_button").fadeOut();
    $("#duplicate_agency_button").fadeOut();
    $("#copy_agency_button").fadeOut();
  }
}
function select_item(id, no) {
  //console.log(id);
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
}

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

function clear_selected() {
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
}

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

$(".data-check-all").click(function (event) {
  console.log($(this));
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
