function load_logs(log_file) {
  $.ajax({
    url: "core/themes/logs.php?log=" + log_file,
    cache: false,
  }).done(function (result) {
    $("#log_wrapper").html(result);
    $("#log_file_name").html(log_file);

    /* $(".log_data").click(function () {
      let next = $(this).parent().parent().next();
      if (next.hasClass("hide")) next.toggle(300);
    });*/
  });

  console.log("Loading logs file: " + log_file);
}

load_logs(document.getElementById("logs_select").value);
