$(document).ready(function () {
  var months = ["Jan", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
  var dateNow = new Date();
  var mCurrent = dateNow.getUTCMonth();
  var mCount = 6;

  var pMonths = [];
  var pValues = [];
  for (i = mCurrent - mCount; i <= mCurrent; i++) {
    i < 0 ? (no = i + 12) : (no = i);
    pMonths.push(months[no]);
  }

  for (q = mCount; q >= 1; q--) {
    let minMonth = q;
    let maxMonth = minMonth - 1;
    let query = `SELECT count(*) as \`amount\` FROM \`main_clients\` WHERE \`date_added\` BETWEEN DATE_sub(now(),INTERVAL ${minMonth} MONTH) AND DATE_sub(now(),INTERVAL ${maxMonth} MONTH)`;

    $.get(
      {
        url: "./api/?query&query=" + query,
        async: false,
      },
      function (data) {
        data = JSON.parse(data);
        pValues.push(data.amount);
      }
    );
  }

  var clients_chart_options = {
    chart: {
      type: "area",
    },
    stroke: {
      curve: "smooth",
    },
    dataLabels: {
      enabled: false,
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 0.9,
        stops: [0, 90, 100],
      },
    },
    series: [
      {
        name: "Clientes",
        data: pValues,
      },
      {
        name: "Reservas",
        data: [16, 12, 5, 9, 20, 13],
      },
    ],
    colors: ["#2E93fA", "#FF9800"],
    dropShadow: {
      enabled: true,
      top: 0,
      left: 0,
      blur: 3,
      opacity: 0.5,
    },
    toolbar: {
      show: false,
    },
    xaxis: {
      categories: pMonths,
    },
  };

  var clients_chart = new ApexCharts(document.querySelector("#clients-chart"), clients_chart_options);
  clients_chart.render();

  var voucher_chart_options = {
    series: [14, 23, 21, 17, 15],
    chart: {
      type: "polarArea",
    },
    labels: ["Hoteles", "Eventos", "Transporte", "Excursiones", "Renta de Autos"],
    stroke: {
      colors: ["#fff"],
    },
    fill: {
      opacity: 0.8,
    },
  };

  var voucher_chart = new ApexCharts(document.querySelector("#voucher-chart"), voucher_chart_options);
  voucher_chart.render();
});
