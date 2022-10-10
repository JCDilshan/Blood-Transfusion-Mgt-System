$(document).ready(function (e) {
  //////////////////////// Get donor details by ajax ///////////////////////
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
            $("#donor_name").val(data.donor_name);
            if (data.donor_status == 1) {
              $("#loc").prop("disabled", false);
            } else {
              //////////////////////// If donor has been blocked ///////////////////////
              swal(
                "Mr./Mrs. " +
                  data.donor_name +
                  ", Sorry Your Account Has Been Blocked !",
                "Please Contact The Relevant Officer For More Details",
                "error"
              );
              $("#loc").prop("disabled", true);
              $("#nic").val("");
              $("#donor_name").val("");
            }
          } else {
            $("#loc").prop("disabled", true);
            $("#loc").val("");
            $("#date").prop("disabled", true);
            $("#date").val("");
            $("#time").prop("disabled", true);
            $("#time").val("");
            $("#donor_name").val("");
          }
        },
      });
    }
  });

  //////////////////////// Get available dates from venue by ajax ///////////////////////
  $("#loc").on("change", function () {
    loc = $("#loc").val();

    if (loc != "") {
      $("#date").prop("disabled", false);

      $.ajax({
        url: "../controller/schedule_controller.php?status=get_dates",
        type: "POST",
        data: { loc_id: loc },
        success: function (data) {
          $("#date").html(data);
          $("#time").html("");
          $("#time").prop("disabled", true);
        },
      });
    } else {
      $("#date").prop("disabled", true);
    }
  });

  //////////////////////// Get available times from dates by ajax ///////////////////////
  $("#date").on("change", function () {
    var loc = $("#loc").val();
    var date = $("#date").val();
    var name = $("#donor_name").val();
    var nic = $("#nic").val();

    //////////////////////// Check whether donor eligible or not to donate blood ///////////////////////
    if (date != "") {
      $.ajax({
        url: "../controller/schedule_controller.php?status=check_appointValidation",
        type: "POST",
        data: { nic: nic, date: date },
        success: function (res) {
          if (res == "appfail") {
            swal(
              "Mr./Mrs. " + name + ", Sorry You Already Made an Appointment !",
              "If you want to make this appointment,\nPlease cancel previous one and try again. Thank You !",
              "error"
            );

            $("#loc").prop("disabled", true);
            $("#nic").val("");
            $("#loc").val("");
            $("#date").prop("disabled", true);
            $("#date").val("");
            $("#time").prop("disabled", true);
            $("#time").val("");
            $("#donor_name").val("");
          } else if (res == "donfail") {
            swal(
              "Mr./Mrs. " + name + ", Sorry You Cannot Make Appointment Now !",
              "You must wait at least 90 days after the previous donation.Thank You !",
              "error"
            );

            $("#loc").prop("disabled", true);
            $("#nic").val("");
            $("#loc").val("");
            $("#date").prop("disabled", true);
            $("#date").val("");
            $("#time").prop("disabled", true);
            $("#time").val("");
            $("#donor_name").val("");
          } else {
            if (date != "" && loc != "") {
              $("#time").prop("disabled", false);

              $.ajax({
                url: "../controller/schedule_controller.php?status=get_times",
                type: "POST",
                data: { loc_id: loc, date: date },
                success: function (data) {
                  $("#time").html(data);
                },
              });
            } else {
              $("#time").prop("disabled", true);
            }
          }
        },
        error: function () {
          alert("AJAX ERROR");
        },
      });
    }
  });

  //////////////////////// Form submittion validations ///////////////////////
  $("#make_appoin").submit(function () {
    if ($("#donor_name").val() == "" || $("#donor_name").val() == null) {
      swal("Invalid Donor N.I.C. ! Please Check Donor N.I.C.", "", "error");
      return false;
    }
  });
});
