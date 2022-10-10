$(document).ready(function (e) {
  //////////////////////// Make form labels to dark ///////////////////////
  $("label").addClass("alert-dark");

  //////////////////////// Auto scrolling to blood request acceptance part ///////////////////////
  $("html,body").animate(
    { scrollTop: $("#acceptancePart").offset().top },
    1000
  );

  //////////////////////// Allow edit required blood bag amount ///////////////////////
  $("#edit_amountBtn").click(function () {
    $("#edit_amountBtn").hide("slow", function () {
      $("#set_amountBtn").prop("hidden", false);
      $("#edit_amount").prop("readonly", false);
      $("#edit_amount").focus();
    });
  });

  //////////////////////// Required blood bags amount validation ///////////////////////
  $("#set_amountBtn").click(function () {
    var new_amount = $("#edit_amount").val();
    var req_id = $("#req_id").val();

    if (new_amount == "" && new_amount == null) {
      swal("New Require Amount is Empty", "", "warning");
    } else {
      $.ajax({
        url: "../controller/patient_controller.php?status=edit_ReqAmount",
        type: "POST",
        data: { req_id: req_id, amount: new_amount },
        success: function (data) {
          $("#edit_amountRes").html(data).css("color", "#00C");
          $("#edit_amount").prop("readonly", true);
          $("#set_amountBtn").prop("hidden", true);
          $("#edit_amountBtn").show("fast");
        },
      });
    }
  });

  //////////////////////// Toggle accept/reject parts ///////////////////////
  $("#acceptable,#unacceptable").hide();

  $("input[name=stmt]").click(function () {
    if ($("input[name=stmt]:checked").val() == "acc") {
      $("#unacceptable").hide("fast", function () {
        $("#acceptable").show("fast", function () {
          $("#anti_A").focus();
        });
      });
    } else if ($("input[name=stmt]:checked").val() == "unacc") {
      $("#acceptable").hide("fast", function () {
        $("#unacceptable").show("fast", function () {
          $("#unacc_reason").focus();
        });
      });
    }
  });

  //////////////////////// Confirmation of form filled user ///////////////////////
  $("#username,#pw").keyup(function () {
    var uname = $("#username").val();
    var pw = $("#pw").val();

    if (uname != null && pw != "") {
      $.ajax({
        url: "../controller/user_controller.php?status=get_DetailsByUname",
        type: "POST",
        data: { uname: uname, pw: pw },
        success: function (data) {
          if (data === "NoData") {
            $("#userChecked")
              .html("Not Checked")
              .css({ "background-color": "#000", color: "#F00" });
            $("button[type=submit]").prop("disabled", true);
            $("input[name=user_id]").val("");
          } else {
            $("#userChecked")
              .html("Checked")
              .css({ "background-color": "#0C0", color: "#FFF" });
            $("button[type=submit]").prop("disabled", false);
            $("input[name=user_id]").val(data);
          }
        },
      });
    }
  });

  //////////////////////// Request accept form validation ///////////////////////
  $("#pendReqAcc").on("submit", function (e) {
    if (
      $("input[name=s1_37]").prop("checked") == false &&
      $("input[name=s2_37]").prop("checked") == false
    ) {
      swal("Please select Screen cells", "S1 37C or S2 37C ?", "warning");
      return false;
    } else if (
      $("input[name=s1_iat]").prop("checked") == false &&
      $("input[name=s2_iat]").prop("checked") == false
    ) {
      swal("Please select Screen cells", "S1 IAT or S2 IAT ?", "warning");
      return false;
    }
  });
});
