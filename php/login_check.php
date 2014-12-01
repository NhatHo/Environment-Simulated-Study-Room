<?php
require_once "config.php";
$connection = mysqli_connect (DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if (mysqli_connect_errno()) {
    printf ("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$result = "false";
session_start();
if (isset($_SESSION['username'], 
          $_SESSION['login_string'])) {
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
      if ($login_check == $login_string) {
        $result = "true";
      }
    }
  }
}
echo json_encode(array("result"=>$result));
?>