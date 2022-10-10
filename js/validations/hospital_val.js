$(document).ready(function (e) {
  //////////////////////// Remove confirmation /////////////////////
  $("#mng_hospTB").on("click", ".RemoveConf", function () {
    var loc = $(this).attr("href");

    swal(
      {
        title: "Are you sure You Want Remove This Hospital ?",
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
  $("#mng_hospTB").DataTable();

  //////////////////////// Show/hide form /////////////////////
  $("#fRow").hide();
  $("#add_hospBtn").click(function () {
    $("#fRow").toggle("slow", function () {
      $("#hosp_name").focus();
    });
  });

  //////////////////////// Hospital existance check by ajax ///////////////////////
  $("#location,#hosp_name").keyup(function () {
    var hosp_name = $("#hosp_name").val();
    var location = $("#location").val();

    if (hosp_name != "" && location != "") {
      $.ajax({
        url: "../controller/schedule_controller.php?status=hosp_exist",
        type: "POST",
        data: { name: hosp_name, loc: location },
        success: function (data) {
          if (data == "fail") {
            $("#hosp_res").html("Hospital Already Added*");
            $("#hosp_name").css("border-color", "#F00");
            $("#submit").prop("disabled", true);
          } else {
            $("#hosp_res").html("");
            $("#hosp_name").css("border-color", "");
            $("#submit").prop("disabled", false);
          }
        },
      });
    }
  });

  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#con_no").keyup(function () {
    var con_no = $(this).val();
    corr_con_no = con_no.replace(/[e]/gi, "");
    $(this).val(corr_con_no);
  });

  $("#up_conno").keyup(function () {
    var con_no = $(this).val();
    corr_con_no = con_no.replace(/[e]/gi, "");
    $(this).val(corr_con_no);
  });

  $("#addHosp_form").on("submit", function () {
    var con_no = $("#con_no").val();

    var patt_conno = /^\d{9}$/;

    if (!con_no.match(patt_conno)) {
      swal("Invalid Contact Number !", "", "warning");
      $("#con_no").focus();
      return false;
    }
  });

  //////////////////////// Hospital existance check when edit details by ajax ///////////////////////
  $(".edit").click(function () {
    var hosp_id = $(this).val();

    $.ajax({
      url: "../controller/schedule_controller.php?status=search_hosp",
      type: "POST",
      dataType: "JSON",
      data: { hosp_id: hosp_id },
      success: function (res) {
        if (res != "NoData") {
          $("#update_hosp").modal("show");
          $("#hosp_id").val(hosp_id);
          $("#up_conno").val(res[0]);
          $("#up_email").val(res[1]);
        }
      },
    });
  });

  //////////////////////// Edit hospital form submittion validations ///////////////////////
  $("#updateHosp_form").on("submit", function () {
    var up_conno = $("#up_conno").val();

    var patt_conno = /^\d{9}$/;

    if (!up_conno.match(patt_conno)) {
      swal("Invalid Contact Number !", "", "warning");
      $("#up_conno").focus();
      return false;
    }
  });
});
