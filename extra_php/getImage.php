<?php
  $id = $_GET['id'];

  mysql_connect("mysql14.000webhost.com","a7036984_test","sysc3010");
  mysql_select_db("a7036984_Scene");
  $query = mysql_query("SELECT * FROM Scenes WHERE id=$id");
  $row = mysql_fetch_assoc($query);
  mysql_close();

  header("Content-type: image/jpeg");
  echo $row['image'];
?>