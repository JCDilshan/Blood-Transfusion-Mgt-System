$(document).ready(function () {
  /////////////////////////// Logout Confirmation //////////////////////////
  $("#signout").on("click", function () {
    var loc = $(this).attr("href");

    swal(
      {
        title: "Are you sure want to logout ?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonClass: "btn-info",
        closeOnConfirm: true,
      },
      function (isConfirm) {
        if (isConfirm === true) {
          window.location.href = loc;
        }
      }
    );

    return false;
  });

  /////////////////////////// Sidebar Collapse /////////////////////////////
  $("#sidebarCollapse").on("click", function () {
    $("#sidebar").toggleClass("active");
  });
  /////////////////////////// Make font weight bolder on labels /////////////////////////////
  $("label").css({
    "font-weight": "bolder",
  });

  /////////////////////////// Get new messages count by ajax /////////////////////////////
  $.ajax({
    url: "../controller/msg_notif_controller.php?status=msg_refresh",
    type: "GET",
    success: function (res) {
      if (res > 0) {
        $("#msgCount").text(res);
      }
    },
  });

  /////////////////////////// Consistently check new messages without refreshing page by ajax /////////////////////////////
  // setInterval(function () {
  //   $.ajax({
  //     url: "../controller/msg_notif_controller.php?status=msg_refresh",
  //     type: "GET",
  //     success: function (res) {
  //       if (res > 0) {
  //         $("#msgCount").text(res);
  //       } else {
  //         $("#msgCount").fadeOut("slow");
  //       }
  //     },
  //   });
  // }, 10 * 1000);

  /////////////////////////// Get new notifications count by ajax /////////////////////////////
  $.ajax({
    url: "../controller/msg_notif_controller.php?status=notif_refresh",
    type: "GET",
    success: function (res) {
      if (res != "noNotif") {
        if (res > 0) {
          $("#notifCount").text(res);
        }
      }
    },
  });

  /////////////////////////// Consistently check new notifications without refreshing page by ajax /////////////////////////////
  setInterval(function () {
    $.ajax({
      url: "../controller/msg_notif_controller.php?status=notif_refresh",
      type: "GET",
      success: function (res) {
        if (res != "noNotif") {
          if (res > 0) {
            $("#notifCount").text(res);
          } else {
            $("#notifCount").fadeOut("slow");
          }
        } else {
          $("#notifCount").fadeOut("slow");
        }
      },
    });
  }, 10 * 1000);

  /////////////////////////// All notifications load into the notification panel /////////////////////////////
  $("#notif_Bodydiv").load("../includes/notif_refresh.php");

  /////////////////////////// Consistently load new notifications into the panel by ajax /////////////////////////////
  // setInterval(function () {
  //   $("#notif_Bodydiv").load("../includes/notif_refresh.php");
  // }, 6 * 1000);

  /////////////////////////// When check notifications, set those status as seen /////////////////////////////
  $("#notifBtn").on("click", function (e) {
    $("#notif_Bodydiv").fadeToggle("fast", function () {
      $.ajax({
        url: "../controller/msg_notif_controller.php?status=set_seenNotif",
        type: "GET",
      });
    });
  });

  /////////////////////////// Hide notification panel when click the outside of notification panel /////////////////////////////
  $("body").click(function (event) {
    var value = event.target.getAttribute("id");

    if (
      value !== "notifBtn" &&
      value !== "notifCount" &&
      value !== "notif_Bell"
    ) {
      $("#notif_Bodydiv").fadeOut("fast");
    }
  });
});
