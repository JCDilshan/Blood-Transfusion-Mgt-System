$(document).ready(function (e) {
  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#donor_id").keyup(function () {
    var d_id = $(this).val();
    corr_d_id = d_id.replace(/[e]/gi, "");
    $(this).val(corr_d_id);
  });

  $("#camp_id").keyup(function () {
    var c_id = $(this).val();
    corr_c_id = c_id.replace(/[e]/gi, "");
    $(this).val(corr_c_id);
  });

  $("#hosp_id").keyup(function () {
    var h_id = $(this).val();
    corr_h_id = h_id.replace(/[e]/gi, "");
    $(this).val(corr_h_id);
  });

  //////////////////////// Blood bag ID existance check by ajax ///////////////////////
  $("#bag_id").keyup(function () {
    var bag_id = $(this).val();

    if (bag_id != "") {
      $.ajax({
        url: "../controller/inventory_controller.php?status=checkBag_exist",
        type: "POST",
        data: { bag_id: bag_id },
        success: function (res) {
          if (res == "HaveData") {
            $("#attention").hide("fast", function () {
              $("#bag_id").css("border-color", "#F00");
              $("#bag_res").html("Bag ID Already Exists*");
              $("#submit").prop("disabled", true);
            });
          } else {
            $("#bag_res").html("");
            $("#bag_id").css("border-color", "");
            $("#attention").show("fast");
            $("#submit").prop("disabled", false);
          }
        },
      });
    }
  });

  //////////////////////// Show/hide input columns ///////////////////////
  $("#Bycamp,#Byhosp").hide();
  $("input[name=where]").click(function () {
    var by = $("input[name=where]:checked").val();

    if (by == "hosp") {
      $("#camp_id").val(0);
      $("#Bycamp").hide("fast", function () {
        $("#Byhosp").show("fast", function () {
          $("#hosp_id").focus();
        });
      });
    } else {
      $("#hosp_id").val(0);
      $("#Byhosp").hide("fast", function () {
        $("#Bycamp").show("fast", function () {
          $("#camp_id").focus();
        });
      });
    }
  });

  //////////////////////// Get donor details by ajax ///////////////////////
  $("#donor_id").keyup(function () {
    donor_id = $("#donor_id").val();

    if (donor_id != "" && donor_id != null) {
      $.ajax({
        url: "../controller/donor_controller.php?status=donor_searchByID",
        type: "POST",
        dataType: "JSON",
        data: { donor_id: donor_id },
        success: function (data) {
          if (data.donor_id != "" && data.donor_id != null) {
            $("#blood_grp").val(data.grp_name);
            $("#grp_id").val(data.grp_id);
          } else {
            $("#blood_grp").val("");
          }
        },
      });
    }
  });

  //////////////////////// Check whether valid date or not ///////////////////////
  $("#sealed_date").on("change", function () {
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

  //////////////////////// Form submittion validations ///////////////////////
  $("#InvenPass").submit(function () {
    grp = $("#blood_grp").val();
    comp = $("#comp_type").val();

    if ($("input[name=where]:checked").length < 1) {
      swal("Select Donated Venue Camp/Hospital", "", "warning");
      return false;
    } else if ($("#hosp_id").val() == 0 && $("#camp_id").val() == 0) {
      swal("Select " + $("input[name=where]:checked").val(), "", "warning");
      return false;
    } else if (grp == "" || grp == null) {
      swal("Incorrect Donor ID", "", "warning");
      $("#donor_id").focus();
      return false;
    }
  });
});
