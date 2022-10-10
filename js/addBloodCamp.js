$(document).ready(function (e) {
  $("label").addClass("alert-dark");

  //////////////////////// Auto correct input fields with regex ///////////////////
  $("#tar_donors").keyup(function () {
    var tar_don = $(this).val();
    var corr_tar_don = tar_don.replace(/[e]/gi, "");
    $(this).val(corr_tar_don);
  });

  //////////////////////// Check expected donor count is valid count or not ///////////////////
  $("#tar_donors").focusout(function () {
    var tar_donor = $(this).val();

    //////////////////////// If invalid, then popup error alert ///////////////////
    if (tar_donor < 1 || tar_donor == "") {
      swal("Invalid Expected Donor Level", "", "error");
      $(this).val("");
      $("#date").val("");
      $("#date").prop("readonly", true);
      $(this).focus();
      return false;
    } else {
      //////////////////////// If invalid, then remove readonly property from date field ///////////////////
      $("#date").prop("readonly", false);
    }
  });

  //////////////////////// When donor count and date are selected, automatically set staff selection fields ///////////////////
  $("#date, #tar_donors").on("change", function (e) {
    var date = $("#date").val();

    date = new Date("" + date + "");
    date = date.getTime();

    var curr_date = new Date();
    var y = curr_date.getFullYear();
    var m = curr_date.getMonth() + 1;
    var d = curr_date.getDate();
    curr_date = new Date("" + y + "-" + m + "-" + d + "").getTime();
    curr_date = curr_date + 19800 * 1000;

    //////////////////////// If invalid date selection ///////////////////
    if (date < curr_date) {
      swal("Invalid Date", "", "error");
      $("#date").val("");
      return false;
    }

    var date = $("#date").val();
    var tar_donor = $("#tar_donors").val();

    //////////////////////// Calculate staff counts and make corresponding selection fields ///////////////////
    var doc_rate = Math.ceil(tar_donor / 50);
    var nurse_rate = Math.ceil(tar_donor / 34);
    var mem_rate = Math.ceil(tar_donor / 34);

    var doc_output = "";
    var nurse_output = "";
    var mem_output = "";

    //////////////////////// If donor count and date are valid ///////////////////
    if (tar_donor != "" && tar_donor > 0 && date != "") {
      var count = 0;
      //////////////////////// Make docotor selction fields ///////////////////
      for (i = 1; i <= doc_rate; i++) {
        doc_output +=
          "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Doctor Name(" +
          i +
          "): </label> <select class='form-control' id='doc" +
          i +
          "' name='doc[]' required></select></div></div>";
        count++;
      }

      //////////////////////// Get all unreserved doctors for the selected date ///////////////////
      $.ajax({
        url: "../controller/staff_controller.php?status=unreserved_doctors",
        type: "POST",
        data: {
          date: date,
        },
        success: function (data) {
          for (i = 1; i <= doc_rate; i++) {
            $("#doc" + i + "").html("<option value=''>---</option>" + data);
          }
        },
      });

      $("#doc_count").html(doc_output);

      var count = 0;
      //////////////////////// Make nurse selction fields ///////////////////
      for (i = 1; i <= nurse_rate; i++) {
        nurse_output +=
          "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Nurse Name(" +
          i +
          "): </label> <select class='form-control' id='nurse" +
          i +
          "' name='nurse[]' required></select></div></div>";
        count++;
      }

      //////////////////////// Get all unreserved nurse for the selected date ///////////////////
      $.ajax({
        url: "../controller/staff_controller.php?status=unreserved_nurse",
        type: "POST",
        data: {
          date: date,
        },
        success: function (data) {
          for (i = 1; i <= nurse_rate; i++) {
            $("#nurse" + i + "").html("<option value=''>---</option>" + data);
          }
        },
      });

      $("#nurse_count").html(nurse_output);

      var count = 0;
      //////////////////////// Make helping staff selction fields ///////////////////
      for (i = 1; i <= mem_rate; i++) {
        mem_output +=
          "<div class='col-md-4'><div class='form-group'><label class='alert-dark'> Member Name(" +
          i +
          "): </label> <select class='form-control' id='mem" +
          i +
          "' name='mem[]' required></select></div></div>";
        count++;
      }

      //////////////////////// Get all unreserved members for the selected date ///////////////////
      $.ajax({
        url: "../controller/staff_controller.php?status=unreserved_mem",
        type: "POST",
        data: {
          date: date,
        },
        success: function (data) {
          for (i = 1; i <= mem_rate; i++) {
            $("#mem" + i + "").html("<option value=''>---</option>" + data);
          }
        },
      });

      $("#mem_count").html(mem_output);
    }

    ////////////////////////// Validate Duplication Selections //////////////////////////////
    $("select").on("change", function () {
      //////////// Make empty arrays for staff ////////////////
      var doc_array = [];
      var nurse_array = [];
      var mem_array = [];

      ////////////// Push selected doctors IDs into doc array ///////////////
      for (var i = 0; i <= doc_rate - 1; i++) {
        doc_val = $("#doc" + (i + 1) + "").val();
        if (doc_val != "") {
          doc_array.push(doc_val);
        }
      }
      ////////////// Create new array as doc set with unique values and compare it with doc array ///////////////
      var doc_set = new Set(doc_array);
      if (doc_set.size != doc_array.length) {
        swal("Doctor Already Selected !", "", "warning");
        $(this).val("");
        doc_array.pop();
      }

      //////////////// Push selected nurse IDs into doc array ///////////////
      for (var i = 0; i <= nurse_rate - 1; i++) {
        nurse_val = $("#nurse" + (i + 1) + "").val();
        if (nurse_val != "") {
          nurse_array.push(nurse_val);
        }
      }
      ////////////// Create new array as nurse set with unique values and compare it with nurse array ///////////////
      var nurse_set = new Set(nurse_array);
      if (nurse_set.size != nurse_array.length) {
        swal("Nurse Already Selected !", "", "warning");
        $(this).val("");
        nurse_array.pop();
      }

      ///////////////// Push selected members IDs into doc array /////////////
      for (var i = 0; i <= mem_rate - 1; i++) {
        mem_val = $("#mem" + (i + 1) + "").val();
        if (mem_val != "") {
          mem_array.push(mem_val);
        }
      }
      ////////////// Create new array as member set with unique values and compare it with member array ///////////////
      var mem_set = new Set(mem_array);
      if (mem_set.size != mem_array.length) {
        swal("Member Already Selected !", "", "warning");
        $(this).val("");
        mem_array.pop();
      }
    });
  });
});
