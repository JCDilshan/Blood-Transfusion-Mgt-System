$(document).ready(function (e) {
  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#fname").keyup(function () {
    var fname = $(this).val();
    corr_fname = fname.replace(/[^a-zA-Z\s\.]/g, "");
    $(this).val(corr_fname);
  });

  $("#lname").keyup(function () {
    var lname = $(this).val();
    corr_lname = lname.replace(/[^a-zA-Z\s]/g, "");
    $(this).val(corr_lname);
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

  //////////////////// Password fields visibility toggle //////////////////////////
  $("#pw_addon").click(function (event) {
    var pw = $("#pw").val();
    if (pw == "" || pw == null) {
      alert("Field Empty");
      return false;
    } else {
      if ($("#pw").attr("type") == "password") {
        $("#pw").attr("type", "text");
      } else if ($("#pw").attr("type") == "text") {
        $("#pw").attr("type", "password");
      }
      $("#pw_icon").toggleClass("fa fa-eye fa fa-eye-slash");
    }
  });

  $("#cpw_addon").click(function (event) {
    var cpw = $("#cpw").val();
    if (cpw == "" || cpw == null) {
      alert("Field Empty");
      return false;
    } else {
      if ($("#cpw").attr("type") == "password") {
        $("#cpw").attr("type", "text");
      } else if ($("#cpw").attr("type") == "text") {
        $("#cpw").attr("type", "password");
      }
      $("#cpw_icon").toggleClass("fa fa-eye fa fa-eye-slash");
    }
  });

  ////////////////// Remove white spaces in password & check strength /////////////////////
  $("#pw").keyup(function () {
    var get_pw = $(this).val();

    corr_pw = get_pw.replace(/\s/g, "");

    $(this).val(corr_pw);

    //////////////////////// Strength meter regex patterns /////////////////////
    var pw_weak_1 = /^[a-zA-Z]{6,}$/;
    var pw_weak_2 = /[0-9]{6,}/;
    var pw_medium = /(?=.*[a-zA-Z])(?=.*[0-9])(?=.{6,})(^((?!\W).)*$)/;
    var pw_strong = /(?=.*[\W])(?=.{6,})/;

    if (corr_pw.match(pw_weak_1) || corr_pw.match(pw_weak_2)) {
      $("#pws_div").removeClass("hidden");
      $("#pws_progress").css({ width: "33%", "background-color": "#F00" });
      $("#pws_res").html("Weak");
    }
    if (corr_pw.match(pw_medium)) {
      $("#pws_div").removeClass("hidden");
      $("#pws_progress").css({ width: "67%", "background-color": "#F90" });
      $("#pws_res").html("Medium");
    }
    if (corr_pw.match(pw_strong)) {
      $("#pws_div").removeClass("hidden");
      $("#pws_progress").css({ width: "100%", "background-color": "#0F0" });
      $("#pws_res").html("Strong");
    }
    if (corr_pw.length < 6) {
      $("#pws_div").addClass("hidden");
    }
  });

  //////////////////////// NIC(user) existance check by ajax ///////////////////////
  $("#nic").on("keyup", function () {
    var nic = $(this).val();

    if (nic != "" || nic != null) {
      $.ajax({
        url: "../controller/user_controller.php?status=user_search",
        type: "POST",
        data: { nic: nic },
        success: function (data) {
          if (data == "fail") {
            $("#user_res").html("This User Has Been Already Registered*");
            $("#nic").css("border-color", "#F00");
          } else {
            $("#nic").css("border-color", "");
            $("#user_res").html("");
          }
        },
      });
    }
  });

  //////////////////////// Email existance check by ajax ///////////////////////
  $("#email").on("keyup", function () {
    var email = $(this).val();

    if (email != "" || email != null) {
      $.ajax({
        url: "../controller/user_controller.php?status=email_search",
        type: "POST",
        data: { email: email },
        success: function (data) {
          if (data == "fail") {
            $("#email_res").html("Email already taken*");
            $("#email").css("border-color", "#F00");
          } else {
            $("#email").css("border-color", "");
            $("#email_res").html("");
          }
        },
      });
    }
  });

  //////////////////////// Username existance check by ajax ///////////////////////
  $("#uname").on("keyup", function () {
    var uname = $(this).val();

    if (uname != "" || uname != null) {
      $.ajax({
        url: "../controller/user_controller.php?status=uname_search",
        type: "POST",
        data: { uname: uname },
        success: function (data) {
          if (data == "fail") {
            $("#uname_res").html("Username already taken*");
            $("#uname").css("border-color", "#F00");
          } else {
            $("#uname").css("border-color", "");
            $("#uname_res").html("");
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
      swal("User Must Be 18 Years of Age or Older", "", "error");
      $(this).val("");
      return false;
    }
  });

  //////////////////////// Submittion validations /////////////////////
  $("#signup").on("submit", function () {
    var resno = $("#resno").val();
    var mno = $("#mno").val();
    var nic = $("#nic").val();
    var email = $("#email").val();
    var pw = $("#pw").val();
    var cpw = $("#cpw").val();

    // RegEx Patterns
    patt_resno = /^\d{9}$/;
    patt_mno = /^\d{8}$/;
    patt_nic_1 = /^\d{9}[vV]$/;
    patt_nic_2 = /^\d{12}$/;
    patt_email = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (
      $("#user_res").html() != "" ||
      $("#email_res").html() != "" ||
      $("#uname").html()
    ) {
      swal("Some input fields are invalid", "", "error");
      return false;
    } else if (patt_nic_1.test(nic) == false && patt_nic_2.test(nic) == false) {
      $("#nic").focus();
      swal("Invalid N.I.C. Number !", "", "warning");
      return false;
    } else if (patt_resno.test(resno) == false && resno != "") {
      $("#resno").focus();
      swal("Invalid Resident Number !", "", "warning");
      return false;
    } else if (patt_mno.test(mno) == false) {
      $("#mno").focus();
      swal("Invalid Mobile Number !", "", "warning");
      return false;
    } else if ($('input[name="gender"]:checked').length < 1) {
      swal("Please Select Your Gender !", "", "warning");
      return false;
    } else if (patt_email.test(email) == false) {
      $("#email").focus();
      swal("Invalid Email Address !", "", "warning");
      return false;
    } else if (pw.length < 6) {
      $("#pw").focus();
      swal("Password Must Have Contains At least 6 Characters", "", "warning");
      return false;
    } else if (pw != cpw) {
      $("#cpw").focus();
      swal("Password Confirmation Doesn't Match", "", "error");
      return false;
    }
  });
});

//////////////////////// Preview user image ///////////////////////
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $("#prev_img").attr("src", e.target.result).height(70).width(80);
    };
    reader.readAsDataURL(input.files[0]);
  } else {
    $("#prev_img").attr("src", "");
  }
}
