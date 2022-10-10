$(document).ready(function (e) {
  ////////// Auto Correction input fields with regex //////////
  $("#name").keyup(function () {
    var name = $(this).val();
    var corr_name = name.replace(/[^a-zA-Z\s\.]/g, "");
    $(this).val(corr_name);
  });

  $("#con_no").keyup(function () {
    var con_no = $(this).val();
    var corr_con_no = con_no.replace(/[e]/gi, "");
    $(this).val(corr_con_no);
  });

  //////////////////////// Donor existance check by ajax ///////////////////////
  $("#nic").keyup(function () {
    var nic = $("#nic").val();

    if (nic != "" && nic != null) {
      $.ajax({
        url: "../controller/donor_controller.php?status=donor_searchByNIC",
        type: "POST",
        dataType: "JSON",
        data: { nic: nic },
        success: function (data) {
          if (data[0] != "fail") {
            $("#donor_res").html("Donor Already Registered*");
            $("#nic").css("border-color", "#F00");
            $("#submit").prop("disabled", true);
          } else {
            $("#donor_res").html("");
            $("#nic").css("border-color", "");
            $("#submit").prop("disabled", false);
          }
        },
      });
    }
  });

  //////////////////////// Age confirmation validations ///////////////////////
  $("#dob").on("change", function (event) {
    var curr_date = new Date();
    var sel_date = $(this).val();
    sel_date = new Date(`${sel_date}`);

    sel_date = sel_date.setHours(0, 0, 0, 0) / (1000 * 60 * 60 * 24 * 365);
    curr_date = curr_date.setHours(0, 0, 0, 0) / (1000 * 60 * 60 * 24 * 365);

    if (curr_date - sel_date < 18) {
      swal("Donor Must Be 18 Years of Age or Older", "", "error");
      $(this).val("");
      return false;
    }
  });

  //////////////////////// Form submittion validations ///////////////////////
  $("#donor_reg").on("submit", function () {
    var con_no = $("#con_no").val();
    var nic = $("#nic").val();
    var email = $("#email").val();

    // RegEx Patterns
    var patt_con_no = /^\d{9}$/;
    var patt_nic_1 = /^\d{9}[vV]$/;
    var patt_nic_2 = /^\d{12}$/;
    var patt_email = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (patt_nic_1.test(nic) == false && patt_nic_2.test(nic) == false) {
      $("#nic").focus();
      swal("Invalid N.I.C. Number !", "", "warning");
      return false;
    } else if ($('input[name="gender"]:checked').length < 1) {
      swal("Please Select Your Gender !", "", "warning");
      return false;
    } else if (patt_con_no.test(con_no) == false) {
      $("#con_no").focus();
      swal("Invalid Contact Number !", "", "warning");
      return false;
    } else if (patt_email.test(email) == false && email != "") {
      $("#email").focus();
      swal("Invalid Email Address !", "", "warning");
      return false;
    }
  });
});
