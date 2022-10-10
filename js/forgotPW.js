localStorage.clear(); //////////// Clear local memory storage when page is loaded /////////////

//////////////////////// Define time count down function /////////////////////
function Count_Time() {
  var set_time = new Date().getTime() + 1000 * 62 * 1.5; //////////// Set timeout to 90 seconds /////////////

  var isExec = "yes"; //////////// Store a variable to be aware whether funtion is already executed or not  /////////////

  localStorage.setItem("isExec", isExec); //////////// Set that variable in local memory storage /////////////

  x = setInterval(function () {
    var now_time = new Date().getTime();
    var time_gap = set_time - now_time;

    var minutes = Math.floor((time_gap % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((time_gap % (1000 * 60)) / 1000);

    document.getElementById("count_time").innerHTML =
      "Verification code will be expires :- " + minutes + "m " + seconds + "s";

    //////////// If time over /////////////
    if (time_gap < 1) {
      clearInterval(x);

      //////////// Hide submit OTP form and time countdown row /////////////
      $("#subotp_row").hide();
      $("#countTime_row").hide();

      swal({
        title: "Time Over !!. Try Again..",
        type: "error",
        showCancelButton: false,
        showConfirmButton: false,
        closeOnConfirm: true,
        timer: 1000,
      });
    }
  }, 1000);
}

$(document).ready(function (e) {
  //////////// Hide submit OTP form and time countdown row as default /////////////
  $("#countTime_row").hide();
  $("#subotp_row").hide();

  //////////// Send OTP form submittion validations /////////////
  $("#send_otpForm").on("submit", function () {
    var email = $("#email").val();

    //////////// Send request to the controller file /////////////
    if (email != "") {
      $.ajax({
        url: "../controller/login_controller.php?status=send_otp",
        type: "POST",
        dataType: "JSON",
        data: {
          email: email,
        },
        beforeSend: function () {
          swal({
            title: "Please wait...",
            text: "",
            type: "info",
            imageUrl: "../images/icons/loading.gif",
            closeOnConfirm: false,
            showCancelButton: false,
            showConfirmButton: false,
          });
        },
        success: function (res) {
          $("#email").val(res[0]);
          if (res[1] == "sendOk") {
            $("#send_otpBtn").val("Resend Code");

            //////////// If already has been exectued count_time() function, then clear it /////////////
            isExec = localStorage.getItem("isExec");
            if (isExec == "yes") {
              clearInterval(x);
            }

            //////////// Execute count_time() function again /////////////
            Count_Time();

            //////////// Send request to the controller file /////////////
            $("#subotp_row").show("fast");
            $("#countTime_row").show("fast");
            $("#otp").focus();
            swal({
              title: "Email Has Been Sent !",
              type: "success",
              showCancelButton: false,
              showConfirmButton: false,
              closeOnConfirm: false,
              timer: 1000,
            });
          } else if (res[1] == "sendFail") {
            swal(
              "Oops Something Went Wrong..Check Your Internet Connection or Try Again Later !",
              "Email could not be sent",
              "error"
            );
          } else {
            swal(
              "Sorry..An account with this Email address cannot be found in our system.Please enter correct Email & try again",
              "Email could not be sent",
              "error"
            );
          }
        },
        error: function () {
          alert("Error");
        },
      });
    }

    return false;
  });

  //////////// Submit OTP form submittion validations /////////////
  $("#subotp_Form").on("submit", function () {
    var otp = $("#otp").val();
    var email = $("#email").val();

    if (otp != "") {
      $.ajax({
        url: "../controller/login_controller.php?status=sub_otp",
        type: "POST",
        dataType: "JSON",
        data: {
          email: email,
          otp: otp,
        },
        success: function (res) {
          if (res[1] == "ok") {
            clearInterval(x); //////////// Remove time count down function /////////////
            localStorage.removeItem("isExec"); //////////// Remove memory stored variable after work done /////////////
            $("#countTime_row").hide();
            $("#subotp_row").hide();

            swal({
              title: "Success !",
              type: "success",
              timer: 1000,
              closeOnConfirm: false,
              showCancelButton: false,
              showConfirmButton: false,
            });

            $("#succ_email").val(res[0]);
            $("#resetPW").modal("show"); //////////// Show password reset form /////////////
          } else {
            swal("OTP Incorrect", "Task Fail !", "error");
          }
        },
      });
    }

    return false;
  });

  ///////////////// Remove whitespaces from password field ////////////////////
  $("#pw").keyup(function () {
    var get_pw = $(this).val();
    corr_pw = get_pw.replace(/\s/g, "");
    $(this).val(corr_pw);
  });

  ///////////////// Password fields visibility toggle ////////////////////
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

  ///////////////// Password reset form submittion validations ////////////////////
  $("#resetPw_Form").on("submit", function () {
    var pw = $("#pw").val();
    var cpw = $("#cpw").val();
    var email = $("#succ_email").val();

    if (pw.length < 6) {
      $("#pw").focus();
      swal("Password Must Have Contains At least 6 Characters", "", "warning");
    } else if (pw != cpw) {
      $("#cpw").focus();
      swal("Password Confirmation Doesn't Match", "", "error");
    } else {
      $.ajax({
        url: "../controller/login_controller.php?status=reset_pw",
        data: {
          new_pw: pw,
          email: email,
        },
        type: "POST",
        success: function (res) {
          if (res == "success") {
            window.location.href = "../view/login.php?resstatus=ok";
          } else {
            swal("Oops Somthing Went Wrong..Try Again Later", "", "error");
          }
        },
      });
    }

    return false;
  });
});
