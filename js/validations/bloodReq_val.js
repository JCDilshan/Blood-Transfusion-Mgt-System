$(document).ready(function (e) {
  $(".col-md-12 label").addClass("alert-dark");

  //////////////////////// Make default hidden ///////////////////////
  $("#trans_yes,#any_react").hide();

  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#req_amount").keyup(function () {
    var amount = $(this).val();
    corr_amount = amount.replace(/[e]/gi, "");
    $(this).val(corr_amount);
  });
  $("#age").keyup(function () {
    var age = $(this).val();
    corr_age = age.replace(/[e]/gi, "");
    $(this).val(corr_age);
  });
  $("#weight").keyup(function () {
    var weight = $(this).val();
    corr_weight = weight.replace(/[e]/gi, "");
    $(this).val(corr_weight);
  });
  $("#bht").keyup(function () {
    var bht = $(this).val();
    corr_bht = bht.replace(/[e]/gi, "");
    $(this).val(corr_bht);
  });
  $("#ward").keyup(function () {
    var ward = $(this).val();
    corr_ward = ward.replace(/[\W]/g, "");
    $(this).val(corr_ward);
  });

  //////////////////////// Get patient details by ajax ///////////////////////
  $("#nic").keyup(function () {
    nic = $(this).val();

    if (nic != "") {
      $.ajax({
        url: "../controller/patient_controller.php?status=patient_search",
        type: "POST",
        dataType: "JSON",
        data: { nic: nic },
        success: function (res) {
          if (res[0] != "NoData") {
            $("#pat_name").val(res[0]);
            $("#" + res[1] + "").prop("checked", true);
            $("#age").val(res[2]);
            $("#blood_grp").val(res[3]);
          } else {
            $("#pat_name").val("");
            $('input[name="gender"]').prop("checked", false);
            $("#age").val("");
            $("#blood_grp").val(0);
          }
        },
        error: function () {
          swal("Ajax Error !", "", "error");
        },
      });
    }
  });

  //////////////////////// Show/hide when check ///////////////////////
  $("input[name=TH]").click(function () {
    var trans_his = $("input[name=TH]:checked").val();

    if (trans_his == "Yes") {
      $("#trans_yes").show("fast");
    } else {
      $("#trans_yes").hide("fast");
    }
  });

  //////////////////////// Show/hide when check ///////////////////////
  $("input[name=AR]").click(function () {
    var any_react = $("input[name=AR]:checked").val();

    if (any_react == "Yes") {
      $("#any_react").show("fast", function () {
        $("#symptom").focus();
      });
    } else {
      $("#any_react").hide("fast");
    }
  });

  //////////////////////// Check whether valid date or not ///////////////////////
  $("#hbTest_date").on("change", function () {
    var curr_date = new Date();
    var y = curr_date.getFullYear();
    var m = curr_date.getMonth() + 1;
    var d = curr_date.getDate();
    curr_date = new Date("" + y + "-" + m + "-" + d + "").getTime();
    curr_date = curr_date + 19800 * 1000;

    var date = $(this).val();
    date = new Date("" + date + "");
    date = date.getTime();

    if (date > curr_date) {
      swal("Invalid Date", "", "error");
      $(this).val("");
    }
  });

  //////////////////////// Get user details by ajax ///////////////////////
  $("#username,#pw").keyup(function () {
    var uname = $("#username").val();
    var pw = $("#pw").val();

    if (uname != null && pw != null) {
      $.ajax({
        url: "../controller/user_controller.php?status=get_DetailsByUname",
        type: "POST",
        data: { uname: uname, pw: pw },
        success: function (data) {
          if (data != "NoData") {
            $("#user_id").val(data);
            $("#userChecked").prop("checked", true);
            $("#submit").prop("disabled", false);
            $("#userChecked").html("Checked");
            $("#userChecked").css({
              "background-color": "#0C0",
              color: "#FFF",
              "font-size": "16px",
            });
          } else {
            $("#user_id").val("");
            $("#userChecked").prop("checked", false);
            $("#submit").prop("disabled", true);
            $("#userChecked").html("Not Checked");
            $("#userChecked").css({
              "background-color": "#333",
              color: "#F00",
              "font-size": "14px",
            });
          }
        },
      });
    }
  });

  //////////////////////// Form submittion validations ///////////////////////
  $("#blood_request").on("submit", function () {
    patt_nic_1 = /^\d{9}[vV]$/;
    patt_nic_2 = /^\d{12}$/;

    nic = $("#nic").val();

    if ($("input[name=reqtype]:checked").length < 1) {
      swal("Select Request Type", "", "warning");
      return false;
    } else if ($("input[name=gender]:checked").length < 1) {
      swal("Select Patient's Gender", "", "warning");
      return false;
    } else if (patt_nic_1.test(nic) == false && patt_nic_2.test(nic) == false) {
      $("#nic").focus();
      swal("Invalid N.I.C. Number", "", "warning");
      return false;
    } else if ($("#blood_grp").val() == 0) {
      swal("Select Patient's Blood Group", "", "warning");
      return false;
    } else if ($("input[name=TH]:checked").length < 1) {
      swal("Select Transfusion History Yes/No", "", "warning");
      return false;
    } else if (
      $("input[name=TH]:checked").val() == "Yes" &&
      $("input[name=when]:checked").length < 1
    ) {
      swal("Select Transfusion Time Range", "", "warning");
      return false;
    } else if (
      $("input[name=TH]:checked").val() == "Yes" &&
      $("input[name=AR]:checked").length < 1
    ) {
      swal("Select Having Any Reactions Yes/No", "", "warning");
      return false;
    } else if (
      $("input[name=AR]:checked").val() == "Yes" &&
      $("#symptom").html("")
    ) {
      swal("Please Mention Your Symptom", "", "warning");
      $("#symptom").focus();
      return false;
    }
  });
});
