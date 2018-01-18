<?php
/* Create User view.
 */
?>

This is the create user page.
<div class="container" >
  <h2 id="Title">Create New User</h2>
  <form id="form" action = "http://localhost/Dragon-Control/public/Admin/InsertUser" method="POST">
      <label for="FirstName"><b>First Name</b></label>
      <input name="FirstName" class="form-control" type="text" placeholder="Enter First Name..." required><br>
      <label for="LastName"><b>Last Name</b></label>
      <input name="LastName" class="form-control" type="text" placeholder="Enter Last Name..." required><br>
      <label for=""><b>Password</b></label>
      <input name= "Password" class="form-control" type="password" placeholder="Enter Password..." required><br>
      <label for="Email"><b>E-mail Address</b></label>
      <input name= "Email" class="form-control"  type="text" placeholder="Enter E-mail address..."><br>
      <input type="submit" value="Submit">
  </form>
  <br>
  <div id="alerts" class="">
</div>
</div>
</body>
</html>