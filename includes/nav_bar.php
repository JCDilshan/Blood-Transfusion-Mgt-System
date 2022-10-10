<?php

///////////////////////// Get session variables /////////////////////////////
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_role =  isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
$user_img  = isset($_SESSION['user_img']) ? $_SESSION['user_img'] : '';

include_once('../model/role_model.php');
$role_object = new Role($conn);

///////////////////////// Get roles /////////////////////////////
$result_role = $role_object->get_specificRole($user_role);
$row_role = $result_role->fetch_assoc();
?>

<!-- ///////////////////////// Navbar Start ///////////////////////////// -->

<nav id="sidebar" class="bg-dark" style="overflow-y: auto; max-height: 100vh;">

    <div class="p-4 d-flex flex-column h-100">

        <h4 class="font-weight-bold text-left text-white">
            <i class="fa-solid fa-hospital mr-2"></i> <i>BTM System</i>
        </h4>

        <!-- ///////////////////////// User image ///////////////////////////// -->
        <img src="../images/users/<?php echo $user_img; ?>" class="user_img shadow-lg">

        <!-- ///////////////////////// User name ///////////////////////////// -->
        <h4 class="text-white text-center"><?php echo $user_name; ?></h4>

        <!-- ///////////////////////// User role ///////////////////////////// -->
        <p class="text-center">(<?php echo $row_role['role_name'] ?>)</p>

        <ul class="list-unstyled components mb-5">


            <?php if ($user_role == 1 || $user_role == 100) { ?>
                <!-- ///////////////////////// Dashboard ///////////////////////////// -->
                <li class="active">
                    <a href="../view/dashboard.php"> Dashboard</a>
                </li>
            <?php } ?>
            <!-- ///////////////////////// Home ///////////////////////////// -->
            <li>
                <a href="../view/home.php"><span class="fa fa-home mr-2"></span> Home</a>
            </li>


            <?php if ($user_role == 1 || $user_role == 100) { ?>
                <!-- ///////////////////////// User module ///////////////////////////// -->
                <li>
                    <a href="../view/add_user.php"><span class="fa fa-user-plus mr-2"></span> Add User</a>
                </li>
                <li>
                    <a href="../view/mng_user.php"><span class="fa fa-user-edit mr-2"></span> Mng Users</a>
                </li>

                <!-- ///////////////////////// Staff module ///////////////////////////// -->
                <li>
                    <a href="#staffSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-users mr-2"></span> Staff </a>
                    <ul class="collapse" id="staffSubmenu" type="circle">
                        <li>
                            <a href="../view/doctors.php"><i class="fa fa-user-tie mr-1"></i> Doctors</a>
                        </li>
                        <li>
                            <a href="../view/nurse.php"><i class="fa fa-user-nurse mr-1"></i> Nurse</a>
                        </li>
                        <li>
                            <a href="../view/other_staff.php"><i class="fa fa-users mr-1"></i> Helping members</a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($user_role == 1 || $user_role == 100 || $user_role == 6) { ?>
                <!-- ///////////////////////// Schedule module ///////////////////////////// -->
                <li>
                    <a href="#mngscheduleSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-clock mr-2"></span> Schedules</a>
                    <ul class="collapse" id="mngscheduleSubmenu" type="circle">
                        <li>
                            <a href="../view/mng_hospitals.php"><i class="fa fa-hospital mr-1"></i> Mng Hospitals</a>
                        </li>
                        <li>
                            <a href="../view/mng_schedule.php"><i class="fa fa-calendar-day mr-1"></i> Mng Schedules</a>
                        </li>
                    </ul>
                </li>

                <!-- ///////////////////////// Donation module ///////////////////////////// -->
                <li>
                    <a href="#donationSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fa fa-hand-holding-medical mr-2"></i> Donoation</a>
                    <ul class="collapse" id="donationSubmenu" type="circle">
                        <li>
                            <a href="../view/add_donation.php"><span class="fa fa-hand-holding-medical mr-2"></span> Add New Donation</a>
                        </li>
                        <li>
                            <a href="../view/donation_history.php"><i class="fa fa-clock-rotate-left mr-2"></i> Donation History</a>
                        </li>
                    </ul>
                </li>

                <!-- ///////////////////////// Camp module ///////////////////////////// -->
                <li>
                    <a href="#bloodcampSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-tents mr-2"></span> Camp </a>
                    <ul class="collapse" id="bloodcampSubmenu" type="circle">
                        <li>
                            <a href="../view/add_blood_camp.php"><i class="fa fa-house-medical mr-2"></i> Schedule New Camp</a>
                        </li>
                        <li>
                            <a href="../view/mng_camps.php"><i class="fa fa-house-medical-circle-exclamation mr-2"></i> Mng Camps</a>
                        </li>
                    </ul>
                </li>

                <!-- ///////////////////////// Blood module ///////////////////////////// -->
                <li>
                    <a href="#bloodgroupSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-droplet mr-2"></span> Blood</a>
                    <ul class="collapse" id="bloodgroupSubmenu" type="circle">
                        <li>
                            <a href="../view/blood_grp.php"><span class="fa fa-fill-drip mr-2"></span> Blood Group</a>
                        </li>
                        <li>
                            <a href="../view/blood_components.php"><span class="fa fa-bottle-droplet mr-2"></span> Blood Component</a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($user_role == 1 || $user_role == 100 || $user_role == 2) { ?>
                <!-- ///////////////////////// Donor module ///////////////////////////// -->
                <li>
                    <a href="#donorSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fa fa-circle-user mr-2"></i> Donor</a>
                    <ul class="collapse" id="donorSubmenu" type="circle">
                        <li>
                            <a href="../view/donor_reg.php"><span class="fa fa-user-plus mr-2"></span> Add New Donor</a>
                        </li>
                        <li>
                            <a href="../view/mng_donors.php"><span class="fa fa-user-pen mr-2"></span> Mng Donors</a>
                        </li>
                        <li>
                            <a href="../view/make_appointment.php"><span class="fa fa-calendar-check mr-2"></span> Make Appointment</a>
                        </li>
                        <li>
                            <a href="../view/mng_appointments.php"><span class="fa fa-calendar-days mr-2"></span> Mng Appointments</a>
                        </li>
                    </ul>
                </li>

                <!-- ///////////////////////// Patient module ///////////////////////////// -->
                <li>
                    <a href="#patientSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-bed mr-2"></span> Patient</a>
                    <ul class="collapse" id="patientSubmenu" type="circle">
                        <li>
                            <a href="../view/blood_request.php"><span class="fa fa-file-contract mr-2"></span> Blood Request Form</a>
                        </li>
                        <li>
                            <a href="../view/mng_patients.php"><span class="fa fa-bed-pulse mr-2"></span> Mng Patients</a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($user_role == 1 || $user_role == 100 || $user_role == 4) { ?>
                <!-- ///////////////////////// Blood request module ///////////////////////////// -->
                <li>
                    <a href="#bloodreqSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-file-import mr-2"></span> Blood Requests</a>
                    <ul class="collapse" id="bloodreqSubmenu" type="circle">
                        <li>
                            <a href="../view/pending_blood_request.php"><i class="fa fa-file-circle-question mr-2"></i> Pending Forms</a>
                        </li>
                        <li>
                            <a href="../view/Checked_Requests.php"><i class="fa fa-file-circle-check mr-2"></i> Checked Forms</a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($user_role == 1 || $user_role == 100 || $user_role == 5) { ?>
                <!-- ///////////////////////// Blood testing ///////////////////////////// -->
                <li>
                    <a href="#bloodtestSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-flask-vial mr-2"></span> Blood Tests</a>
                    <ul class="collapse" id="bloodtestSubmenu" type="circle">
                        <li>
                            <a href="../view/pending_blood_donations.php"><i class="fa fa-spinner mr-2"></i>Pending Doantions</a>
                        </li>
                        <li>
                            <a href="../view/proceed_blood_donations.php"><i class="fa fa-hourglass mr-2"></i>Proceeding Donations</a>
                        </li>
                        <li>
                            <a href="../view/checked_blood_donations.php"><i class="fa fa-heart-circle-check mr-2"></i>Checked Donations</a>
                        </li>
                        <li>
                            <a href="../view/bloodBag_inventoryPass.php"><i class="fa fa-share-from-square mr-2"></i>Inventory Pass</a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if ($user_role == 1 || $user_role == 100 || $user_role == 3) { ?>
                <!-- ///////////////////////// Inventory module ///////////////////////////// -->
                <li>
                    <a href="#bloodinvenSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <span class="fa fa-warehouse mr-2"></span> Blood Inventory</a>
                    <ul class="collapse" id="bloodinvenSubmenu" type="circle">
                        <li>
                            <a href="../view/TestPass_BloodForms.php"><span class="fa fa-file-import mr-2"></span> Received Donations</a>
                        </li>
                        <li>
                            <a href="../view/Approved_Requests.php"><i class="fa fa-file-circle-exclamation mr-2"></i> Requisition Forms</a>
                        </li>
                        <li>
                            <a href="../view/mng_inventory.php"><span class="fa fa-shop-lock mr-2"></span> Mng Inventory</a>
                        </li>
                        <li>
                            <a href="../view/issued_history.php"><span class="fa fa-clock-rotate-left mr-2"></span> Issued History</a>
                        </li>
                        <li>
                            <a href="../view/expired_history.php"><span class="fa fa-hourglass-end mr-2"></span> Expired History</a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

        </ul>

        <!-- ///////////////////////// Footer ///////////////////////////// -->
        <div class="footer mt-auto">
            <h6>Blood Transfusion Management System</h6>
            <p>
                Copyright &copy;<script>
                    document.write(new Date().getFullYear());
                </script> All rights reserved | Design By : <kbd>JCDilshan</kbd>
            </p>
        </div>

    </div>
</nav>

<!-- Page Content  -->
<div id="content" class="">

    <!-- ///////////////////////// Top menu bar ///////////////////////////// -->
    <nav class="navbar navbar-expand-lg navbar-light bg-dark p-0 pr-3" style="height:80px;">

        <div class="custom-menu bg-dark no-print ml-2">
            <!-- ///////////////////////// Navbar toggle button ///////////////////////////// -->
            <button type="button" id="sidebarCollapse" class="btn btn-dark">
                <i class="fa fa-2x fa-bars text-white"></i>
                <span class="sr-only">Toggle Menu</span>
            </button>
        </div>

        <!-- <div class="container-fluid"> -->
        <div class="collapse navbar-collapse h-100" id="navbarSupportedContent">
            <ul class="nav navbar-nav align-items-center ml-auto" style="font-size:16px; font-weight:bolder;">
                <!-- ///////////////////////// Contact list ///////////////////////////// -->
                <li class="nav-item">
                    <a class="btn btn-secondary" href="../view/contact.php">Contact List <i class="fa fa-phone-alt"></i></a>
                </li>
                &nbsp;
                &nbsp;
                <!-- ///////////////////////// Notifications ///////////////////////////// -->
                <li class="nav-item">
                    <a id="notifBtn" class="btn btn-warning shadow" style="color:#000;">Notifications <i id="notif_Bell" class="fa fa-bell">
                            <span class="badge" id="notifCount"></span></i>
                    </a>
                </li>

                <div id="notif_Bodydiv" class="notif_div" style="display:none;">

                </div>

                &nbsp;
                &nbsp;
                <!-- ///////////////////////// Messages ///////////////////////////// -->
                <li class="nav-item">
                    <a class="btn btn-primary shadow msg" href="../view/messages.php">Messages
                        <i class="fa fa-comments">
                            <span class="badge" id="msgCount"></span>
                        </i>
                    </a>
                </li>
                &nbsp;
                &nbsp;

                <!-- ///////////////////////// User settings ///////////////////////////// -->
                <li>
                    <div id="userCog" class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <i class="fa fa-user-cog fa-2x text-white"></i>
                        </button>
                        <ul id="SubNavDrp" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li><a href="../view/self_info.php">Edit Info</a></li>
                            <li><a href="../view/change_pw.php">Change Password</a></li>
                            <li role="separator" class="dropdown-divider"></li>
                            <li><a href="../controller/login_controller.php?status=logout" id="signout">SIGNOUT <i class="fa fa-sign-out-alt"></i></a></li>
                        </ul>
                    </div>

                </li>
            </ul>
        </div>
    </nav>
    <!-- ///////////////////////// Top menu bar end ///////////////////////////// -->

    <!-- Content continue..... -->