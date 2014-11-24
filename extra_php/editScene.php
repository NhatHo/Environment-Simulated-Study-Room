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
  <link rel="stylesheet" href="css/edit.css">
  <!-- Custom Fonts -->
  <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

  <title>Audio-Graphic Novel Classroom</title>
</head>
    
<body>

<?
  if (isset($_POST["edit"]))
  {
    if (!mysql_connect("mysql14.000webhost.com","a7036984_test","sysc3010"))
    {
      die("Could not connect: ".mysql_error());
    }

    mysql_select_db("a7036984_Scene");

    $query = mysql_query("UPDATE Scenes
                          SET name = '$_POST[title]',
                              description = '$_POST[desc]',
                              audio = '$_POST[audioURL]',
                              video = '$_POST[videoURL]'
                          WHERE id = '$_POST[edit]'");
    if (!$query)
    {
      die("Error: ".mysql_error());
    }

    if ($_FILES["imageEdit"]["size"] > 0)
    {
      if ($_FILES["imageEdit"]["error"] > 0)
      {
        die("Error: Image upload went wrong!\n");
      }

      $filePath="pictures/".strtolower($_POST["title"])."_".time().".jpg";
      if (!move_uploaded_file($_FILES["imageEdit"]["tmp_name"], $filePath))
      {
        die("Error: Image upload failed.\n");
      }

      $query = mysql_query("UPDATE Scenes
                            SET image = '$filePath'
                            WHERE id = '$_POST[edit]'");
      if (!$query)
      {
        die("Error: ".mysql_error());
      }
    }
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
          <li class="active">
            <a href="editScene.php"><i class="fa fa-fw fa-edit"></i> Edit Scenes</a>
          </li>
          <li>
            <a href="deleteScene.php"><i class="fa fa-fw fa-times"></i> Delete Scenes</a>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse -->
    </nav>
    
    <div id="page-wrapper">
      <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
          <div class="col-lg-12">
            <h1 class="page-header">
              Edit Scenes
            </h1>
            <ol class="breadcrumb">
              <li>
                <i class="fa fa-dashboard"></i>  <a href="admin.php">View Scenes</a>
              </li>
              <li class="active">
                <i class="fa fa-edit"></i> Edit Scenes
              </li>
            </ol>
          </div>
        </div>
        <!-- /.row -->

<?
        if (isset($_POST["submit"]))
        {
          if (!mysql_connect("mysql14.000webhost.com","a7036984_test","sysc3010"))
          {
            die("Could not connect: ".mysql_error());
          }

          mysql_select_db("a7036984_Scene");

          if (!empty($_POST["radio"]))
          {
            $query = mysql_query("SELECT *
                                  FROM Scenes
                                  WHERE id=".$_POST["radio"]."");
            if (!$query)
            {
              die("Error: ".mysql_error());
            }

            $row = mysql_fetch_assoc($query);
?>
            <div class="row">
              <div class="col-lg-6">

                <form role="form" id="editSceneForm" action="editScene.php" method="post" name="edit" enctype="multipart/form-data">

                  <div class="form-group">
                    <label>Scene Title</label>
                    <input class="form-control" name="title" placeholder="Example: Fuji Mountain" value="<?echo htmlspecialchars($row["name"]);?>" required/>
                  </div><br>

                  <div class="form-group">
                    <div class="panel panel-default col-sm-12 panelSize">
                      <div class="panel-heading paneltitle">
                        <h3 class="panel-title">Current Image</h3>
                      </div>
                      <div class="panel-body panelbody">
                        <img id="sceneImage" src="<?echo htmlspecialchars($row["image"]);?>" class="img-responsive">
                        <label>Change Image</label>
                        <input id="imageInput" type="file" name="imageEdit"/>
                      </div>
                    </div>
                  </div><br>

                  <div class="form-group">
                    <label>Scene Description</label>
                    <textarea class="form-control" rows="3" name="desc" placeholder="Example: which subject should this scene be suitable" required><?echo htmlspecialchars($row["description"]);?></textarea>
                  </div><br>

                  <div class="form-group">
                    <label>Audio URL:</label>
                    <input class="form-control" name="audioURL" placeholder="Link to the audio file" value="<?echo htmlspecialchars($row["audio"]);?>" required/>
                  </div>

                  <div class="form-group">
                    <p class="help-block">Either uploading or inputting audio URL above</p>
                    <label>Upload an audio file</label>
                    <input type="file"/>
                  </div><br>

                  <div class="form-group">
                    <label>Video URL:</label>
                    <input class="form-control" name="videoURL" placeholder="Link to the video file" value="<?echo htmlspecialchars($row["video"]);?>" required/>
                  </div><br>

                  <button type="submit" class="btn btn-primary" name="edit" value="<?echo htmlspecialchars($row["id"]);?>">Submit Changes</button>
                  <button type="submit" class="btn btn-cancel" name="cancel" value="Cancel">Cancel</button><br><br>

                </form>

              </div>
            </div>
<?
          }
          mysql_close();
        }
        else
        {
?>
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
            echo '  <form role="form" id="editSelectionForm" action="editScene.php" method="post" name="submit" enctype="multipart/form-data">';
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
                              <div class="radio">
                                <label>
                                  <input type="radio" name="radio" id="radio'.$i.'" value="'.$row["id"].'" required/>'.htmlspecialchars($row["name"]).' Scene
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
                        <button type="submit" name="submit" value="Submit" class="btn btn-lg btn-success">Edit Selected Scene</button><br><br>
                      </div>
                    </form>';
          }
        }
?>

      </div>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.0/jquery.validate.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/edit.js"></script>
</body>
</html>