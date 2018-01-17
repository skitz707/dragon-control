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