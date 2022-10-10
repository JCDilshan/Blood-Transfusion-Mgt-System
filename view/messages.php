<?php
include('../includes/session&redirect.php');

include('../model/role_model.php');
$role_object = new Role($conn);
$result = $role_object->getRoles();

$msg = isset($_GET['msg']) ? base64_decode($_GET['msg']) : '';
$res_status = isset($_GET['resstatus']) ? base64_decode($_GET['resstatus']) : '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Messeges</title>

  <?php
  include('../includes/css_assets.php');
  include('../includes/js_assets.php');
  ?>

  <style type="text/css">
    form {
      border: #999999 solid medium;
      border-radius: 10px;
      padding: 15px 15px;
    }

    .new_badge {
      text-align: center;
      color: #FFF;
      width: 50px;
      height: 25px;
      border-radius: 4px;
      background-color: #F00;
      position: relative;
    }

    .from {
      color: #FFF;
      text-align: center;
      background-color: #999;
    }
  </style>

</head>

<body>
  <!-- ///////////////////////// Content wrapper start ///////////////////////// -->
  <div class="wrapper d-flex align-items-stretch">

    <?php
    include('../includes/nav_bar.php');
    ?>

    <div class="container-fluid p-3" style="overflow-y: auto; max-height: 90vh;">

      <div>&nbsp;</div>

      <?php
      if ($res_status == '1') {
      ?>
        <script>
          swal({
            title: "<?php echo $msg; ?>",
            text: "",
            type: "success",
            timer: 2000,
            showConfirmButton: false,
            closeOnConfirm: false
          })
        </script>
      <?php
      } else if ($res_status == '0') {
      ?>
        <script>
          swal({
            title: "<?php echo $msg; ?>",
            text: "Message Not Sent",
            type: "error",
            timer: 2500,
            showConfirmButton: false,
            closeOnConfirm: false
          })
        </script>
      <?php
      }
      ?>

      <button id="newMsgBtn" class="btn btn-outline-primary">Write a New Message <i class="fa fa-plus-circle"></i></button>

      <div class="row pl-2 mt-4" id="newMsgRow">
        <div class="col-md-8 offset-2">

          <form class="shadow" id="send_msg" action="../controller/msg_notif_controller.php?status=send_msg" method="post">

            <div class="row">

              <div class="col-md-6">
                <div class="form-group">
                  <label class="alert-info">Receiver Role :</label>
                  <select class="form-control" name="receiver_role" id="rec_role" required>
                    <option value="">---</option>
                    <option value="all">All</option>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                      <option value="<?php echo $row['role_id']; ?>"><?php echo $row['role_name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="alert-info">Receiver Name :</label>
                  <select class="form-control" id="receiver" name="receiver" disabled required>
                  </select>
                </div>
              </div>

            </div>

            <div>&nbsp;</div>

            <div class="row">

              <div class="col-md-8">
                <div class="form-group">
                  <textarea name="msg_body" placeholder="Write a Message" class="form-control" required></textarea>
                </div>
              </div>

            </div>

            <button class="btn btn-info" type="submit">Send <i class="fa fa-share"></i></button>
          </form>

        </div>
      </div>

      <div>&nbsp;</div>

      <hr>

      <!--Row Start-->
      <div class="row">

        <div class="col-md-10 offset-1">

          <div class="card">

            <div class="card-header text-center bg-dark" style="color:#FFF;">
              <span style="font-size:28px;"><b>Recieved Messeges</b></span>
            </div>

            <div class="card-body bg-light" id="MsgDiv" style="overflow:scroll; max-height:1000px;">

            </div>
          </div>

        </div>

        <div>&nbsp;</div>


        <div class="modal fade" id="reply" role="dialog">
          <div class="modal-dialog modal-md">
            <form id="reply_Form" enctype="multipart/form-data" action="../controller/msg_notif_controller.php?status=send_msg" method="post">
              <div class="modal-content">

                <div class="modal-body">
                  <div class="row">
                  </div>
                  <div class="row">&nbsp;</div>

                  <div>&nbsp;</div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <input id="reply_rec" name="receiver" type="hidden" class="form-control" required>
                        <textarea name="msg_body" class="form-control" placeholder="Write Reply..." required></textarea>
                      </div>
                    </div>
                  </div>

                  <div>&nbsp;</div>

                  <button type="submit" class="btn btn-warning">Send <i class="fa fa-share"></i></button>
                </div>
              </div>

          </div>
        </div>
        </form>
      </div>
    </div>

  </div>

  <!-- ///////////////////////// Content end ///////////////////////// -->
  </div><!-- ///////////////////////// Wrapper end ///////////////////////// -->
  </div>


  <script>
    $(document).ready(function(e) {

      ///////////////////////////// Hide new message form as default ///////////////////////////
      $('#newMsgRow').hide();

      ///////////////////////////// Show form when click the new message button ///////////////////////////
      $('#newMsgBtn').click(function() {
        $('#newMsgRow').toggle('slow', function() {
          $('#rec_role').focus();
        })
      });

      ///////////////////////////// Get all users to related selected role by ajax ///////////////////////////
      $('#rec_role').on('change', function() {

        var rec_role = $(this).val();

        if (rec_role != "") {
          $('#receiver').prop('disabled', false);

          $.ajax({
            url: "../controller/user_controller.php?status=get_usersByRole",
            type: 'POST',
            data: {
              role: rec_role
            },
            success: function(data) {
              $('#receiver').html(data);
            }
          });

        } else {
          $('#receiver').prop('disabled', true);
          $('#receiver').html("<option value=''>---</option>");
        }

      });

      ///////////////////////////// Get all messages by user ID using ajax ///////////////////////////
      $.ajax({
        url: "../controller/msg_notif_controller.php?status=get_allMsgs",
        type: 'GET',
        success: function(data) {
          $('#MsgDiv').html(data);
          $('.msgBody').hide();

          ///////////////////////////// When click show messages button ///////////////////////////
          $('.btn-info').click(function() {
            btn_val = $(this).val();
            $('.' + btn_val + '').toggle('slow');

            $.ajax({
              url: "../controller/msg_notif_controller.php?status=set_MsgSeen",
              type: 'POST',
              data: {
                sender_id: btn_val
              }
            });

            $('.reply').click(function() {
              receiver = $(this).val();
              $('#reply_rec').val(receiver);

              $('#reply').modal('show');

            });

          });
        }
      });

    });

    ///////////////////////////// Delete all messages function ///////////////////////////
    function dell_All(url) {

      loc = url.getAttribute("href");

      swal({
          title: "Are you sure Do You Want To Delete All Messages Of This User ?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, Delete All",
          cancelButtonClass: "btn-info",
          closeOnConfirm: true
        },
        function(isConfirm) {
          if (isConfirm === true) {
            window.location.href = loc;
          }
        });

      return false;

    }

    ///////////////////////////// Delete single user messages function ///////////////////////////
    function dell_Single(url) {

      loc = url.getAttribute("href");

      swal({
          title: "Are You Sure You Want Delete This Message ?",
          text: "",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "Yes, Delete",
          cancelButtonClass: "btn-info",
          closeOnConfirm: true
        },
        function(isConfirm) {
          if (isConfirm === true) {
            window.location.href = loc;
          }
        });

      return false;

    }
  </script>
</body>

</html>