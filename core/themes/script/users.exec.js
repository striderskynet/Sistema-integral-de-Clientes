$(".card-header").click(function () {
  $(".card-body").slideToggle();
  $("#apf_code").focus();
  //console.dir(this.parentElement.children[1]);
});

main_users = $(".main-users");
main_users_html = main_users.html();

function users_table_reload() {
  $.get("./api/?users&list", function (data) {
    data = JSON.parse(data);

    main_users.html("");

    Object.keys(data).forEach((key) => {
      var html = tokenize(data[key], main_users_html);
      main_users.html(main_users.html() + html);
    });
  });
}

$("#apf_clear_button").click(function () {
  $("#add_user_form")[0].reset();
  $("#apf_user").focus();
});

users_table_reload();

$("#add_user_form").submit(function (e) {
  e.preventDefault(); // Avoid form to execute
  var form = $(this);

  // Execute only of validator is passed
  var form_data = new FormData(form[0]);

  $.ajax({
    url: "./api/?users&add",
    type: "POST",
    data: form_data,
    contentType: false,
    processData: false,
    success: function (msg) {
      show_alert("success", "Se ha agregado correctamente el usuario");

      $("#add_user_form")[0].reset();
      $(".card-body").slideToggle();

      console.log(msg);
      // Reload the main table data
      users_table_reload();
    },
  });
});

function populateAvatars(gender = "male") {
  var elem = $(".add_user_picture");
  elem.html("");

  for (q = 1; q <= 5; q++) {
    var input = $("<input>");
    var label = document.createElement("label");
    var image = document.createElement("img");
    var tooltip = document.createElement("span");

    //<span data-tooltip="Modificar los datos del cliente">

    input.attr("type", "radio");
    input.attr("name", "avatar-radio");
    input.attr("id", "avatar-" + q);
    input.attr("value", `avatar-${gender}-${q}`);
    input.addClass("avatar-check");

    if (q === 1) input.attr("checked", "true");

    label.setAttribute("for", input.attr("id"));
    tooltip.setAttribute("data-tooltip", `Avatar ${gender} #${q}`);

    image.src = `./assets/images/avatar-${gender}-${q}.png`;

    image.width = 50;

    elem.append(input);
    input.after(label);
    label.appendChild(tooltip);
    tooltip.appendChild(image);
  }

  $('form[name="user-password"]').submit(function (e) {
    e.preventDefault();

    let userID = this.id;
    console.log(userID);
  });
  //<input class='avatar-check' type="checkbox" name="avatar" id="avatar" /><label for="avatar"><img src='./assets/images/default-avatar.png' width="50"></label>
}

$("select[name='apf_gender'").change(function () {
  if (this.value != "other") populateAvatars(this.value);
});

function showUserButtons(userID) {
  $(".main-users-buttons-" + userID).slideToggle();
}

function user_delete(userID) {
  console.log("Deleting user: " + userID);

  if (confirm("Realmente desea borrar el usuario: " + userID)) {
    $.get("./api/?users&delete&id=" + userID, function (data) {
      users_table_reload();
    });

    show_alert("danger", `Borrando usuario con ID: ${userID}`);
  }
}

function user_password(userID) {
  var passInput = $(`#user_pass_change-${userID}`);
  var button = $("#user-button-mod-" + userID);

  passInput.fadeToggle();
  button.toggleClass("no-left-round");

  if (passInput.val().length >= 3) {
    console.log("Changing password to: " + passInput.val());

    if (
      confirm("Realmente desea cambiar la contraseña al usuario: " + userID)
    ) {
      $.get(
        "./api/?users&password&id=" + userID + "&pass=" + passInput.val(),
        function (data) {}
      );

      show_alert("info", `Contraseña cambiada al usuario: ${userID}`);

      if (session_id === userID) {
        $("#logout_button").click();
      }
    } else {
    }
  }
}

$(function () {
  populateAvatars("male");
  $('[data-toggle="popover"]').popover();
});
