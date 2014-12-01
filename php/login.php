<?php
function login($username, $password, $connection) { 
  if ($stmt = $connection->prepare("SELECT password, salt 
    FROM users
    WHERE username = ?
    LIMIT 1")) {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    $stmt->bind_result($db_password, $salt);
    $stmt->fetch();

    $password = hash('sha512', $password . $salt);
    if ($stmt->num_rows == 1) {
      if ($db_password == $password) {
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                    "", 
                                                    $username);
        session_start();
        //$_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['login_string'] = hash('sha512', 
                  $password . $user_browser);
        return true;
      }
      else {
        return false;
      }
    } 
    else {
      return false;
    }
  }
}
?>