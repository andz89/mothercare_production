<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar_admin.php'; ?>
<style>
  a .sched:hover {
    text-decoration: underline;
  }

  @media only screen and (max-width: 1100px) {
    .content-image-info {
      flex-direction: column;
      justify-content: center;
      align-items: center;
      ;

    }

    .content-img {
      width: 100%;
      height: auto;
      display: flex;
      justify-content: center;
      align-items: center;

    }

    .img {
      width: 200px;
    }

    .info-button {

      flex-direction: column;
      gap: 10px;
    }
  }
</style>
<div class="container col-md-10 mx-auto my-0 py-0">
  <div style="height: 30px;">
    <div class="alert-flash text-center"> <?php flash('update'); ?></div>
  </div>

  <div class="jumbutron my-0 py-0">
    <section class="text-center container my-0 py-0">
      <div class="row py-lg-2 my-0 py-0">
        <div class="col-md-12 mx-auto my-0 py-0">
          <h1 class="fw-light">MotherCare Doctors</h1>
          <span>Total No. of added doctors: <b><?php echo $data['added-doctors'] ?> </b> </span> <br>
          <a href="<?php echo URLROOT; ?>/admin/add_doctor"><span class="text-white btn btn-sm px-4  mt-2 fs-6" style="background-color:#EE6983;">Add Doctor</span></a>

        </div>
      </div>
    </section>
  </div>

  <?php foreach ($data['doctors'] as  $doctors) : ?>

    <div class=" align-items-start ">
      <div class="list-group-item mt-4 py-3">
        <div class="col-xs-12 d-flex justify-content-start gap-3 content-image-info">
          <div class="content-img" style="width:20%">
            <img class="img" src="<?php echo  URLROOT . '/' . 'images/' . $doctors->image_path ?>" width="100%" alt="">
          </div>

          <div class="" style="width:100%">
            <div class="">
              <div class="d-flex justify-content-between info-button">
                <div>
                  <h6 class="text-body fs-4 m-0"><?php echo $doctors->doctor_name ?></h6>
                  <span class="text-secondary">Email: <b><?php echo $doctors->email ?> </b></span><br>
                  <span class="text-secondary">Contact Number: <b> <?php echo $doctors->contact_number ?></b></span>

                </div>

                <div>

                  <a href="<?php echo URLROOT; ?>/admin/schedule?id=<?php echo $doctors->id ?>" class="m-2 text-decoration-none ">
                    <span class="sched">
                      Time and Schedule
                    </span>

                  </a>

                  <span class="btn btn-primary btn-sm m-0">
                    <a href="<?php echo URLROOT; ?>/admin/edit_doctor?id=<?php echo $doctors->id ?>" class="text-white text-decoration-none">
                      Edit Profile
                    </a>
                  </span>

                  <span class="btn btn-secondary btn-sm m-0" data-bs-toggle="collapse" href="#a<?php echo $doctors->id ?>" role="button" aria-expanded="false" aria-controls="a<?php echo $doctors->id ?>">
                    View More
                  </span>

                </div>
              </div>
              <p class="lead fs-5 pt-2">
                <?php echo $doctors->description_1 ?>
              </p>

            </div>

          </div>
          <!-- <small class="mt-2">12/21/22 </small> -->
        </div>
        <!-- hide area -->
        <div class="collapse mt-3" id="a<?php echo $doctors->id ?>">
          <div class="">
            <p class="lead" style="text-indent:40px ;">
              <?php echo $doctors->description_2 ?>
            </p>


          </div>
          <div class="d-flex justify-content-end">
            <form action="<?php echo URLROOT; ?>/admin/delete?id=<?php echo $doctors->id ?> " method="post">
              <input value="Remove Doctor" type='submit' class=" btn  btn-danger btn-sm m-0 text-white text-decoration-none float-right">
            </form>
          </div>



        </div>
      </div>





    </div>

  <?php endforeach; ?>
</div>
<script>
  <?php echo alert_flash(); ?>
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>