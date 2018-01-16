<!DOCTYPE html>
<html lang ="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
  <link rel="stylesheet" href="">
 <Title>Dragon Control 1.0</title>
</head>
<body>
<div class="dc-push"></div>
<div class="container col-sm-3">
  <div class="card">
    <div class="card-header">
      <h2 class="text-center">Dragon Control Login</h2>
    </div>
    <div class="card-body ">
      <div class="center" >
<?php
    if($data->loginResult == false){
        echo  '<div id="failureMessage" class="alert alert-danger">Please enter a valid Username and Password</div>';
    }
?>
      <form role="form" method="POST" action= <?php echo '"' . Base_URL . 'Login/Process"' ?> >
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="UserNameInput"><b>Username</b></label>
            <input id="UserNameInput" class="form-control col-sm-8" type="text" placeholder="Enter Username..." name="username" required>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" for="PasswordInput"><b>Password</b></label>
            <input id= "PasswordInput" class="form-control col-sm-8" type="password" placeholder="Enter Password..." name="password" required><br>
        </div>
        <div  class ="text-right">
            <button type="submit" class="btn btn-primary col-sm-4" id="Login">Login</button>    
        </div>
      </form>
    </div>
    </div>
  </div>
</div>
</body>
</html>