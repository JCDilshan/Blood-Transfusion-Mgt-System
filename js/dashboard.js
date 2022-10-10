$(document).ready(function (e) {
  /////////////////////////////// Current inventory data row adjusting ///////////////////////////////
  $("#sidebarCollapse").on("click", function () {
    $("#fsvg_Row,#ssvg_Row").toggleClass("after_svg");
  });

  /////////////////////////////// Get Current Inventory Data ///////////////////////////////
  $.ajax({
    url: "../controller/Data_controller.php?status=get_CurrentInvenData",
    type: "GET",
    dataType: "JSON",
    success: function (res) {
      var max_count = 20;
      for (i = 0; i < res.length; i++) {
        var grp = res[i][0];
        var count = res[i][1];
        var stroke_offset = 320 - (320 / max_count) * count;
        var color =
          count >= 15
            ? "#00ff00"
            : count >= 10
            ? "#6bf759"
            : count >= 5
            ? "#f3a81c "
            : "#ff0000";
        $("#grp" + (i + 1) + " circle:nth-child(2)").css({
          "stroke-dashoffset": stroke_offset,
          stroke: color,
        });
        $("#grp" + (i + 1) + " + .text").html(grp);
        $("#grp" + (i + 1) + " text").text(count * 5 + "%");
      }
    },
  });
});
