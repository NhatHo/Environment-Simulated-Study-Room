<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Auto-Graphic Novel Classroom is a project for Discovery Center at Carleton University, developed by ">
  <meta name="author" content="">
  
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/admin.css">
  <link rel="stylesheet" href="css/delete.css">
  <!-- Custom Fonts -->
  <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

  <title>Audio-Graphic Novel Classroom</title>
</head>
  
<body>

<?
  if (isset($_POST["submit"]))
  {
    if (!mysql_connect("mysql14.000webhost.com","a7036984_test","sysc3010"))
    {
      die("Could not connect: ".mysql_error());
    }

    mysql_select_db("a7036984_Scene");

    if (!empty($_POST["checkbox"]))
    {
      foreach ($_POST["checkbox"] as $deleteId)
      {
        if (!mysql_query("DELETE FROM Scenes
                          WHERE id=".$deleteId.""))
        {
          die("Error: ".mysql_error());
        }
      }
    }

    mysql_close();
  }
?>

  <div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="admin.php">Audio-Graphic Novel Classroom Admin</a>
      </div>
      <ul class="nav navbar-right top-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Admin <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li>
              <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
            </li>                   
            <li class="divider"></li>
            <li>
              <a href="index.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
            </li>
          </ul>
        </li>
      </ul>
      <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
          <li>
            <a href="admin.php"><i class="fa fa-fw fa-dashboard"></i> View Scenes</a>
          </li>               
          <li>
            <a href="addScene.php"><i class="fa fa-fw fa-plus-square"></i> Add Scenes</a>
          </li>
          <li>
            <a href="editScene.php"><i class="fa fa-fw fa-edit"></i> Edit Scenes</a>
          </li>
          <li  class="active">
            <a href="deleteScene.php"><i class="fa fa-fw fa-times"></i> Delete Scenes</a>
          </li>
        </ul>
      </div> 
    </nav> <!-- /.navbar-collapse -->

    <div id="page-wrapper"> 
      <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">
              Delete Scenes
            </h1>
            <ol class="breadcrumb">
              <li>
                <i class="fa fa-dashboard"></i>  <a href="admin.php">View Scenes</a>
              </li>
              <li class="active">
                <i class="fa fa-fw fa-times"></i> Delete Scenes
              </li>
            </ol>
          </div>
        </div>
        <!-- /.row -->

        <div class="page-header">
          <h1>Available Scenes</h1>
        </div>

<?
        if(!mysql_connect("mysql14.000webhost.com","a7036984_test","sysc3010"))
        {
          die();
        }

        mysql_select_db("a7036984_Scene");
        $query = mysql_query("SELECT * FROM Scenes");
        if (!$query)
        {
          die('Error: '.mysql_error());
        }

        $numRows = mysql_num_rows($query);
        if ($numRows==0)
        {
          echo '  There are currently no scenes uploaded to the database.\n
                  <div class="row">
                    <button type="button" class="btn btn-lg">No Uploaded Scenes</button><br><br>
                  </div>';
        }
        else
        {
          echo '  <form role="form" id="deleteSceneForm" action="deleteScene.php" method="post" name="submit" enctype="multipart/form-data">';
          $i = 1;
          while ($row = mysql_fetch_assoc($query))
          {
            if ($i%3==1)
            {
              echo '<div class="row">'; 
            }

            echo '    <div class="panel panel-default col-sm-4 panelSize">
                        <div class="panel-heading paneltitle">
                          <h3 class="panel-title">'.htmlspecialchars($row["name"]).' Scene</h3>
                        </div>
                        <div class="panel-body panelbody">
                          <img src="'.htmlspecialchars($row["image"]).'" class="img-responsive">
                          <div class="form-group">
                            <div class="checkbox">
                              <label>
                                <input type="checkbox" name="checkbox[]" id="checkbox'.$i.'" value="'.$row["id"].'" required/>'.htmlspecialchars($row["name"]).' Scene
                              </label>
                            </div>
                          </div>
                        </div>
                      </div>';

            if ($i%3==0 || $i==$numRows)
            {
              echo '</div>';
            }
            $i++;
          }
          echo '    <div class="row">
                      <button type="submit" name="submit" value="Submit" class="btn btn-lg btn-danger">Delete Selected Scene(s)</button><br><br>
                    </div>
                  </form>';
        }

        mysql_close();
?>
      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/delete.js"></script>
</body>
</html>