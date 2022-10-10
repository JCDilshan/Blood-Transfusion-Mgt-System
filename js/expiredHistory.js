$(document).ready(function (e) {
  //////////////////////// Enable datatable plugin /////////////////////
  $("#issueHisTB").DataTable();

  //////////////////////// Get blood bag details by bag ID using ajax ///////////////////////
  $("#issueHisTB").on("click", ".viewBag", function () {
    var bag_id = $(this).val();

    $.ajax({
      url: "../controller/inventory_controller.php?status=get_bagDetails",
      type: "POST",
      dataType: "JSON",
      data: {
        bag_id: bag_id,
      },
      success: function (res) {
        if (res.bag_id != "") {
          $("#view_bag").modal("show"); ////////// Enable modal ///////////
          $("#bag_id").text(bag_id);
          $("#blood_grp").text(res.grp_name);
          $("#comp_type").text(res.comp_name);
          $("#donor_id").text(res.donor_id);
          $("#camp_id").text(res.camp_id);
          $("#hosp_id").text(res.hospital_id);
        } else {
          swal(
            "No Data Found !",
            "The Bag May Have Been Removed Or Something Went Wrong...",
            "error"
          );
        }
      },
      error: function () {
        swal(
          "oops ! something went wrong..",
          "Please Try Again Later",
          "error"
        );
      },
    });
  });
});
