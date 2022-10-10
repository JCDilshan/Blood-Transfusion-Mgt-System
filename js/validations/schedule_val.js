$(document).ready(function (e) {
  //////////////////////// Remove confirmation /////////////////////
  $("#mng_scheduleTB").on("click", ".RemoveConf", function () {
    var loc = $(this).attr("href");

    swal(
      {
        title: "Are you sure You Want Remove This Schedule ?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Remove",
        cancelButtonClass: "btn-info",
        closeOnConfirm: true,
      },
      function (isConfirm) {
        if (isConfirm === true) {
          window.location.href = loc;
        }
      }
    );

    return false;
  });

  //////////////////////// Enable datatable plugin /////////////////////
  $("#mng_scheduleTB").DataTable();

  //////////////////////// Show/hide form /////////////////////
  $("#fRow").hide();
  $("#add_schedBtn").click(function () {
    $("#fRow").toggle("slow", function () {
      $("#venue").focus();
    });
  });

  //////////////////////// Date range selection hide as default /////////////////////
  $("#dt_seriesRow").hide();

  //////////////////////// Date range selection show when click set range /////////////////////
  $("#set_series").click(function () {
    $("#date").val("");
    $("#dt_seriesRow").toggle("fast");
    $("#single_date").toggle("fast");
  });

  //////////////////////// Check whether valid date or not ///////////////////////
  $("#date").on("change", function () {
    // $("#set_series").hide("fast");
    $("#from_date").val("");
    $("#to_date").val("");

    var curr_date = new Date();
    var y = curr_date.getFullYear();
    var m = curr_date.getMonth() + 1;
    var d = curr_date.getDate();
    curr_date = new Date("" + y + "-" + m + "-" + d + "").getTime();
    curr_date = curr_date + 19800 * 1000;

    var date = $(this).val();
    date = new Date("" + date + "");
    date = date.getTime();

    if (date < curr_date) {
      swal("Invalid Date", "", "error");
      $(this).val("");
    }
  });

  //////////////////////// Check whether valid date or not ///////////////////////
  $("#from_date").on("change", function () {
    var curr_date = new Date();
    var y = curr_date.getFullYear();
    var m = curr_date.getMonth() + 1;
    var d = curr_date.getDate();
    curr_date = new Date("" + y + "-" + m + "-" + d + "").getTime();
    curr_date = curr_date + 19800 * 1000;

    var date = $(this).val();
    date = new Date("" + date + "");
    date = date.getTime();

    if (date < curr_date) {
      swal("Invalid Date", "", "error");
      $(this).val("");
    } else {
      $("#to_date").prop("readonly", false);
    }
  });

  //////////////////////// Check whether valid date or not ///////////////////////
  $("#to_date").on("change", function () {
    var from_date = $("#from_date").val();
    var to_date = $(this).val();

    var set_Fromdate = new Date("" + from_date + "");
    var from_date = set_Fromdate.getTime();

    var set_Todate = new Date("" + to_date + "");
    var to_date = set_Todate.getTime();

    if (to_date <= from_date) {
      swal("Invalid Date Range", "", "error");
      $(this).val("");
    }
  });

  //////////////////////// Allow to select ending time when set start time ///////////////////////
  $("#str_time").on("change", function () {
    $("#end_time").prop("readonly", false);
  });

  //////////////////////// Check whether valid ending time or not ///////////////////////
  $("#end_time").on("change", function () {
    var str_time = $("#str_time").val();
    var end_time = $(this).val();

    str_time = Date.parse("01/01/2011 " + str_time + "");
    end_time = Date.parse("01/01/2011 " + end_time + "");

    if (end_time <= str_time) {
      swal("Invalid Time Range", "", "error");
      $(this).val("");
    }
  });

  //////////////////////// Slots input field auto correct with regex ///////////////////////
  $("#slots").keyup(function () {
    var slots = $(this).val();
    corr_slots = slots.replace(/[e]/gi, "");
    $(this).val(corr_slots);
  });

  //////////////////////// Schedule existance check by ajax ///////////////////////
  $("#add_schedule").on("change", function () {
    var venue = $("#venue").val();
    var date = $("#date").val();
    var time = $("#str_time").val();

    $.ajax({
      url: "../controller/schedule_controller.php?status=schedule_exist",
      type: "POST",
      data: { venue: venue, date: date, time: time },
      success: function (data) {
        if (data == "fail") {
          $("#schedule_res").html("Schedule Already Set*");
          $("#submit").prop("disabled", true);
        } else {
          $("#schedule_res").html("");
          $("#submit").prop("disabled", false);
        }
      },
    });
  });

  //////////////////////// Form submittion validations ///////////////////////
  $("#add_schedule").on("submit", function () {
    var stt_date = $("#date").val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();

    if (stt_date == "" && from_date == "" && to_date == "") {
      swal("Date Field Empty !", "", "warning");
      return false;
    } else if (
      (from_date == "" && to_date != "") ||
      (from_date != "" && to_date == "")
    ) {
      swal("Should Be Fill Both Date Fields !", "", "warning");
      return false;
    }
  });
});
