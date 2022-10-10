$(document).ready(function (e) {
  $("label").addClass("alert-dark");

  ////////////////////////// Allow make changes when click edit button ////////////////////////////
  $("#edit").click(function () {
    $(this).hide();
    $("#submit").prop("hidden", false);
    $("#submit").prop("disabled", false);
    $("input").slice(1, 6).prop("readonly", false);
    $("textarea").prop("readonly", false);
    $("select").prop("disabled", false);
    $("#org_name").focus();
  });

  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#tar_donors").keyup(function () {
    var tar_don = $(this).val();
    corr_tar_don = tar_don.replace(/[e]/gi, "");
    $(this).val(corr_tar_don);
  });

  //////////////////////// Get current assigned and unreserved staff by ajax ///////////////////
  function get_currStaff() {
    //////////////////////// Current assigned doctors ///////////////////
    $.ajax({
      url: "../controller/camp_controller.php?status=editCamp_getdoctors",
      type: "POST",
      dataType: "JSON",
      data: {
        camp_id: $("#camp_id").val(),
      },
      success: function (res) {
        if (res[0] != "NoData") {
          var doc_output = "";

          ////////////// Insert each assigned doctor into separated selection field ////////////
          for (var i = 1; i <= res.length; i++) {
            doc_output +=
              "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Doctor Name(" +
              i +
              "): </label> <select class='form-control' id='doc" +
              i +
              "' name='doc[]' required disabled><option value='" +
              res[i - 1][0] +
              "'>" +
              res[i - 1][1] +
              "</option>";

            ////////////// Insert again rest of assigned doctors ////////////
            for (var j = 1; j <= res.length; j++) {
              if (res[j - 1][0] == res[i - 1][0]) {
                continue;
              }
              doc_output +=
                "<option value='" +
                res[j - 1][0] +
                "'>" +
                res[j - 1][1] +
                "'</option>";
            }

            doc_output += "</select></div></div>";
          }

          $("#doc_count").html(doc_output);
        }
      },
    });

    //////////////////////// Current assigned nurse ///////////////////
    $.ajax({
      url: "../controller/camp_controller.php?status=editCamp_getnurse",
      type: "POST",
      dataType: "JSON",
      data: {
        camp_id: $("#camp_id").val(),
      },
      success: function (res) {
        if (res[0] != "NoData") {
          var nurse_output = "";

          ////////////// Insert each assigned nurse into separated selection field ////////////
          for (var i = 1; i <= res.length; i++) {
            nurse_output +=
              "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Nurse Name(" +
              i +
              "): </label> <select class='form-control' id='nurse" +
              i +
              "' name='nurse[]' required disabled><option value='" +
              res[i - 1][0] +
              "'>" +
              res[i - 1][1] +
              "</option>";

            ////////////// Insert again rest of assigned nurse ////////////
            for (var j = 1; j <= res.length; j++) {
              if (res[j - 1][0] == res[i - 1][0]) {
                continue;
              }
              nurse_output +=
                "<option value='" +
                res[j - 1][0] +
                "'>" +
                res[j - 1][1] +
                "</option>";
            }

            nurse_output += "</select></div></div>";
          }

          $("#nurse_count").html(nurse_output);
        }
      },
    });

    //////////////////////// Current assigned members ///////////////////
    $.ajax({
      url: "../controller/camp_controller.php?status=editCamp_getmem",
      type: "POST",
      dataType: "JSON",
      data: {
        camp_id: $("#camp_id").val(),
      },
      success: function (res) {
        if (res[0] != "NoData") {
          var mem_output = "";

          ////////////// Insert each assigned member into separated selection field ////////////
          for (var i = 1; i <= res.length; i++) {
            mem_output +=
              "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Member Name(" +
              i +
              "): </label> <select class='form-control' id='mem" +
              i +
              "' name='mem[]' required disabled><option value='" +
              res[i - 1][0] +
              "'>" +
              res[i - 1][1] +
              "</option>";

            ////////////// Insert again rest of assigned members ////////////
            for (var j = 1; j <= res.length; j++) {
              if (res[j - 1][0] == res[i - 1][0]) {
                continue;
              }
              mem_output +=
                "<option value='" +
                res[j - 1][0] +
                "'>" +
                res[j - 1][1] +
                "</option>";
            }

            mem_output += "</select></div></div>";
          }

          $("#mem_count").html(mem_output);
        }
      },
    });

    //////////////////////// Also get unreserved staff for selected date by ajax ///////////////////
    setTimeout(function () {
      ////////// Unresereved doctors ////////////
      $.ajax({
        url: "../controller/staff_controller.php?status=unreserved_doctors",
        type: "POST",
        data: {
          date: $("#date").val(),
        },
        success: function (data) {
          var doc_rate = Math.ceil($("#tar_donors").val() / 50);
          for (var i = 1; i <= doc_rate; i++) {
            $("#doc" + i + "").append(data);
          }
        },
      });

      ////////// Unresereved nurse ////////////
      $.ajax({
        url: "../controller/staff_controller.php?status=unreserved_nurse",
        type: "POST",
        data: {
          date: $("#date").val(),
        },
        success: function (data) {
          var nurse_rate = Math.ceil($("#tar_donors").val() / 34);
          for (var i = 1; i <= nurse_rate; i++) {
            $("#nurse" + i + "").append(data);
          }
        },
      });

      ////////// Unresereved members ////////////
      $.ajax({
        url: "../controller/staff_controller.php?status=unreserved_mem",
        type: "POST",
        data: {
          date: $("#date").val(),
        },
        success: function (data) {
          var mem_rate = Math.ceil($("#tar_donors").val() / 34);
          for (var i = 1; i <= mem_rate; i++) {
            $("#mem" + i + "").append(data);
          }
        },
      });
    }, 500);
  }
  //////////////////////// Call method ///////////////////
  get_currStaff();

  //////////////////////// After embedded all staff members completely //////////////////////
  setTimeout(function () {
    ////////// When change staff selection fields ////////////
    $("select").change(function () {
      ////////// Set edit statment as 1 for detect user has make changes in staff fields ////////////
      $("#editStmt").val(1);

      var tar_donor = $("#tar_donors").val();

      //////////// Calculate staff counts and make corresponding selection fields ///////////
      var doc_rate = Math.ceil(tar_donor / 50);
      var nurse_rate = Math.ceil(tar_donor / 34);
      var mem_rate = Math.ceil(tar_donor / 34);

      var doc_array = [];
      var nurse_array = [];
      var mem_array = [];

      // Avoid Duplicate Doctor Selections
      for (var i = 0; i <= doc_rate - 1; i++) {
        doc_val = $("#doc" + (i + 1) + "").val();
        if (doc_val != 0) {
          doc_array.push(doc_val);
        }
      }
      var doc_set = new Set(doc_array);
      if (doc_set.size != doc_array.length) {
        swal("Doctor Already Selected !", "", "warning");
        $(this).val(0);
        doc_array.pop();
      }

      // Avoid Duplicate Nurse Selections
      for (var i = 0; i <= nurse_rate - 1; i++) {
        nurse_val = $("#nurse" + (i + 1) + "").val();
        if (nurse_val != 0) {
          nurse_array.push(nurse_val);
        }
      }
      var nurse_set = new Set(nurse_array);
      if (nurse_set.size != nurse_array.length) {
        swal("Nurse Already Selected !", "", "warning");
        $(this).val(0);
        nurse_array.pop();
      }

      // Avoid Duplicate Member Selections
      for (var i = 0; i <= mem_rate - 1; i++) {
        mem_val = $("#mem" + (i + 1) + "").val();
        if (mem_val != 0) {
          mem_array.push(mem_val);
        }
      }
      var mem_set = new Set(mem_array);
      if (mem_set.size != mem_array.length) {
        swal("Member Already Selected !", "", "warning");
        $(this).val(0);
        mem_array.pop();
      }
    });
  }, 800);

  // Get Currently Set Date and donors count
  var init_date = $("#date").val();
  var init_donors = $("#tar_donors").val();
  var change_count = 0;

  ///// Changing Date Selections Staff /////
  $("#date, #tar_donors").on("change", function (e) {
    var tar_donor = $("#tar_donors").val();
    var date = $("#date").val();

    var curr_date = new Date();
    var y = curr_date.getFullYear();
    var m = curr_date.getMonth() + 1;
    var d = curr_date.getDate();
    curr_date = new Date("" + y + "-" + m + "-" + d + "").getTime();
    curr_date = curr_date + 19800 * 1000;

    date = new Date("" + date + "");
    date = date.getTime();

    if (tar_donor < 1 || tar_donor == "") {
      swal("Invalid Expected Donor Level", "", "warning");
      $("#date").val("");
      $("#tar_donors").val("");
      return false;
    } else if (date < curr_date) {
      swal("Invalid Date", "", "error");
      $(this).val("");
      return false;
    } else {
      var date = $("#date").val();

      var doc_rate = Math.ceil(tar_donor / 50);
      var nurse_rate = Math.ceil(tar_donor / 34);
      var mem_rate = Math.ceil(tar_donor / 34);

      var doc_output = "";
      var nurse_output = "";
      var mem_output = "";

      new_date = $("#date").val();

      if (change_count == 0) {
        swal(
          {
            title:
              "If You Change The Date or Donors Level, Then All The Staff Selection Fields Will Be Reset !",
            text: "Do You Want To Perform This Action ?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, I want",
            cancelButtonClass: "btn-info",
            closeOnConfirm: true,
          },
          function (isConfirm) {
            if (isConfirm === true) {
              set_changes();
              change_count++;
            } else {
              $("#date").val(init_date);
              $("#tar_donors").val(init_donors);
              change_count = 0;
              return false;
            }
          }
        );
      } else {
        set_changes();
      }

      function set_changes() {
        if (tar_donor != "" && date != "") {
          count = 0;

          // Set Doctors
          for (i = 1; i <= doc_rate; i++) {
            doc_output +=
              "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Doctor Name(" +
              i +
              "): </label> <select class='form-control' id='doc" +
              i +
              "' name='doc[]' required></select></div></div>";
            count++;
          }
          if (count % 3 == 0) {
            doc_output += "</div><div class='row'>";
          }

          $.ajax({
            url: "../controller/staff_controller.php?status=unreserved_doctors",
            type: "POST",
            data: {
              date: date,
            },
            success: function (data) {
              for (var i = 1; i <= doc_rate; i++) {
                $("#doc" + i + "").html("<option value=''>---</option>" + data);
              }
            },
          });

          $("#doc_count").html(doc_output);

          count = 0;

          // Set Nurse
          for (i = 1; i <= nurse_rate; i++) {
            nurse_output +=
              "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Nurse Name(" +
              i +
              "): </label> <select class='form-control' id='nurse" +
              i +
              "' name='nurse[]' required></select></div></div>";
            count++;
          }
          if (count % 3 == 0) {
            nurse_output += "</div><div class='row'>";
          }

          $.ajax({
            url: "../controller/staff_controller.php?status=unreserved_nurse",
            type: "POST",
            data: {
              date: date,
            },
            success: function (data) {
              for (var i = 1; i <= nurse_rate; i++) {
                $("#nurse" + i + "").html(
                  "<option value=''>---</option>" + data
                );
              }
            },
          });

          $("#nurse_count").html(nurse_output);

          count = 0;

          // Set Members
          for (i = 1; i <= mem_rate; i++) {
            mem_output +=
              "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Member Name(" +
              i +
              "): </label> <select class='form-control' id='mem" +
              i +
              "' name='mem[]' required></select></div></div>";
            count++;
          }
          if (count % 3 == 0) {
            mem_output += "</div><div class='row'>";
          }

          $.ajax({
            url: "../controller/staff_controller.php?status=unreserved_mem",
            type: "POST",
            data: {
              date: date,
            },
            success: function (data) {
              for (var i = 1; i <= mem_rate; i++) {
                $("#mem" + i + "").html("<option value=''>---</option>" + data);
              }
            },
          });

          $("#mem_count").html(mem_output);

          //When Select Current Date Again
          if (init_date == new_date) {
            $.ajax({
              url: "../controller/camp_controller.php?status=editCamp_getdoctors",
              type: "POST",
              dataType: "JSON",
              data: {
                camp_id: $("#camp_id").val(),
              },
              success: function (res) {
                if (res[0] != "NoData") {
                  for (var a = 1; a <= doc_rate; a++) {
                    for (var i = 1; i <= res.length; i++) {
                      $("#doc" + a + "").append(
                        "<option value='" +
                          res[i - 1][0] +
                          "'>" +
                          res[i - 1][1] +
                          "</option>"
                      );
                    }
                  }
                }
              },
            });

            $.ajax({
              url: "../controller/camp_controller.php?status=editCamp_getnurse",
              type: "POST",
              dataType: "JSON",
              data: {
                camp_id: $("#camp_id").val(),
              },
              success: function (res) {
                if (res[0] != "NoData") {
                  for (var b = 1; b <= nurse_rate; b++) {
                    for (var i = 1; i <= res.length; i++) {
                      $("#nurse" + b + "").append(
                        "<option value='" +
                          res[i - 1][0] +
                          "'>" +
                          res[i - 1][1] +
                          "</option>"
                      );
                    }
                  }
                }
              },
            });

            $.ajax({
              url: "../controller/camp_controller.php?status=editCamp_getmem",
              type: "POST",
              dataType: "JSON",
              data: {
                camp_id: $("#camp_id").val(),
              },
              success: function (res) {
                if (res[0] != "NoData") {
                  for (var c = 1; c <= mem_rate; c++) {
                    for (var i = 1; i <= res.length; i++) {
                      $("#mem" + c + "").append(
                        "<option value='" +
                          res[i - 1][0] +
                          "'>" +
                          res[i - 1][1] +
                          "</option>"
                      );
                    }
                  }
                }
              },
            });
          }
        }

        //// Avoid Duplication Selections ////
        $("select").on("change", function () {
          $("#editStmt").val(1);

          var doc_array = [];
          var nurse_array = [];
          var mem_array = [];

          // Avoid Duplicate Doctor Selections
          for (var i = 0; i <= doc_rate - 1; i++) {
            doc_val = $("#doc" + (i + 1) + "").val();
            if (doc_val != 0) {
              doc_array.push(doc_val);
            }
          }
          var doc_set = new Set(doc_array);
          if (doc_set.size != doc_array.length) {
            swal("Doctor Already Selected !", "", "warning");
            $(this).val(0);
            doc_array.pop();
          }

          // Avoid Duplicate Nurse Selections
          for (var i = 0; i <= nurse_rate - 1; i++) {
            nurse_val = $("#nurse" + (i + 1) + "").val();
            if (nurse_val != 0) {
              nurse_array.push(nurse_val);
            }
          }
          var nurse_set = new Set(nurse_array);
          if (nurse_set.size != nurse_array.length) {
            swal("Nurse Already Selected !", "", "warning");
            $(this).val(0);
            nurse_array.pop();
          }

          // Avoid Duplicate Member Selections
          for (var i = 0; i <= mem_rate - 1; i++) {
            mem_val = $("#mem" + (i + 1) + "").val();
            if (mem_val != 0) {
              mem_array.push(mem_val);
            }
          }
          var mem_set = new Set(mem_array);
          if (mem_set.size != mem_array.length) {
            swal("Member Already Selected !", "", "warning");
            $(this).val(0);
            mem_array.pop();
          }
        });
      }
    }
  });
});
