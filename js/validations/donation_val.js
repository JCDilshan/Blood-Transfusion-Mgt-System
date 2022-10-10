$(document).ready(function (e) {
  //////////////////////// Blood bag ID existance check by ajax ///////////////////////
  $("#bag_id").keyup(function () {
    var bag_id = $(this).val();

    if (bag_id != "" && bag_id != null) {
      $.ajax({
        url: "../controller/donation_controller.php?status=checkBag_exist",
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

  //////////////////////// Show/hide input column ///////////////////////
  $("#Bycamp,#Byhosp").hide();
  $("input[name=where]").click(function () {
    var by = $("input[name=where]:checked").val();

    if (by == "hospital") {
      $("#camp_id").val(0);
      $("#Bycamp").hide("fast", function () {
        $("#don_date").val("");
        $("#Byhosp").show("fast", function () {
          $("#don_date").prop("readonly", false);
          $("#hosp_id").focus();
        });
      });
    } else {
      $("#hosp_id").val(0);
      $("#Byhosp").hide("fast", function () {
        $("#don_date").val("");
        $("#Bycamp").show("fast", function () {
          $("#don_date").prop("readonly", true);
          $("#camp_id").focus();
        });
      });
    }
  });

  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#don_count").keyup(function () {
    var don_count = $(this).val();
    corr_don_count = don_count.replace(/[e]/gi, "");
    $(this).val(corr_don_count);
  });

  //////////////////////// Get donor details by ajax ///////////////////////
  $("#nic").keyup(function () {
    var nic = $(this).val();

    if (nic != "" && nic != null) {
      $.ajax({
        url: "../controller/donor_controller.php?status=donor_searchByNIC",
        type: "POST",
        dataType: "JSON",
        data: { nic: nic },
        success: function (data) {
          if (data.donor_id != "" && data.donor_id != null) {
            if (data.donor_status == 1) {
              $("#donor_id").val(data.donor_id);
              $("#name").val(data.donor_name);
              $("#blood_grp").val(data.grp_name);
            } else {
              swal("Sorry This Donor Account Has Been Blocked !", "", "error");
              $("#nic").val("");
              $("#name").val("");
            }
          } else {
            $("#donor_id").val("");
            $("#name").val("");
            $("#blood_grp").val("");
          }
        },
      });
    }
  });

  //////////////////////// Get camp details by ajax ///////////////////////
  $("#camp_id").on("change", function () {
    var camp_id = $(this).val();

    if (camp_id != "" && camp_id != null) {
      $.ajax({
        url: "../controller/camp_controller.php?status=search_camp",
        type: "POST",
        data: { camp_id: camp_id },
        success: function (data) {
          $("#don_date").val(data);
        },
      });
    }
  });

  //////////////////////// Check whether valid date or not ///////////////////////
  $("#don_date").on("change", function () {
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
  $("#add_donation").submit(function () {
    var blood_grp = $("#blood_grp").val();

    if (blood_grp == "" || blood_grp == null) {
      swal("Sorry ! Donor Doesn't Exist With Related N.I.C.", "", "error");
      $("#nic").focus();
      return false;
    } else if ($("input[name=where]:checked").length < 1) {
      swal("Select Donated Venue", "", "warning");
      return false;
    } else if ($("#hosp_id").val() == 0 && $("#camp_id").val() == 0) {
      swal("Select " + $("input[name=where]:checked").val(), "", "warning");
      return false;
    }
  });
});
