$(document).ready(function (e) {
  /////////////////////////////// Set canvases hights ///////////////////////////////
  $("#myChart").height(380);
  $("#myChart2").height(380);
  $("#myChart3").height(220);

  /////////////////////////////////////////////// 1st Chart - Blood donations History Bar chart ///////////////////////////////////////////////
  var myChart = document.getElementById("myChart").getContext("2d");

  /////////////////////////// Define empty arrays for store donation history data /////////////////////////////
  var TotalBlood_data2022 = [];
  var AcceptBlood_data2022 = [];
  var RejectBlood_data2022 = [];

  /////////////////////////// Get donation history data by ajax /////////////////////////////
  function Chart1_ajax() {
    $.ajax({
      url: "../controller/Data_controller.php?status=get_RawBloodData",
      dataType: "JSON",
      type: "GET",
      success: function (res) {
        if (res[0][0] != "Error") {
          for (i = 1; i <= 12; i++) {
            TotalBlood_data2022.push(res[0][5][i]); //////////// [0] => Donated bloods , [5] => Current year, [i] => Month ///////////
          }
        }

        if (res[1][0] != "Error") {
          for (i = 1; i <= 12; i++) {
            AcceptBlood_data2022.push(res[1][5][i]); //////////// [1] => Accepted bloods , [5] => Current year, [i] => Month ///////////
          }
        }

        if (res[2][0] != "Error") {
          for (i = 1; i <= 12; i++) {
            RejectBlood_data2022.push(res[2][5][i]); //////////// [0] => Rejected bloods, [5] => Current year, [i] => Month ///////////
          }
        }
      },
    });
  }

  /////////////////////////// Enable chart and insert data /////////////////////////////
  function Chart_1() {
    var donation = new Chart(myChart, {
      type: "bar", // pie, line, horizontalBar, daoughnut, radar, polarArea
      data: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        datasets: [
          {
            label: "Total Donations",
            data: TotalBlood_data2022, //Samples - [56,162,167,97,203,136,128,104,100,135,116,99]
            backgroundColor: "#00F",
            borderWidth: 1,
            borderColor: "rgb(0, 0, 255)",
            hoverBorderColor: "rgb(0 , 0, 255)",
          },
          {
            label: "Accepted Blood Count",
            data: AcceptBlood_data2022, //Samples - [102,148,95,125,87,166,68,72,141,170,130,108,120]
            backgroundColor: "#0C0",
            borderWidth: 1,
            borderColor: "#0C0",
            hoverBorderColor: "#0C0",
          },
          {
            label: "Rejected Blood Count",
            data: RejectBlood_data2022, //Samples - [108,118,95,135,100,106,112,119,105,105,97,120,118],
            backgroundColor: "#F00",
            borderWidth: 1,
            borderColor: "#F00",
            hoverBorderColor: "#F00",
          },
        ],
      },
      options: {
        title: {
          display: true,
          text: "Donations",
          fontSize: 20,
        },
        scales: {
          xAxes: [
            {
              display: true,
              scaleLabel: {
                display: true,
                labelString: "Month",
              },
            },
          ],
          yAxes: [
            {
              display: true,
              scaleLabel: {
                display: true,
                labelString: "Bags Count",
              },
            },
          ],
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  }
  ///////////////////////////////////////////////////// 1st Chart End ////////////////////////////////////////////////////////

  /////////////////////////////////////////////// 2nd Chart - Inventory History Bar chart ///////////////////////////////////////////////
  var myChart2 = document.getElementById("myChart2").getContext("2d");

  /////////////////////////// Define empty arrays for store inventory data /////////////////////////////
  var AddedBlood_data2022 = [];
  var IssuedBlood_data2022 = [];
  var ExpiredBlood_data2022 = [];

  /////////////////////////// Get inventory history data by ajax /////////////////////////////
  function Chart2_ajax() {
    $.ajax({
      url: "../controller/Data_controller.php?status=get_InventoryData",
      dataType: "JSON",
      type: "GET",
      success: function (res) {
        if (res[0][0] != "Error") {
          for (i = 1; i <= 12; i++) {
            AddedBlood_data2022.push(res[0][5][i]); //////////// [0] => Added bags , [5] => Current year, [i] => Month ///////////
          }
        }

        if (res[1][0] != "Error") {
          for (i = 1; i <= 12; i++) {
            IssuedBlood_data2022.push(res[1][5][i]); //////////// [0] => Issued bags , [5] => Current year, [i] => Month ///////////
          }
        }

        if (res[2][0] != "Error") {
          for (i = 1; i <= 12; i++) {
            ExpiredBlood_data2022.push(res[2][5][i]); //////////// [0] => Expired bags , [5] => Current year, [i] => Month ///////////
          }
        }
      },
    });
  }

  /////////////////////////// Enable chart and insert data /////////////////////////////
  function Chart_2() {
    var inventory = new Chart(myChart2, {
      type: "line",
      data: {
        labels: [
          "January",
          "February",
          "March",
          "April",
          "May",
          "June",
          "July",
          "August",
          "September",
          "October",
          "November",
          "December",
        ],
        datasets: [
          {
            label: "Added Blood",
            fill: false,
            data: AddedBlood_data2022,
            backgroundColor: "rgba(167, 45, 38)",
            borderWidth: 1,
            borderColor: "rgba(167, 45, 38)",
            hoverBorderColor: "",
          },
          {
            label: "Issued Blood",
            fill: false,
            data: IssuedBlood_data2022,
            backgroundColor: "rgba(194, 85, 238)",
            borderWidth: 1,
            borderColor: "rgba(43, 255, 56)",
            hoverBorderColor: "",
          },
          {
            label: "Expired Blood",
            fill: false,
            data: ExpiredBlood_data2022,
            backgroundColor: "rgba(105, 105, 68)",
            borderWidth: 1,
            borderColor: "rgba(105, 105, 68)",
            hoverBorderColor: "",
          },
        ],
      },
      options: {
        title: {
          display: true,
          text: "Inventory",
          fontSize: 20,
        },
        scales: {
          xAxes: [
            {
              display: true,
              scaleLabel: {
                display: true,
                labelString: "Month",
              },
            },
          ],
          yAxes: [
            {
              display: true,
              scaleLabel: {
                display: true,
                labelString: "Bags Count",
              },
            },
          ],
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  }
  //////////////////////////////////////////////////////// 2nd Chart End ///////////////////////////////////////////////////////////

  /////////////////////////////////////////////// 3rd Chart - Current Raw bloods Pie chart ///////////////////////////////////////////////
  var myChart3 = document.getElementById("myChart3").getContext("2d");

  /////////////////////////// Define empty arrays for store current raw blood data /////////////////////////////
  var labels_array = [];
  var data_array = [];

  /////////////////////////// Get current raw blood data by ajax /////////////////////////////
  function Chart3_ajax() {
    $.ajax({
      url: "../controller/Data_controller.php?status=get_CurrentRawBloodData",
      type: "GET",
      dataType: "JSON",
      success: function (res) {
        for (i = 0; i < res.length; i++) {
          labels_array.push(res[i][0]); //////////// [0] => Blood group name ///////////
          data_array.push(res[i][1]); //////////// [1] => Corresponding Raw blood count ///////////
        }
      },
    });
  }

  /////////////////////////// Enable chart and insert data /////////////////////////////
  function Chart_3() {
    var other = new Chart(myChart3, {
      type: "pie",
      data: {
        labels: labels_array,
        datasets: [
          {
            label: "Added Blood",
            data: data_array,
            backgroundColor: [
              "#930",
              "#FC0",
              "#FF0",
              "#06F",
              "#4C0",
              "#33C",
              "#966",
              "#666",
            ],
            borderWidth: 1,
            borderColor: "",
            hoverBorderColor: "",
          },
        ],
      },
      options: {
        title: {
          display: true,
          text: "Current Raw Blood Stock",
          fontSize: 17,
        },
        responsive: true,
        maintainAspectRatio: false,
      },
    });
  }
  ////////////////////////////////////////////////////// 3rd Chart End /////////////////////////////////////////////////////////////

  /////////////////////////////////////////////// Execute Functions ///////////////////////////////////////////////
  Chart1_ajax();
  Chart2_ajax();
  Chart3_ajax();

  setTimeout(function () {
    Chart_1();
    Chart_2();
    Chart_3();
  }, 1000);

  ////////////////////////// If need real time updates ///////////////////////////
  // setInterval(function () {
  //   Chart1_ajax();
  //   Chart2_ajax();
  //   Chart3_ajax();
  // }, 59 * 1000);

  // setInterval(function () {
  //   Chart_1();
  //   Chart_2();
  //   Chart_3();
  // }, 60 * 1000);
});
