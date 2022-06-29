//var login_modal = new bootstrap.Modal(document.getElementById('login-modal'));
$("#login-modal").modal({
  backdrop: "static",
});
$("#login-modal").show();

$("#login-form").submit(function (e) {
  e.preventDefault();

  var form_login = $(this);

  var log_user = form_login[0].username_login.value;
  var log_pass = form_login[0].password_login.value;

  console.log(`Login as "${log_user}" Pass: "${log_pass}"`);

  $.ajax({
    method: "POST",
    url: "./api/?users&verify",
    // Passing all the variables
    data: {
      username: log_user,
      password: log_pass,
    },
  }).done(function (msg) {
    if (msg.includes("true")) {
      document.location = "./";
    } else {
      show_alert("danger", "Usuario o Contrasena incorrecto");
    }
  });
});
