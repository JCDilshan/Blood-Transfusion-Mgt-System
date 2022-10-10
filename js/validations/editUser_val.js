$(document).ready(function (e) {
  //////////////////////// User role check by ajax ///////////////////////
  $("#make_chng").on("click", function () {
    var role = $("#role").val();
    var curr_role = $("#curr_role").val();

    //////////////////////// If role is an administrator, required password to change details ///////////////////////
    if (curr_role != 100 && (role == 1 || role == 100)) {
      swal(
        {
          title:
            "System Requires Relevant Administrator's Password For Change Administrator Details !",
          text: "Enter This Administrator's Password :",
          type: "input",
          imageUrl: "../images/icons/lock.png",
          confirmButtonClass: "btn-success",
          confirmButtonText: "Confirm",
          showCancelButton: false,
          closeOnConfirm: false,
          inputPlaceholder: "",
        },
        function (inputValue) {
          if (inputValue === "") {
            swal(
              "Password Incorrect",
              "Sorry ! Cannot Perform This Task",
              "error"
            );
          } else {
            user_id = $("#user_id").val();

            //////////////////////// Admin password check by ajax ///////////////////////
            $.ajax({
              url: "../controller/user_controller.php?status=check_AdminPw",
              type: "POST",
              data: {
                user_id: user_id,
                pw: inputValue,
              },
              success: function (data) {
                if (data == "ok") {
                  swal({
                    title: "Success Thank You !",
                    text: "",
                    type: "success",
                    timer: 1000,
                    closeOnConfirm: false,
                    showCancelButton: false,
                    showConfirmButton: false,
                  });

                  $("#role").prop("disabled", false);
                  $("input").slice(0, 11).prop("readonly", false);
                  $("#email").prop("readonly", false);
                  $("#uimg,#submit").prop("disabled", false);
                  $("#submit").prop("hidden", false);
                  $("#make_chng").prop("hidden", true);
                  $("#make_chng").prop("disabled", true);
                  $("#fname").focus();
                } else {
                  swal(
                    "Password Incorrect",
                    "Sorry ! Cannot Perform This Task",
                    "error"
                  );
                }
              },
            });
          }
        }
      );
    } else {
      $("#role").prop("disabled", false);
      $("input").slice(0, 11).prop("readonly", false);
      $("#email").prop("readonly", false);
      $("#uimg,#submit").prop("disabled", false);
      $("#submit").prop("hidden", false);
      $("#make_chng").prop("hidden", true);
      $("#make_chng").prop("disabled", true);
      $("#fname").focus();
    }

    return false;
  });

  //////////////////////// Auto correct input fields with regex ///////////////////
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

  //////////////////////// Form submittion validations ///////////////////////
  $("#edit_user").on("submit", function () {
    var resno = $("#resno").val();
    var mno = $("#mno").val();
    var nic = $("#nic").val();
    var email = $("#email").val();

    // RegEx Patterns
    patt_resno = /^\d{9}$/;
    patt_mno = /^\d{8}$/;
    patt_nic_1 = /^\d{9}[vV]$/;
    patt_nic_2 = /^\d{12}$/;
    patt_email = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    if (patt_resno.test(resno) == false && resno != "") {
      $("#resno").focus();
      swal("Invalid Residential Number", "", "warning");
      return false;
    } else if (patt_mno.test(mno) == false) {
      $("#mno").focus();
      swal("Invalid Mobile Number", "", "warning");
      return false;
    } else if (patt_nic_1.test(nic) == false && patt_nic_2.test(nic) == false) {
      $("#nic").focus();
      swal("Invalid N.I.C. Number", "", "warning");
      return false;
    } else if (patt_email.test(email) == false) {
      $("#email").focus();
      swal("Invalid Email Address", "", "warning");
      return false;
    }
  });
});

///////////////// Preview User Image /////////////////////
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
