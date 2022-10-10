$(document).ready(function (e) {
  //////////////////////// Allow to make changes when click edit button ///////////////////////
  $("#make_chng").click(function () {
    $("input").slice(0, 8).prop("readonly", false);
    $("#disc_id").prop("disabled", false);
    $("#other_info").prop("readonly", false);
    $("#submit,#blood_grp").prop("disabled", false);
    $("#make_chng").prop("hidden", true);
    $("#name").focus();
    return false;
  });

  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#name").keyup(function () {
    var name = $(this).val();
    corr_name = name.replace(/[^a-zA-Z\s\.]/g, "");
    $(this).val(corr_name);
  });

  $("#con_no").keyup(function () {
    var con_no = $(this).val();
    corr_con_no = con_no.replace(/[e]/gi, "");
    $(this).val(corr_con_no);
  });

  $("#zip").keyup(function () {
    var zip = $(this).val();
    corr_zip = zip.replace(/[e]/gi, "");
    $(this).val(corr_zip);
  });

  //////////////////////// Form submittion validations ///////////////////////
  $("#update_donor").on("submit", function () {
    var con_no = $("#con_no").val();
    var nic = $("#nic").val();
    var email = $("#email").val();
    var zip = $("#zip").val();

    // RegEx Patterns
    var patt_con_no = /^\d{9}$/;
    var patt_nic_1 = /^\d{9}[vV]$/;
    var patt_nic_2 = /^\d{12}$/;
    var patt_email = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (patt_nic_1.test(nic) == false && patt_nic_2.test(nic) == false) {
      $("#nic").focus();
      swal("Invalid N.I.C. Number", "", "warning");
      return false;
    } else if (patt_con_no.test(con_no) == false) {
      $("#con_no").focus();
      swal("Invalid Residential Number", "", "warning");
      return false;
    } else if (patt_email.test(email) == false && email != "") {
      $("#email").focus();
      swal("Invalid Email Address", "", "warning");
      return false;
    } else if (zip.length != 5) {
      $("#zip").focus();
      swal("Invalid Zip Code", "", "error");
      return false;
    }
  });
});
