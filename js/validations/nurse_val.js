$(document).ready(function (e) {
  //////////////////////// Blocking confirmation /////////////////////
  $("#mng_nurseTB").on("click", ".BlockConf", function () {
    var loc = $(this).attr("href");

    swal(
      {
        title: "Are you sure You Want Block This Nurse ?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Block!",
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

  //////////////////////// Remove confirmation /////////////////////
  $("#mng_nurseTB").on("click", ".RemoveConf", function () {
    var loc = $(this).attr("href");

    swal(
      {
        title: "Are you sure You Want Remove This Nurse ?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes, Remove!",
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
  $("#mng_nurseTB").DataTable();

  //////////////////////// Show/hide form /////////////////////
  $("#fRow").hide();
  $("#add_nusreBtn").click(function () {
    $("#fRow").toggle("slow", function () {
      $("#fname").focus();
    });
  });

  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#fname").keyup(function () {
    var fname = $(this).val();
    corr_fname = fname.replace(/[^a-zA-Z\s\.]/g, "");
    $(this).val(corr_fname);
  });

  $("#lname").keyup(function () {
    var lname = $(this).val();
    corr_lname = lname.replace(/[^a-zA-Z\s\.]/g, "");
    $(this).val(corr_lname);
  });

  $("#con_no").keyup(function () {
    var con_no = $(this).val();
    corr_con_no = con_no.replace(/[e]/gi, "");
    $(this).val(corr_con_no);
  });

  //////////////////////// Nurse existance check by ajax ///////////////////////
  $("#nic").on("keyup", function () {
    var nic = $(this).val();

    if (nic != "" || nic != null) {
      $.ajax({
        url: "../controller/staff_controller.php?status=nurse_search",
        type: "POST",
        data: { nic: nic },
        success: function (data) {
          if (data == "fail") {
            $("#nurse_res").html("Nurse Already Added*");
            $("#nic").css("border-color", "#F00");
            $("#submit").prop("disabled", true);
            //return false;
          } else {
            $("#submit").prop("disabled", false);
            $("#nic").css("border-color", "");
            $("#nurse_res").html("");
          }
        },
      });
    }
  });

  //////////////////////// Form submittion validations ///////////////////////
  $("#addNurse_Form").on("submit", function () {
    var con_no = $("#con_no").val();
    var nic = $("#nic").val();

    patt_conno = /^\d{9}$/;
    nic_patt_1 = /^\d{9}[vV]$/;
    nic_patt_2 = /^\d{12}$/;
    name_patt = /^[a-zA-Z]+[.]*$/;

    if (!nic.match(nic_patt_1) && !nic.match(nic_patt_2)) {
      swal("Invalid N.I.C. Number !", "", "warning");
      $("#nic").focus();
      return false;
    } else if (!con_no.match(patt_conno)) {
      swal("Invalid Contact Number !", "", "warning");
      $("#con_no").focus();
      return false;
    } else if ($('input[name="gender"]:checked').length < 1) {
      swal("Please Select Gender !", "", "warning");
      return false;
    }
  });
});
