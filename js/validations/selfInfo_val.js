$(document).ready(function (e) {
  $("#make_chng").click(function () {
    var role = $("#role").val();

    if (role != 1 && role != 100) {
      $("input").slice(1, 8).prop("readonly", false);
      $("#dob").prop("readonly", false);
      $("#uimg,#submit").prop("disabled", false);
      $("#submit").prop("hidden", false);
      $("#make_chng").prop("hidden", true);
      $("#nic").prop("readonly", true);
      $("#email").prop("readonly", true);
      $("#fname").focus();
    } else {
      $("input").slice(1, 8).prop("readonly", false);
      $("#dob").prop("readonly", false);
      $("#uimg,#submit").prop("disabled", false);
      $("#submit").prop("hidden", false);
      $("#make_chng").prop("hidden", true);
      $("#fname").focus();
    }
  });

  $("#resno").keyup(function () {
    var resno = $(this).val();
    corr_resno = resno.replace(/[e]/gi, "");
    $(this).val(corr_resno);
  });

  $("#mno").keyup(function () {
    var mno = $(this).val();
    corr_mno = mno.replace(/[e]/gi, "");
    $(this).val(corr_mno);
  });

  $("#selfInfo").on("submit", function () {
    var resno = $("#resno").val();
    var mno = $("#mno").val();
    var nic = $("#nic").val();
    var city = $("#city").val();
    var email = $("#email").val();
    var role = $("#role").val();

    // RegEx Patterns
    patt_resno = /^\d{9}$/;
    patt_mno = /^\d{8}$/;
    patt_nic_1 = /^\d{9}[vV]$/;
    patt_nic_2 = /^\d{12}$/;
    patt_email = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (patt_resno.test(resno) == false && resno != "") {
      $("#resno").focus();
      alert("Invalid Residential Number");
      return false;
    } else if (patt_mno.test(mno) == false) {
      $("#mno").focus();
      alert("Invalid Mobile Number");
      return false;
    } else if (patt_nic_1.test(nic) == false && patt_nic_2.test(nic) == false) {
      $("#nic").focus();
      alert("Invalid N.I.C. Number");
      return false;
    } else if (patt_email.test(email) == false) {
      $("#email").focus();
      alert("Invalid Email Address");
      return false;
    }
  });
});
