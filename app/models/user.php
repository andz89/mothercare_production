<?php
class user
{
  private $db;


  public function __construct()
  {
    $this->db = new Database;
  }



  // Find user by email
  public function findUserByEmail($email)
  {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    // Check row
    if ($this->db->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }
  // UPDATE Find user by email
  public function findUserByEmail_updateAccount($email, $id)
  {
    $this->db->query('SELECT * FROM users WHERE email = :email AND id != :id');
    $this->db->bind(':email', $email);
    $this->db->bind(':id', $id);


    $row = $this->db->single();

    // Check row
    if ($this->db->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  // Regsiter user
  public function register($data)
  {
    $this->db->query('INSERT INTO users (name, email, password,role,contact_number) VALUES(:name, :email, :password, :role, :contact_number)');
    // Bind values
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', $data['password']);
    $this->db->bind(':role', $data['role']);
    $this->db->bind(':contact_number', $data['contact_number']);



    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  // Regsiter user
  public function add_booking($data)
  {
    $this->db->query('INSERT INTO booking (booking_id, user_id, user_name,user_email,contact_number,doctor_name,date,time,note,reminders, doctor_id) VALUES(:booking_id, :user_id, :user_name, :user_email, :contact_number,:doctor_name,:date, :time, :note, :reminders,:doctor_id)');

    // Bind values
    $this->db->bind(':booking_id', $data['booking_id']);
    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':user_name', $data['user_name']);
    $this->db->bind(':user_email', $data['user_email']);
    $this->db->bind(':contact_number', $data['contact_number']);
    $this->db->bind(':note', $data['note']);
    $this->db->bind(':doctor_name', $data['doctor_name']);
    $this->db->bind(':date', $data['date']);
    $this->db->bind(':time', $data['time']);
    $this->db->bind(':reminders', $data['reminders']);
    $this->db->bind(':doctor_id', $data['doctor_id']);





    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  //login user 
  public function login($email, $password)
  {
    $this->db->query('SELECT * FROM users WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    $hashed_password = $row->password;
    if (password_verify($password, $hashed_password)) {
      return $row;
    } else {
      return false;
    }
  }
  //login user 
  public function login_admin($email, $password)
  {
    $this->db->query('SELECT * FROM admin WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    $hashed_password = $row->password;
    if ($password == $hashed_password) {
      return $row;
    } else {
      return false;
    }
  }
  // Find user by email
  public function findUserByEmail_admin($email)
  {
    $this->db->query('SELECT * FROM admin WHERE email = :email');
    $this->db->bind(':email', $email);

    $row = $this->db->single();

    // Check row
    if ($this->db->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function getAllBookings_as_user($id)
  {
    $this->db->query('SELECT * FROM booking WHERE user_id = :user_id');
    $this->db->bind(':user_id', $id);

    $results = $this->db->resultSet();

    return $results;
  }


  //count bookings
  public function getAllBookings_count()
  {
    $this->db->query('SELECT * FROM booking');
    $this->db->resultSet();
    return $this->db->rowCount();
  }

  //count bookings
  public function getAllUsers_count()
  {
    $this->db->query('SELECT * FROM users');
    $this->db->resultSet();
    return $this->db->rowCount();
  }


  public function getAllBookings_as_admin()
  {
    $this->db->query('SELECT * FROM booking');
    $results = $this->db->resultSet();

    return $results;
  }

  public function getcontact()
  {

    $this->db->query('SELECT * FROM contact');

    $row = $this->db->single();
    return $row;
  }


  public function update_contact($data)
  {

    $this->db->query('UPDATE contact SET telephone = :telephone, email = :email, address = :address WHERE id = :id');
    // Bind values
    $this->db->bind(':id', $data['id']);
    $this->db->bind(':telephone', $data['telephone']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':address', $data['address']);

    // $this->db->bind(':image_thumbnail', $data['image_thumbnail']);



    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  public function updateAccount($data, $id)
  {

    $this->db->query('UPDATE users SET contact_number = :contact_number, email = :email, name = :name WHERE id = :id');
    // Bind values
    $this->db->bind(':id', $id);
    $this->db->bind(':contact_number', $data['contact_number']);
    $this->db->bind(':email', $data['email']);
    $this->db->bind(':name', $data['name']);



    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function delete_doctor($id)
  {

    $this->db->query('DELETE FROM doctors WHERE id = :id');
    // Bind values
    $this->db->bind(':id', $id);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  // add schedule
  public function schedule($data)
  {
    $this->db->query('INSERT INTO schedule (date, time,doctor_id,patient_limit, reminders) VALUES(:date, :time, :doctor_id, :patient_limit, :reminders)');
    // Bind values
    $this->db->bind(':date', $data['date']);
    $this->db->bind(':time', $data['time']);
    $this->db->bind(':patient_limit', $data['patient_limit']);

    $this->db->bind(':doctor_id', $data['doctor_id']);
    $this->db->bind(':reminders', $data['reminders']);



    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  //get Sched
  public function getSched($doctor_id)
  {

    $this->db->query('SELECT * FROM schedule WHERE patient_limit > count AND doctor_id = :doctor_id');
    $this->db->bind(':doctor_id', $doctor_id);

    $results = $this->db->resultSet();

    return $results;
  }

  public function getAllSched()
  {

    $this->db->query('SELECT * FROM schedule');


    $results = $this->db->resultSet();

    return $results;
  }
  public function getSched_for_user($date)
  {

    $this->db->query('SELECT * FROM schedule WHERE date = :date');
    $this->db->bind(':date', $date);

    $results = $this->db->resultSet();

    return $results;
  }

  public function edit_schedule($data)
  {

    $this->db->query('UPDATE schedule SET time = :time, reminders = :reminders  WHERE id = :id');

    // Bind values
    $this->db->bind(':id', $data['sched-id']);
    $this->db->bind(':time', $data['time']);
    $this->db->bind(':reminders', $data['reminders']);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  public function announcement($data)
  {

    $this->db->query('UPDATE schedule SET  announcement = :announcement  WHERE id = :id');

    // Bind values
    $this->db->bind(':id', $data['sched-id']);
    $this->db->bind(':announcement', $data['announcement']);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
  public function patients($id, $date)
  {
    $this->db->query('SELECT * FROM booking WHERE doctor_id = :doctor_id AND date = :date');
    $this->db->bind(':doctor_id', $id);
    $this->db->bind(':date', $date);


    $results = $this->db->resultSet();

    return $results;
  }

  public function getCountPatients($id, $date)
  {

    $this->db->query('SELECT * FROM booking WHERE doctor_id = :doctor_id AND date= :date');
    $this->db->bind(':doctor_id', $id);
    $this->db->bind(':date', $date);

    $this->db->resultSet();

    return $this->db->rowCount();
  }
  public function getCountBookings()
  {

    $this->db->query('SELECT * FROM booking');


    $this->db->resultSet();

    return $this->db->rowCount();
  }
  public function getProfile($id)
  {
    $this->db->query('SELECT * FROM users WHERE id = :id');
    $this->db->bind(':id', $id);

    $result = $this->db->single();

    return $result;
  }


  public function count($id, $date, $count)
  {

    $this->db->query('UPDATE schedule  SET count = :count WHERE doctor_id = :doctor_id AND date= :date');
    $this->db->bind(':doctor_id', $id);
    $this->db->bind(':date', $date);
    $this->db->bind(':count', $count);



    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }



  public function checkDoubleBooking($id, $date)
  {
    $this->db->query('SELECT * FROM booking WHERE user_id= :user_id AND date = :date');
    $this->db->bind(':user_id', $id);
    $this->db->bind(':date', $date);

    $row = $this->db->single();

    // Check row
    if ($this->db->rowCount() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function getUserBookingForPayment($booking_id)
  {

    $this->db->query('SELECT * FROM booking WHERE booking_id = :booking_id');
    $this->db->bind(':booking_id', $booking_id);

    $results = $this->db->single();
    // print_r($results);
    return $results;
  }
  public function savePayment($data)
  {



    $this->db->query('UPDATE booking SET payment = :payment, payment_date = :payment_date WHERE booking_id = :booking_id');
    // Bind values
    $this->db->bind(':booking_id', $data['booking_id']);
    $this->db->bind(':payment', $data['payment']);
    $this->db->bind(':payment_date', $data['payment_date']);

    // Execute
    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }
}
