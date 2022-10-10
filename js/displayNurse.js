$(document).ready(function (e) {
  $("#fname").focus();

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

  //////////////////////// Form submittion validations ///////////////////////
  $("#editNurseForm").on("submit", function () {
    var con_no = $("#con_no").val();
    var nic = $("#nic").val();

    var patt_conno = /^\d{9}$/;
    var nic_patt_1 = /^\d{9}[vV]$/;
    var nic_patt_2 = /^\d{12}$/;

    if (!nic.match(nic_patt_1) && !nic.match(nic_patt_2)) {
      swal("Invalid N.I.C. Number !", "", "warning");
      $("#nic").focus();
      return false;
    } else if (!con_no.match(patt_conno)) {
      swal("Invalid Contact Number !", "", "warning");
      $("#con_no").focus();
      return false;
    }
  });
});
