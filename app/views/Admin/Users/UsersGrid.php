<div class="container">
  <div class="card">
    <div class="card-header">
      <h2>Users</h2>
    </div>
    <div class="card-body">
      <a href="CreateUser" class= "btn btn-primary">Create New User</a>
      <br>
      <br>
      <?php PrintUsersGrid($data->users)?>
    </div>
    </div>
    </div>
  </div>
</div>
</body>
</html>

<?php

function PrintusersGrid($userData)
{
    if($userData != null)
    {
        foreach ($user as $userData)
        {
            echo " " . $user['firstName'];
        }
    }
    else
    {

    }
}
        
        
?>