$(document).ready(function (e) {
  //////////////////////// Allow make changes when click edit button ///////////////////
  $("#make_chng").click(function () {
    $("input").slice(0, 5).prop("readonly", false);
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

  $("#age").keyup(function () {
    var age = $(this).val();
    corr_age = age.replace(/[e]/gi, "");
    $(this).val(corr_age);
  });

  //////////////////////// Form submittion validations ///////////////////////
  $("#update_patient").on("submit", function () {
    var nic = $("#nic").val();

    // RegEx Patterns
    patt_nic_1 = /^\d{9}[vV]$/;
    patt_nic_2 = /^\d{12}$/;

    if (patt_nic_1.test(nic) == false && patt_nic_2.test(nic) == false) {
      $("#nic").focus();
      alert("Invalid N.I.C. Number");
      return false;
    }
  });
});
