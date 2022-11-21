<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar_admin.php'; ?>
<div class="px-4 py-5 text-center" style="min-height: 700px;">
  <img class="mx-auto mb-2" src="<?php echo URLROOT; ?>/images/mothercare_1.png" width="50%" alt="">
  <h1 class="display-4 " style="color:#EE6983">MotherCare Dashboard</h1>
  <div class="col-lg-6 mx-auto">
    <span class="lead mb-4">Added Doctors: <b><?php echo $data['doctors_count'] ?></b> </span><br>
    <span class="lead mb-4">Current Bookings: <b><?php echo $data['bookings_count'] ?></b></span><br>
    <span class="lead mb-4">Users: <b><?php echo $data['users_count'] ?></b></span>


  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>