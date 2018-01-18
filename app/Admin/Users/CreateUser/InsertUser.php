<?php
InsertUser($username, $password, 0, $link);
//InsertUser($username, $password, 0, $link);

function InsertUser($userName, $password, $IsAdmin, $link){

  $username = $_POST['username'];
  $password = $_POST['password'];
  $password_hashed = md5($password);
  $qry = "INSERT INTO users (UserName, Password, IsAdmin) values('". $username. "','". $password_hashed  ."', 1)";

  if(mysqli_query($link, $qry)){
    PrintInsertSuccess($username);
  }else{
    PrintInsertFailure($username);
  }
}

function PrintInsertSuccess($userName){
  echo '<div class="alert alert-success" role="alert">';
  echo '<h4 cass="alert-heading">Success!</h4>';
  echo '<hr>';
  echo '<p>Added new user '. $userName .'</p>';
  echo "</div>";
}

function PrintInsertFailure($userName){
  echo '<div class="alert alert-danger" role="alert">';
  echo '<h4 cass="alert-heading">Failure!</h4>';
  echo '<hr>';
  echo '<p>Failed to add new user: '. $userName .'</p>';
  echo "</div>";
}
?>
</div>
</body>
</html>
