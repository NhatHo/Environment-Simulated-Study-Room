<?php
require_once "config.php";
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (mysqli_connect_errno()) {
    printf ("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

class Boolean {
  public $loggedIn;
}
$result = new Boolean();
$result->loggedIn = false;
//function login_check($connection) {
  session_start();
  if (isset($_SESSION["loggedIn"]))
  {
    $result->loggedIn = true;
  }
  echo json_encode($result);
  //echo "<h1>STEP 1</h1>";
  /*if (isset($_SESSION['username'], 
            $_SESSION['login_string'])) {
    //echo "<h1>STEP 2</h1>";
    $login_string = $_SESSION['login_string'];
    $username = $_SESSION['username'];

    $user_browser = $_SERVER['HTTP_USER_AGENT'];

    if ($stmt = $connection->prepare("SELECT password 
                                  FROM members 
                                  WHERE username = ? LIMIT 1")) { 
      $stmt->bind_param('i', $username);
      $stmt->execute();
      $stmt->store_result();
      echo "<h1>STEP 3</h1>";
      if ($stmt->num_rows == 1) {
        $stmt->bind_result($password);
        $stmt->fetch();
        $login_check = hash('sha512', $password . $user_browser);
        echo "<h1>STEP 4</h1>";
        if ($login_check == $login_string) {
          echo "<h1>STEP 5</h1>";
          $result->loggedIn = "true";
          //return true;
        }
        else { 
          //return false;
        }
      }
      else {
        //return false;
      }
    }
    else {
      //return false;
    }
  }
  else {
    //return false;
  }
  echo json_encode($result)
//}*/
?>