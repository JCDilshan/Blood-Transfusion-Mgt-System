$(document).ready(function (e) {
  /////////////////////////// Hide select hospital/camp input fields as default ///////////////////////////
  $("#Bycamp,#Byhosp").hide();

  /////////////////////////// Show hospital/camp input field when click the venue ///////////////////////////
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
});
