<?php

function PrintUsersGrid($link){
 echo '<table class="table" id="UsersGrid">';
 PrintGridHeaders($link);
 PrintTableRows($link);
 echo '</table>';
}

function PrintGridHeaders($link){
  echo '<th>User Name</th>';
  echo '<th>Admin Access</th>';
  echo '<th></th>';
}

function PrintTableRows($link){
  $qry = 'Select * from Users';
  $results = mysqli_query($link, $qry);

  if(mysqli_num_rows($results) > 0){
    while($row = mysqli_fetch_array($results)){

      if($row['DeletedDate'] != null && $row['DeletedByUserID']  != null){
       echo '<tr class="bg-deleted">';
      }else{
        echo '<tr>';
      }

      echo '<td>' . $row['UserName'] . '</td>';
      DrawAdmin($row['IsAdmin']);
      DrawDeleteButton($row['UserID'], $row['DeletedDate'], $row['DeletedByUserID']);
      echo '</tr>';
    }
  }
}

function DrawAdmin($isAdmin){
  if($isAdmin == 1){
      echo '<td>Yes</td>';
  }else{
    echo '<td>No</td>';
  }
}


function DrawDeleteButton($userID, $DeletedDate, $DeletedByUserID){
  if($DeletedDate != null && $DeletedByUserID != null){
    echo '<td><a href="../../includes/RestoreUser.php?UserID=' .$userID . '" class="btn btn-success">Restore</a></td>';
  }else{
    echo '<td><a href="../../includes/DeleteUser.php?UserID=' . $userID . '"class="btn btn-danger">Delete</a></td>';
  }
}
 ?>
