<?php
class Users extends Controller
{
  public function __construct()
  {
    $this->userModel = $this->model('user');
    $this->doctorModel = $this->model('doctor');
  }

  public function index()
  {
    $data =  ['title' => '',];
    $this->view('pages/index', $data);
  }

  public function register()
  {

    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // Process form
      // sanitize post data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      // init data
      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'confirm_password' => trim($_POST['confirm_password']),
        'role' => 'user',
        'contact_number' => trim($_POST['contact_number']),
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => ''
      ];
      if (isset($_SESSION['user_role'])) {
        if ($_SESSION['user_role'] == 'admin') {
          $data['name_err'] = 'You login as admin';
          $this->view('users/register', $data);
          return false;
        }
      }

      // validate email
      if (empty($data['email'])) {
        $data['email_err'] = 'Pleae enter email';
      } else {
        // Check email
        if ($this->userModel->findUserByEmail($data['email'])) {
          $data['email_err'] = 'Email is already taken';
        }
      }

      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Pleae enter name';
      }
      //validate number
      if (empty($data['contact_number'])) {
        $data['contact_number_err'] = 'Pleae enter contact number';
      }
      // Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'Pleae enter password';
      } elseif (strlen($data['password']) < 6) {
        $data['password_err'] = 'Password must be at least 6 characters';
      }
      // Validate Confirm Password
      if (empty($data['confirm_password'])) {
        $data['confirm_password_err'] = 'Pleae confirm password';
      } else {
        if ($data['password'] != $data['confirm_password']) {
          $data['confirm_password_err'] = 'Passwords do not match';
        }
      }


      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
        // Validated


        // Hash Password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        if ($this->userModel->register($data)) {
          flash('register_success', 'You are registered and can log in');

          redirect('/users/login');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('users/register', $data);
      }
    } else {

      // Init data
      $data = [
        'name' => '',
        'email' => '',
        'password' => '',
        'confirm_password' => '',
        'name_err' => '',
        'email_err' => '',
        'password_err' => '',
        'confirm_password_err' => '',
        'role' => '',
        'contact_number' => '',

      ];

      // Load view
      $this->view('users/register', $data);
    }
  }
  public function booking()
  {
    if (!isLoggedIn()) {
      redirect('index');
    }

    ID_isNull($_GET['id'], 'index'); # check  id

    $doctor_profile = $this->doctorModel->getDoctor($_GET['id']);
    $sched_dates = $this->userModel->getSched($_GET['id']);


    // print_r($doctor_profile->date);

    $array_sched = [];
    foreach ($sched_dates as $date) {

      array_push($array_sched, $date);
    }


    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      // init data

      $data = [
        'doctor_id' => $doctor_profile->id,
        'doctor_name' => $doctor_profile->doctor_name,
        'user_name' => $_SESSION['user_name'],
        'user_email' => $_SESSION['user_email'],
        'user_id' =>  $_SESSION['user_id'],
        'reminders' => $_POST['reminders'],
        'contact_number' => $_SESSION['user_contact_number'],
        'date' => trim($_POST['date']),
        'time' => trim($_POST['time']),
        'note' => trim($_POST['note']),
        'count' => trim($_POST['count']),
        'booking_id' =>  uniqid(),
        'array_sched' => $array_sched,
        'date_err' => '',
        'time_err' => '',
        'note_err' => '',
      ];


      // print_r($data['date']);

      // return false;

      //filter for double booking
      if ($this->userModel->checkDoubleBooking($data['user_id'], $data['date'])) {

        $data['date_err'] = 'You already booked this date';
      }


      // validate date
      if (empty($data['date'])) {
        $data['date_err'] = 'Pleae enter date';
      }

      // Validate time
      if (empty($data['time'])) {
        $data['time_err'] = 'Pleae enter time';
      }
      //validate number
      if (empty($data['note'])) {
        $data['note_err'] = 'Pleae enter note';
      }


      // Make sure errors are empty
      if (empty($data['name_err']) && empty($data['note_err']) &&  empty($data['time_err'])   && empty($data['date_err'])) {
        // Validated

        if ($this->userModel->count($data['doctor_id'], $data['date'], $data['count'] + 1)) {
        } else {
          die('Something went wrong');

          return false;
        }


        if ($this->userModel->add_booking($data)) {

          redirect('users/myBooking');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('users/booking', $data);
      }
    } else {





      // Init data
      $data = [

        'doctor_id' => $doctor_profile->id,
        'doctor_name' => $doctor_profile->doctor_name,
        'date' => '',
        'time' => '',
        'note' => '',
        'date_err' => '',
        'time_err' => '',
        'note_err' => '',
        'array_sched' => $array_sched,

      ];

      // Load view
      $this->view('users/booking', $data);
    }
  }
  public function login()
  {

    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


      // Process form
      // Sanitize POST data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

      // Init data
      $data = [
        'email' => trim($_POST['email']),
        'password' => trim($_POST['password']),
        'email_err' => '',
        'password_err' => '',
      ];
      if (isset($_SESSION['user_role'])) {
        if ($_SESSION['user_role'] == 'admin') {
          $data['email_err'] = 'You login as admin';
          $this->view('users/login', $data);
          return false;
        }
      }

      // Validate Email
      if (empty($data['email'])) {
        $data['email_err'] = 'Pleae enter email';
      }

      // Validate Password
      if (empty($data['password'])) {
        $data['password_err'] = 'Please enter password';
      }

      //check for user/email
      if ($this->userModel->findUserByEmail($data['email'])) {
        //user found

      } else {
        //No user found
        $data['email_err'] = 'No user found';
      }

      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['password_err'])) {
        // Validated
        // check and set logged in user
        $loggedInUser = $this->userModel->login($data['email'], $data['password']);

        if ($loggedInUser) {
          // Create Session
          $this->createUserSession($loggedInUser);
        } else {
          $data['password_err'] = 'Password incorrect';

          $this->view('users/login', $data);
        }
      } else {
        // Load view with errors
        $this->view('users/login', $data);
      }
    } else {
      // Init data
      $data = [
        'email' => '',
        'password' => '',
        'email_err' => '',
        'password_err' => '',

      ];

      // Load view
      $this->view('users/login', $data);
    }
  }


  public function createUserSession($user)
  {
    $_SESSION['user_id'] = $user->id;
    $_SESSION['user_email'] = $user->email;
    $_SESSION['user_name'] = $user->name;
    $_SESSION['user_role'] = $user->role;
    $_SESSION['user_created'] = $user->date;
    $_SESSION['user_contact_number'] = $user->contact_number;
    redirect('index');
  }

  public function logout()
  {
    if (($_SESSION['user_role'] == 'admin')) {

      unset($_SESSION['user_id']);
      unset($_SESSION['user_email ']);
      unset($_SESSION['user_name']);
      unset($_SESSION['user_role']);
      unset($_SESSION['user_contact_number']);

      session_destroy();
      redirect('admin');
    } else {




      unset($_SESSION['user_id']);
      unset($_SESSION['user_email ']);
      unset($_SESSION['user_name']);
      unset($_SESSION['user_role']);
      unset($_SESSION['user_contact_number']);

      session_destroy();

      redirect('users/login');
    }
  }
  public function myBooking()
  {

    if (!$_SESSION['user_id']) {
      redirect('index');
      return false;
    };
    $booking =  $this->userModel->getAllBookings_as_user($_SESSION['user_id']);

    $sched =  $this->userModel->getAllSched();

    $data =  [
      'booking' => $booking,
      'sched' => $sched
    ];

    $this->view('users/myBooking', $data);
  }
  public function account()
  {
    $profile = $this->userModel->getProfile($_SESSION['user_id']);

    // Init data
    $data = [
      'name' => $profile->name,
      'email' => $profile->email,
      'contact_number' => $profile->contact_number,
      'name_err' => '',
      'email_err' => '',
      'contact_number_err' => '',
    ];
    $this->view('users/account', $data);
  }
  public function update_account()
  {
    if (!$_SESSION['user_id']) {
      redirect('index');
      return false;
    };
    if ($_SESSION['user_role'] == 'admin') {
      // redirect('index');
      return false;
    };


    // Check for POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      // Process form
      // sanitize post data
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      // init data

      $data = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'contact_number' => trim($_POST['contact_number']),

        'name_err' => '',
        'email_err' => '',
        // 'password_err' => '',
        // 'confirm_password_err' => ''
      ];

      // validate email
      if (empty($data['email'])) {
        $data['email_err'] = 'Pleae enter email';
      } else {
        // Check email
        if ($this->userModel->findUserByEmail_updateAccount($data['email'], $_SESSION['user_id'])) {
          $data['email_err'] = 'Email is already taken';
        }
      }

      // Validate Name
      if (empty($data['name'])) {
        $data['name_err'] = 'Pleae enter name';
      }
      //validate number
      if (empty($data['contact_number'])) {
        $data['contact_number_err'] = 'Pleae enter contact number';
      }




      // Make sure errors are empty
      if (empty($data['email_err']) && empty($data['name_err'])) {
        // Validated



        if ($this->userModel->updateAccount($data, $_SESSION['user_id'])) {
          // flash('register_success', 'You are registered and can log in');

          redirect('users/account');
        } else {
          die('Something went wrong');
        }
      } else {
        // Load view with errors
        $this->view('users/update_account', $data);
      }
    } else {
      $profile = $this->userModel->getProfile($_SESSION['user_id']);

      // Init data
      $data = [
        'name' => $profile->name,
        'email' => $profile->email,
        'contact_number' => $profile->contact_number,
        'name_err' => '',
        'email_err' => '',
        'contact_number_err' => '',
      ];

      // Load view
      $this->view('users/update_account', $data);
    }
  }
}
