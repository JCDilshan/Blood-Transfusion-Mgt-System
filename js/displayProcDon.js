$(document).ready(function (e) {
  //////////////////////// Hide reject reason field initially ///////////////////////
  $("#rej_reason").hide();

  //////////////////////// When click reject blood button ///////////////////////
  $("#rejectBtn").click(function () {
    $("#accBtn").hide();
    $("#rejectBtn").hide();
    $("#actionValue").val(0);
    $("#rej_reason").show("fast", function () {
      $("#rej_reasonTxt").focus();
    });
  });

  //////////////////////// Change the reject button's text according to the reason ///////////////////////
  $("#rej_reasonTxt").on("focus keyup", function () {
    var reason = $(this).val();

    if (reason == "" || reason == null) {
      $("#rejectBtn2").html(
        "Reject Without Mention <i class='fa fa-times-circle'></i>"
      );
    } else {
      $("#rejectBtn2").html("Reject <i class='fa fa-times-circle'></i>");
    }
  });

  //////////////////////// Check date field when submit the form ///////////////////////
  $("#rejectBtn2, #accBtn").on("click", function () {
    var check_date = $("#check_date").val();

    if (check_date == "") {
      $("#check_date").focus();
      swal("Please Select Blood Checked Date", "", "warning");
    } else {
      swal(
        {
          title: "Confirm This Action Again",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Confirm",
          cancelButtonClass: "btn-info",
          cancelButtonText: "Cancel",
          closeOnConfirm: true,
        },
        function (isConfirm) {
          if (isConfirm === true) {
            $("#CheckResultForm").submit();
          }
        }
      );
    }

    return false;
  });

  //////////////////////// Check whether valid date or not ///////////////////////
  $("#check_date").on("change", function () {
    var curr_date = new Date();
    var y = curr_date.getFullYear();
    var m = curr_date.getMonth() + 1;
    var d = curr_date.getDate();
    curr_date = new Date("" + y + "-" + m + "-" + d + "").getTime();
    curr_date = curr_date + 19800 * 1000;

    var date = $(this).val();
    date = new Date("" + date + "");
    date = date.getTime();

    if (date > curr_date) {
      swal("Invalid Date", "", "error");
      $(this).val("");
    }
  });
});
