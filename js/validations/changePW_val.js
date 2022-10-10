$(document).ready(function (e) {
  //////////////////////// Form submittion validations ///////////////////////
  $("#pw_change").submit(function () {
    pw = $("#new_pw").val();
    cpw = $("#cpw").val();

    if (pw.length < 6) {
      $("#pw").focus();
      swal("Password Must Have Contains At least 6 Characters", "", "warning");
      return false;
    } else if (pw != cpw) {
      $("#cpw").focus();
      swal("Password Confirmation Doesn't Match", "", "error");
      return false;
    }
  });

  ////////////////////// Password fields visibility toggle /////////////////////////
  $("#pw_addon").click(function (event) {
    var pw = $("#new_pw").val();
    if (pw == "" || pw == null) {
      swal("Field Empty", "", "warning");
      return false;
    } else {
      if ($("#new_pw").attr("type") == "password") {
        $("#new_pw").attr("type", "text");
      } else if ($("#new_pw").attr("type") == "text") {
        $("#new_pw").attr("type", "password");
      }
      $("#pw_icon").toggleClass("fa fa-eye fa fa-eye-slash");
    }
  });

  $("#cpw_addon").click(function (event) {
    var cpw = $("#cpw").val();
    if (cpw == "" || cpw == null) {
      swal("Field Empty", "", "warning");
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

  ////////////////////// Remove white spaces in password & check strength //////////////////////////
  $("#new_pw").keyup(function () {
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
});
